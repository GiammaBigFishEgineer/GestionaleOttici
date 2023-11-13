<?php

require_once dirname(__DIR__) . "/views/PDFView.php";
require_once dirname(__DIR__) . "/models/OptometriaModel.php";
require_once dirname(__DIR__) . "/models/LeadModel.php";

//require_once(__ROOT__ . '/config/DB.php');

use Mpdf\Mpdf;
use Mpdf\HTMLParserMode;

require_once(__ROOT__ . '/utils/ClassUtils.php');
class PDFController
{

    public static function createPDF(){
        $data = $_POST;

        switch ($data["object_type"]){
            case "/optometria":
                $optometria = OptometriaModel::get($data["id"]);
                $cliente = LeadModel::get($optometria->{"ID Cliente"});

                self::generatePDF(1, array($optometria, $cliente));


                break;
            case "/cliente":
                $obj = LeadModel::get($data["id"]);
                
                self::generatePDF(0, array($obj));
                break;
        }

        echo "OK";
    }

    public static function generatePDF(int $type, array $obj){
        //TODO: Usare un'enum con uno case statement

        //Optometria
        if($type == 1){
            $pdf = new Mpdf();
            $pdf->AddPage();
            
            
            $view = new PdfView();
            $html = $view->optometriaPDF($obj[0], $obj[1]);
            $css = $view->optometriaCSS();

            $pdf->WriteHTML($css, HTMLParserMode::HEADER_CSS);
            $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

            $directory = __ROOT__ . "/user_data/pdf/". OptometriaModel::$nome_tabella . "/";
            $filename = $obj[0]->ID. ".pdf";
    
            if(!is_dir($directory)){
                mkdir($directory, recursive: true);
            }
    
            $pdf->Output($directory . $filename, \Mpdf\Output\Destination::FILE);
            // $pdf->simpleTables = false;
        } else if ($type == 0) {
            $pdf = new Mpdf([
                'margin_left' => 8,
                'margin_right' => 8,
                'margin_top' => 8,
                'margin_bottom' => 8,
            ]);
            $pdf->AddPage();
            
            $view = new PdfView();
            $html = $view->leadPDF($obj[0]);
            $css = $view->leadCSS();

            $pdf->WriteHTML($css, HTMLParserMode::HEADER_CSS);
            $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

            $directory = __ROOT__ . "/user_data/pdf/". LeadModel::$nome_tabella . "/";
            $filename = $obj[0]->ID. ".pdf";
    
            if(!is_dir($directory)){
                mkdir($directory, recursive: true);
            }
    
            $pdf->Output($directory . $filename, \Mpdf\Output\Destination::FILE);
        }
    }
}


?>