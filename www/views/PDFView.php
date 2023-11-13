<?php

require_once(__ROOT__ . '/models/OptometriaModel.php');
require_once(__ROOT__ . '/models/LeadModel.php');
require_once('BaseView.php');

class PdfView extends BaseView
{
    public function leadPDF(LeadModel $cliente): string
    {
        return $this->twig->render('pdf/lead.html.twig', [
            'date' => date("Y/m/d"),
            'cliente' => $cliente
        ]);
    }

    public function leadCSS(){
        return file_get_contents(__ROOT__ . "/templates/pdf/css/lead.css");
    }

    public function optometriaPDF(OptometriaModel $optometria, LeadModel $cliente): string
    {
        return $this->twig->render('pdf/optometria.html.twig', [
            'optometria' => $optometria,
            'cliente' => $cliente
        ]);
    }

    public function optometriaCSS(){
        return file_get_contents(__ROOT__ . "/templates/pdf/css/optometria.css");
    }

}
