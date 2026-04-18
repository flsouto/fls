<?php

require_once(__DIR__.'/boot.php');

$rows = query("
    SELECT
      table_name,
      column_name,
      data_type
    FROM information_schema.columns
    WHERE table_schema = 'public'
    ORDER BY table_name, ordinal_position
");

$types = [];
foreach($rows as $row){
    $types[$row['table_name']][$row['column_name']] = $row['data_type'];
}

$schema['types'] = $types;

$schema['relations'] = query("
    SELECT
      parent.relname AS parent,
      child.relname  AS child,
      child_col.attname  AS fk
    FROM pg_catalog.pg_constraint con
    JOIN pg_catalog.pg_class child
      ON child.oid = con.conrelid
    JOIN pg_catalog.pg_class parent
      ON parent.oid = con.confrelid
    JOIN pg_catalog.pg_namespace nsp
      ON nsp.oid = child.relnamespace
    JOIN unnest(con.conkey)  WITH ORDINALITY AS ck(attnum, ord) ON true
    JOIN unnest(con.confkey) WITH ORDINALITY AS fk(attnum, ord) ON ck.ord = fk.ord
    JOIN pg_catalog.pg_attribute child_col
      ON child_col.attrelid = child.oid AND child_col.attnum = ck.attnum
    JOIN pg_catalog.pg_attribute parent_col
      ON parent_col.attrelid = parent.oid AND parent_col.attnum = fk.attnum
    WHERE
      con.contype = 'f'
      AND nsp.nspname = 'public'
    ORDER BY parent.relname, child.relname, con.conname, ck.ord;

");

$schema->save();
