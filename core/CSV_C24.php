<?php

require "CSVReader.php";


class CSV_C24 extends CSVReader
{
    protected function convertData()
    {
        foreach( $this->csvData as $row )
        {
            $newRow = [];
            $newRow["date"] = $row["Buchungsdatum"];
            $newRow["description"] = $row["Verwendungszweck"];
            $newRow["amount"] = $row["Betrag"];
            $newRow["client"] = $row["Zahlungsempfänger"];
            $newRow["iban"] = $row["IBAN"];
            $newRow["bic"] = $row["BIC"];
            $newRow["reference"] = $row["Beschreibung"];
            $newRow["category"] = "";
            $newRow["costcenter"] = "";
            $newRow["account"] = "C24";

            $this->data[] = $newRow;            
        }
    }
}