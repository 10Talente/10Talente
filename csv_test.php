<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );

require_once "core/CSVReader.php";

$filename = "data/cdb_2023_1.csv";
$fieldCount = 6;

$csv = new CSVReader( $filename );
echo "<pre>";
print_r( $csv->data() );
echo "</pre>";