<?php
require_once(__ROOT__ . '/vendor/autoload.php');
require_once(__ROOT__ . '/config/DB.php');


require_once('BaseModel.php');

use CodeInc\StripAccents\StripAccents;

class LeadModel extends BaseModel
{
    public static string $nome_tabella = 'AN_Clienti';
    
    protected array $_fields = [
        "ID",
        "Cliente",
        "Nazione",
        "Località",
        "Provincia",
        "Data di Nascita",
        "Sesso",
        "Codice Fiscale",
        "Telefono",
        "Telefono Cellulare",
        "Indirizzo Email",
        "Partita IVA",
        "Ultima modifica",
    ];
   
    public static function rAll()
    {
        $sql = "SELECT `ID`, `Cliente`,`Località`, `Provincia`,`Indirizzo Email`,`Telefono Cellulare`,`Ultima modifica` FROM " . LeadModel::$nome_tabella;
        $list = DB::get()->query($sql);
        //salvo il rendering in array
        $leads = array();
        foreach ($list as $row) {
            $lead = array();
            $lead['ID'] = $row['ID'];
            $lead['Cliente'] = $row['Cliente'];
            $lead['Località'] = $row['Località'];
            $lead['Provincia'] = $row['Provincia'];       
            $lead['Indirizzo Email'] = $row['Indirizzo Email'];
            $lead['Telefono Cellulare'] = $row['Telefono Cellulare'];
            $lead['Ultima modifica'] = $row['Ultima modifica'];
            $leads[] = $lead;
        }
        return $leads;
    }

    // public function getSafeIdentifiers(){
    //     $lead = array();
    //     foreach($this->_fields as $field){
    //         $lead[StripAccents::strip($field)] = $this->{$field};
    //     }
    //     return $lead;
    // }
    
    public static function getDisplayValues(){
        
        $sql = "SELECT  `ID`, `Cliente`, `Località`, `Provincia`, `Indirizzo Email`,`Telefono Cellulare`,`Ultima modifica` FROM " . LeadModel::$nome_tabella;
        // $sql = mb_convert_encoding($sql, "ISO", "");
        $list = DB::get()->query($sql);
        //salvo il rendering in array
        $leads = array();
        foreach ($list as $row) {
            $lead = array();
            $lead['ID'] = $row['ID'];
            $lead['Cliente'] = $row['Cliente'];
            $lead['Localita'] = $row['Località'];
            $lead['Provincia'] = $row['Provincia'];       
            $lead['Indirizzo Email'] = $row['Indirizzo Email'];
            $lead['Telefono Cellulare'] = $row['Telefono Cellulare'];
            $lead['Ultima modifica'] = $row['Ultima modifica'];
            $leads[] = $lead;
        }
        return $leads;
    }

    public function generatePDF(){
        $pdf = new \Mpdf\Mpdf();
        $pdf->AddPage();
        
        foreach($this->_fields as $field){
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(40,10,$field.':');

            if($this->$field != null){
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(strlen($this->$field),10,$this->$field);
            }
            $pdf->Ln(5);
        }

        $directory = __ROOT__ . "/user_data/pdf/". LeadModel::$nome_tabella . "/";
        $filename = $this->ID. ".pdf";

        if(!is_dir($directory)){
            mkdir($directory, recursive: true);
        }

        $pdf->Output($directory . $filename, \Mpdf\Output\Destination::FILE);
    }
    
    /* CALCULATED FIELDS */
    private int $eta = 0;
    // private string $fullname = "";

    public function getEta()
    {
        if ($this->{"Data di Nascita"} != null){
            if (!$this->eta) {
                    $date1 = new DateTime($this->{"Data di Nascita"});
                    $date2 = new DateTime();
                
                if($date1 != $date2){
                    $this->eta = date_diff($date2, $date1)->y; //$date1->diff($date2)->y;
                }
            }
        }


        return $this->eta;
    }

    public function getLocalità()
    {
        return $this->{"Localita"};
    }

    public function setLocalità($value)
    {
        $this->{"Localita"} = $value;
    }

    public function getSimpleBirthday()
    {   
        $date = date("Y-m-d", strtotime($this->{"Data di Nascita"}));
        return $date;
    }

    // public function getLocalità()
    // {
    //     $field = "Località";
    //     $value = $this->_data[mb_convert_encoding($field, "ISO-8859-1", "UTF-8")];
    //     return $this->_data[mb_convert_encoding($field, "ISO-8859-1", "UTF-8")];
    // }
    // public function getFullName()
    // {
    //     if (!$this->fullname) {
    //         $this->fullname = $this->nome . " " . $this->cognome;
    //     }

    //     return $this->fullname;
    // }
}
