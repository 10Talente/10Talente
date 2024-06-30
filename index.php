<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );

require_once "core/Database.php";

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datei-Upload</title>
    <style>
        html {
            font-family: sans-serif;
        }
    </style>
</head>
<body>
    <h1>10 Talente</h1>
    <h2>CSV Konverter</h2>
    <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" enctype="multipart/form-data" accept-charset="utf-8">
        <label for="file-type">Type: </label>
        <select name="file-type" id="file-type">
            <option value="cdb">CDB</option>
            <option value="c24">C24</option>
            <option value="dkb">DKB</option>
        </select> <br>
        <input type="file" id="file-upload" name="uploadedFile">
        <br><br>
        <input type="submit" value="Hochladen">
    </form>
<?php

if( 0 < count( $_FILES ) )
{
//    echo "<pre>";
//    print_r( $_POST );
//    echo "</pre>";

    if( "dkb" == $_POST['file-type'] )
    {
        require "core/CSV_DKB.php";
        try
        {
            $csv = new CSV_DKB( $_FILES['uploadedFile']['tmp_name'] );
            echo "<pre>";
            print_r( $csv->csvData() ); 
            echo "</pre>";
        }
        catch( Exception $e )
        {
            echo "<p>" . $e->getMessage() . "</p>";
        }
    }
    else if( "cdb" == $_POST['file-type'] )
    {
        require "core/CSV_CDB.php";
        try
        {
            $csv = new CSV_CDB( $_FILES['uploadedFile']['tmp_name'] );
            echo "<pre>";
            print_r( $csv->csvData() ); 
            echo "</pre>";
        }
        catch( Exception $e )
        {
            echo "<p>" . $e->getMessage() . "</p>";
        }
    }
    else if( "c24" == $_POST['file-type'] )
    {
        require "core/CSV_C24.php";
        try
        {
            $csv = new CSV_C24( $_FILES['uploadedFile']['tmp_name'], "," );
            echo "<pre>";
            print_r( $csv->csvData() ); 
            echo "</pre>";
        }
        catch( Exception $e )
        {
            echo "<p>" . $e->getMessage() . "</p>";
        }
    }
    else
    {
        echo "<p>Unknown file type " . $_POST['file-type'];
    }
}

?>
</body>
</html>