<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );

//require_once "core/CSVReader.php";
require "core/CSV_DKB.php";

//$filename = "data/cdb_2023_1.csv";
$filename = "data/dkb_2022.csv";

$csv = new CSV_DKB( $filename );
echo "<pre>";
print_r( $csv->data() );
echo "</pre>";