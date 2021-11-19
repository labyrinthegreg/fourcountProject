<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;


class ExportCsvService
{
    public function __construct(){
    }

    /**
     * Create CSV file from Fourcount expenses
     */
    public function createExpensesCsv($expenses){
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
        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename . '');

        return $response;
    }    
    /**
     * Create CSV file from Fourcount balance
     */
    public function createBalanceCsv($balanceArray){
        // Set delimiter
        $filename = "balance_data" . date('Y-m-d') . ".csv"; 

        $delimiter = ","; 
        // Create a file pointer 
        $file = fopen('php://output', 'w'); 
        // Set fields
        $fields = ['Username', 'BALANCE'];
        fputcsv($file, $fields, $delimiter); 

        foreach ($balanceArray as $key => $value ) {
            $line = [$key, $value];
            fputcsv($file, $line, $delimiter); 
        }

            // Move back to beginning of file 
        fclose($file); 
        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename . '');
        return $response;
    }
}