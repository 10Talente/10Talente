<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );

require "core/Database.php";

$db = new Database();

$query = "SELECT * FROM 'categories'";
$result = $db->execQuery( $query );

echo "<pre>";
print_r( $result );
echo "</pre>";