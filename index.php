<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );

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
    <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" enctype="multipart/form-data">
        <label for="file-type">Type: </label>
        <select name="file-type" id="file-type">
            <option value="dkb">DKB</option>
        </select> <br>
        <input type="file" id="file-upload" name="uploadedFile">
        <br><br>
        <input type="submit" value="Hochladen">
    </form>
<?php

if( 0 < count( $_FILES ) )
{
    if( "dkb" == $_POST['file-type'] )
    {
        require "core/CSV_DKB.php";

        $csv = new CSV_DKB( $_FILES['uploadedFile']['tmp_name'] );
        echo "<pre>";
        print_r( $csv->csvData() ); 
        echo "</pre>";
    }
    else
    {
        echo "<p>Unknown file type " . $_POST['file-type'];
    }
}

?>
</body>
</html>