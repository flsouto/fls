<?php

function db(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $host = getenv('DB_HOST') ?: 'localhost';
        $db   = getenv('DB_NAME') ?: 'performance_tracker';
        $user = getenv('DB_USER') ?: 'postgres';
        $pass = getenv('DB_PASS') ?: 'postgres';
        $port = getenv('DB_PORT') ?: '5431';

        $dsn = "pgsql:host=$host;port=$port;dbname=$db";

        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    return $pdo;
}

/* ================= Helpers ================= */

function query(string $sql, array $replacements = []) {
    $pdo = db();

    $trimmed = ltrim($sql);
    $type = 'SELECT';

    if (stripos($trimmed, 'UPDATE') === 0) $type = 'UPDATE';
    if (stripos($trimmed, 'INSERT') === 0) $type = 'INSERT';
    if (stripos($trimmed, 'DELETE') === 0) $type = 'DELETE';

    // --- NEW: expand array parameters ---
    $finalParams = [];
    $paramIndex = 0;

    if (array_is_list($replacements)) {
        // positional params (?)
        $parts = explode('?', $sql);
        $newSql = array_shift($parts);

        foreach ($parts as $i => $part) {
            $value = $replacements[$i] ?? null;

            if (is_array($value)) {
                if (empty($value)) {
                    // edge case: IN ()
                    $newSql .= 'NULL';
                } else {
                    $placeholders = implode(',', array_fill(0, count($value), '?'));
                    $newSql .= $placeholders;
                    foreach ($value as $v) {
                        $finalParams[] = $v;
                    }
                }
            } else {
                $newSql .= '?';
                $finalParams[] = $value;
            }

            $newSql .= $part;
        }

        $sql = $newSql;
    } else {
        // named params (:key)
        foreach ($replacements as $key => $value) {
            if (is_array($value)) {
                if (empty($value)) {
                    $sql = str_replace(":$key", 'NULL', $sql);
                } else {
                    $placeholders = [];
                    foreach ($value as $i => $v) {
                        $ph = ":{$key}_$i";
                        $placeholders[] = $ph;
                        $finalParams[$ph] = $v;
                    }
                    $sql = str_replace(":$key", implode(',', $placeholders), $sql);
                }
            } else {
                $finalParams[":$key"] = $value;
            }
        }
    }

    $stmt = $pdo->prepare($sql);

    // bind params
    if (array_is_list($finalParams)) {
        foreach ($finalParams as $i => $value) {
            $stmt->bindValue($i + 1, $value);
        }
    } else {
        foreach ($finalParams as $key => $value) {
            $stmt->bindValue($key, $value);
        }
    }

    $stmt->execute();

    if ($type === 'SELECT') {
        return $stmt->fetchAll();
    }

    try {
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        return $type === 'INSERT'
            ? ['lastInsertId' => $pdo->lastInsertId()]
            : $stmt->rowCount();
    }
}

function qrow(string $sql, array $replacements = []) {
    $rows = query($sql, $replacements);
    return ($rows && count($rows)) ? $rows[0] : null;
}

function qvals(string $sql, array $replacements = []) {
    $rows = query($sql, $replacements);
    if (!$rows) return [];
    return array_map(fn($row) => array_values($row)[0] ?? null, $rows);
}

function qval(string $sql, array $replacements = []) {
    $values = queryValues($sql, $replacements);
    return ($values && count($values)) ? $values[0] : null;
}

function qkv(string $sql, array $replacements = []) {
    $rows = query($sql, $replacements);
    $out = [];

    foreach ($rows as $row) {
        $vals = array_values($row);
        if (count($vals) >= 2) {
            $out[$vals[0]] = $vals[1];
        }
    }

    return $out;
}

function insert(string $table, array $data) {
    $pdo = db();

    $cols = array_keys($data);
    $placeholders = implode(', ', array_fill(0, count($cols), '?'));

    $sql = sprintf(
        'INSERT INTO "%s" (%s) VALUES (%s) RETURNING *',
        $table,
        implode(', ', array_map(fn($c) => "\"$c\"", $cols)),
        $placeholders
    );

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_values($data));

    $rows = $stmt->fetchAll();
    return $rows[0] ?? null;
}

function update(string $table, array $data, array $where) {
    $pdo = db();

    $setCols = array_keys($data);
    $whereCols = array_keys($where);

    $setSql = implode(', ', array_map(fn($c) => "\"$c\" = ?", $setCols));
    $whereSql = implode(' AND ', array_map(fn($c) => "\"$c\" = ?", $whereCols));

    $sql = sprintf(
        'UPDATE "%s" SET %s WHERE %s RETURNING *',
        $table,
        $setSql,
        $whereSql
    );

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_merge(array_values($data), array_values($where)));

    return $stmt->fetchAll();
}

function upsert(string $table, array $data, array $where) {
    $whereParts = [];
    foreach ($where as $k => $v) {
        $whereParts[] = "\"$k\" = :$k";
    }

    $sql = sprintf(
        'SELECT id FROM "%s" WHERE %s',
        $table,
        implode(' AND ', $whereParts)
    );

    $id = qval($sql, $where);

    if ($id) {
        unset($data['id']);
        return update($table, $data, $where);
    } else {
        return insert($table, array_merge($data, $where));
    }
}
