<?php

require_once dirname(__DIR__) . "/models/LeadModel.php";
require_once dirname(__DIR__) . "/views/LeadView.php";
require_once(__ROOT__ . '/views/OptometriaView.php');
require_once(__ROOT__ . '/utils/ClassUtils.php');

class LeadController
{
    public static function apiList()
    {
        $draw = isset($_GET['draw']) ? $_GET['draw'] : 0;
        $start = isset($_GET['start']) ? $_GET['start'] : 0;
        $length = isset($_GET['length']) ? $_GET['length'] : 10;

        $search = array();
        if (isset($_GET['search']['value']) && $_GET['search']['value'] != '')
            $search["Cliente"] = $_GET['search']['value'];

        if (isset($_GET['search2']['value']) && $_GET['search2']['value'] != '')
            $search["Codice Fiscale"] = $_GET['search2']['value'];

        $data = LeadModel::paginate("Cliente",$draw, $start, $length, $search, true);
        //echo json_encode( $_GET['search2']);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

    public static function list()
    {
        //rendering template
        $view = new LeadView();
        $view->render();
    }

    public static function show()
    {
        $id =  isset($_GET["id"]) ? (int) $_GET["id"] : null;
        $cliente = null;

        if($id != NULL){
            $cliente = LeadModel::get($id);
        }

        $view = new LeadView();
        $view->show($cliente, $id);
    }
    
    public static function save(){
        $data = remove_underscores_from_array($_POST);
        $data["Ultima modifica"] = date("Y-m-d H:i:s", time());

        if($data["ID"] == ""){
            $data["ID"] = LeadModel::max()+1;
        } 
        $lead = new LeadModel($data);

        

        if (!$lead->validate()) {
            $_SESSION["error"] = implode(', ', $lead->errors);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
        try {
            $id = $lead->save();
            $lead->generatePDF();
            $_SESSION["message"] = "Il cliente è stato salvato con successo.";

            //Test
            header("Location: /cliente?id={$lead->ID}");
        } catch (Exception $err) {
            $_SESSION["error"] = "Si è verificato un errore durante il salvataggio del cliente.";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }

    public static function delete()
    {
        $id = (int) $_GET["id"];
        try {
            LeadModel::delete($id);
            $_SESSION["message"] = "Il cliente è stato rimosso con successo.";
        } catch (Exception $err) {
            $_SESSION["error"] = "Si è verificato un errore durante la rimossione del cliente.";
        }

        header('Location: /lista_clienti');
    }

    public function getCliente($id){
        $cliente = LeadModel::get((int)$id);
        $client_id = $id;


        if ($client_id) {
            $optometrie = OptometriaModel::where(array('ID Cliente' => $client_id));
        } else {
            $optometrie = OptometriaModel::all();
        }

        $view = new LeadView();
        $view->get($cliente,$optometrie);
    }
}
