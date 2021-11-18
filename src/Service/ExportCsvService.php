<?php

namespace App\Service;


class ExportCsvService
{
    public function __construct(){
    }

    public function createCsv($expenses){
        $filename = "expense_data" . date('Y-m-d') . ".csv"; 
        // Set delimiter
        $delimiter = ","; 
        // Create a file pointer 
        $file = fopen('php://output', 'w'); 
        // Set fields
        $fields = ['ID', 'TITLE', 'AMOUNT', 'DESCRIPTION', 'DATE'];
        fputcsv($file, $fields, $delimiter); 

        foreach ($expenses as &$expense) {
            $line = [$expense->getId(), $expense->getTitle(), $expense->getAmount() . " euros",
            $expense->getDescription(), $expense->getDate()->format('Y-m-d')];
            fputcsv($file, $line, $delimiter); 
        }

            // Move back to beginning of file 
        fclose($file); 
        
    
    }
}