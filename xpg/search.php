<?php
require_once(__DIR__."/boot.php");

function qsearch(string $table, string $term, array $columns = null): array {
    $pdo = db();

    // If columns not provided, fetch them from information_schema
    if ($columns === null) {
        $cols = query(
            'SELECT column_name 
             FROM information_schema.columns 
             WHERE table_name = :table',
            ['table' => $table]
        );

        $columns = array_map(fn($c) => $c['column_name'], $cols);
    }

    if (!$columns || count($columns) === 0) {
        return [];
    }

    // Build WHERE conditions
    $conditions = [];
    $params = [];

    foreach ($columns as $i => $col) {
        $param = "term_$i";
        $conditions[] = "\"$col\"::text ILIKE :$param";
        $params[$param] = "%$term%";
    }

    $sql = sprintf(
        'SELECT id FROM "%s" WHERE %s',
        $table,
        implode(' OR ', $conditions)
    );

    return query($sql, $params);
}

$rows = qsearch($argv[1], $argv[2]);

if(empty($rows)){
    die("Nothing returned from search\n");
}

//echo implode("\n", array_column($rows,'id'))."\n";

$state['tid'] = $tid = count($state['tabs']??[])+1;
$state['tabs'][] = [
    'id' => $tid,
    'name' => $argv[1].' '.$argv[2],
    'count' => count($rows)
];
$state->save();

$tab = jsondb("data/t$tid.json");
$tab['table'] = $argv[1];
$tab['data'] = $rows;
$tab->save();

passthru('php view.php');
