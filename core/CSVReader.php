<?php

class CSVReader 
{
    private $filename = "";
    private $separator = ";";
    private $expectedFieldCount;
    private $header = null;
    protected $csvData = [];
    protected $data = [];


    function __construct( $filename, $expectedFieldCount=null, $separator=";" )
    {
        $this->filename = $filename;
        $this->separator = $separator;
        $this->getFieldCount();
        $this->read();
        $this->convertData();
    }


    private function read()
    {
        if( is_file( $this->filename ) and is_readable( $this->filename ) ) 
        {
            $this->data = [];
            $header = null;

            ini_set( "auto_detect_line_endings", true );
            if( ( $handle = fopen( $this->filename, "r" ) ) !== false )
            {
                while( ( $row = fgetcsv( $handle, 0, $this->separator ) ) !== false ) 
                {
                    if( $this->isEmptyRow( $row ) ) continue;       // skip empty rows

                    if( !$header )
                    {
                        if( $this->isHeaderRow( $row ) )
                        {
                            $header = $row;
                        }
                    }
                    else
                    {
                        for( $i = 0; $i < count( $row ); $i++ ) 
                        {
                            $row[$i] = trim( $row[$i] );
                            $row[$i] = preg_replace( '#\s+#' , ' ' , $row[$i] );
                        }
                        $this->csvData[] = array_combine( $header, $row );
                    }
                }
                fclose( $handle );
            }
            ini_set( "auto_detect_line_endings", false );
        }
        else
            throw new Exception( "CSV file not found or not readable" );
    }


    protected function convertData()
    {
        $this->data = $this->csvData;
    }


    public function data()
    {
        return $this->data;
    }


    public function csvData()
    {
        $lines = "";
        $line = "";
        foreach( array_keys( $this->data[0] ) as $key )
        {
            $line .= '"' . $key . '",';
        }
        $lines .= substr( $line, 0, -1 ) . "\n";
        foreach( $this->data as $row )
        {
            $line = "";
            foreach( $row as $cell )
            {
                $line .= '"' . $cell . '",';
            }
            $lines .= substr( $line, 0, -1 ) . "\n";
        }
        return $lines;
    }


    private function getFieldCount()
    {
        if( is_file( $this->filename ) and is_readable( $this->filename ) ) 
        {
            ini_set( "auto_detect_line_endings", true );
            if( ( $handle = fopen( $this->filename, "r" ) ) !== false )
            {
                while( ( $row = fgetcsv( $handle, 0, $this->separator ) ) !== false ) 
                {
                    $count = count( $row );
                    if( $count > $this->expectedFieldCount ) $this->expectedFieldCount = $count;
                }
                fclose( $handle );
            }
            ini_set( "auto_detect_line_endings", false );
        }
        else
            throw new Exception( "CSV file not found or not readable" );
    }


    /**
     * Die Methode prüft, ob alle Elemente im Array leer sind, um so eine leere
     * Zeile zu erkennen.
     */
    private function isEmptyRow( array $row ): bool
    {
        if( count( $row ) < $this->expectedFieldCount ) return false;
        foreach( $row as $field )
            if( !empty( $field ) )
                return false;
        return true;
    }


    /**
     * Die Methode prüft, ob der Suchbegriff in der Zeile vorhanden ist, um so 
     * die Kopfzeile zu finden.
     */
    private function isHeaderRow( array $row ): bool
    {
//        return preg_match( "/^" . $this->headerLinePattern . "/i", implode( $this->separator, $row ) );
        return count( $row ) == $this->expectedFieldCount;
    }
}