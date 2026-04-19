<?php
require_once(__DIR__."/boot.php");

function search(string $table, string $term, array $columns = null): array {

    global $schema;

    // If columns not provided, fetch them from information_schema
    if ($columns === null) {
        $columns = array_keys($schema['types'][$table]);
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

$rows = search($argv[1], $argv[2]);

if(empty($rows)){
    die("Nothing returned from search\n");
}

newtab($argv[1], $rows, $argv[1].' '.$argv[2]);

view();
