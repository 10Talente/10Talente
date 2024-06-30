<?php

require "CSVReader.php";


class CSV_CDB extends CSVReader
{
    protected function convertData()
    {
        foreach( $this->csvData as $row )
        {
            $retVal = $this->parseText( $row["Buchungstext"] );

            $newRow = [];
            $newRow["date"] = $row["Buchungstag"];
            $newRow["description"] = $retVal["Buchungstext"];
            $newRow["amount"] = $row["Umsatz in EUR"];
            $newRow["client"] = $retVal["Partner"];
            $newRow["iban"] = "";
            $newRow["bic"] = "";
            $newRow["reference"] = $retVal["Referenz"];
            $newRow["category"] = "";
            $newRow["costcenter"] = "";
            $newRow["account"] = "CDB";

            $this->data[] = $newRow;
        }
    }


    private function parseText( $text )
    {
        // @see https://regex101.com/
        $matches = [];
        $retVal = array(
            "Buchungstext" => "",
            "Partner" => "",
            "IBAN" => "",
            "BIC" => "",
            "Referenz" => ""
        );
        if( str_starts_with( $text, "Buchungstext: " ) )
        {
            $pattern = "/^Buchungstext: (.+?)Ref\. (.+)/";
            preg_match( $pattern, $text, $matches );
            $retVal["Buchungstext"] = $matches[1];
            $retVal["Referenz"] = $matches[2];
        }
        else if( str_starts_with( $text, "Auftraggeber: " ) )
        {
            $pattern = "/^Auftraggeber: (.+?)Buchungstext: (.+?)Ref\. (.+)/";
            preg_match( $pattern, $text, $matches );
            $retVal["Partner"] = $matches[1];
            $retVal["Buchungstext"] = $matches[2];
            $retVal["Referenz"] = $matches[3];
        }
        else if( str_starts_with( $text, "Empf?nger: " ) )
        {
            $pattern = "/^Empf\?nger: (.+?)Kto\/IBAN: (.+?)BLZ\/BIC: (.+?)Buchungstext: (.+?)Ref. (.+)/";
            preg_match( $pattern, $text, $matches );
//            echo "<pre>";
//            print_r( $matches );
//            echo "</pre>";
            $retVal["Partner"] = $matches[1];
            $retVal["IBAN"] = $matches[2];
            $retVal["BIC"] = $matches[3];
            $retVal["Buchungstext"] = $matches[4];
            $retVal["Referenz"] = $matches[5];
        }
        else
        {
            echo "<p>Unknown linestart in $text</p>";
        }
        return $retVal;
    }
}