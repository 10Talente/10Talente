<?php

require "CSVReader.php";


class CSV_DKB extends CSVReader
{
    protected function convertData()
    {
        foreach( $this->csvData as $row )
        {
            $newRow = [];
            $newRow["date"] = $row["Buchungsdatum"];
            $newRow["description"] = $row["Verwendungszweck"];
            $newRow["amount"] = $row["Betrag (€)"];
            $newRow["outCat"] = "";
            $newRow["inCat"] = "";
            $newRow["client"] = $row["Zahlungsempfänger*in"];
            $newRow["iban"] = $row["IBAN"];
            $newRow["bic"] = "";
            $newRow["reference"] = $row["Kundenreferenz"];
            $newRow["category"] = "";
            $newRow["costcenter"] = "";
            $newRow["account"] = "DKB";

            $this->data[] = $newRow;
        }
    }
}