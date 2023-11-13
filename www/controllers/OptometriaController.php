<?php

require_once(__ROOT__ . '/models/OptometriaModel.php');
require_once(__ROOT__ . '/views/OptometriaView.php');
require_once(__ROOT__ . '/utils/ClassUtils.php');

class OptometriaController
{

    public static function list()
    {
        $client_id = isset($_GET['client_id']) ? $_GET['client_id'] : null;
        if ($client_id) {
            $optometrie = OptometriaModel::where(array('ID Cliente' => $client_id));
        } else {
            $optometrie = OptometriaModel::all();
        }

        //rendering template
        $view = new OptometriaView();
        $view->list($optometrie);
    }

    public static function show()
    {
        //TODO: Aggiustare il cambio di pagina
        $id =  isset($_GET["id"]) ? (int) $_GET["id"] : null;
        $client_id = isset($_GET["client_id"]) ? (int) $_GET["client_id"] : null;

        $optometria = null;
        if ($id != NULL) {
            $optometria = OptometriaModel::get($id);
            $client = $optometria->cliente;
        }

        if ($client_id != NULL) {
            $client = LeadModel::get($client_id);
        }

        $view = new OptometriaView();
        $view->form($optometria, $client);
    }

    public static function save()
    {
        $data = remove_underscores_from_array($_POST);
        $optometria = new OptometriaModel($data);

        if (!$optometria->validate()) {
            $_SESSION["error"] = implode(', ', $optometria->errors);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }

        try {
            $lead = LeadModel::get($optometria->{"ID Cliente"});

            $lead->{"Ultima modifica"} = date("Y-m-d H:i:s", time());
            
            $lead->save();
            
            $id = $optometria->save();
            $optometria->generatePDF();
            $_SESSION["message"] = "La scheda optometrica è stata salvata con successo.";

            header("Location: /optometria?id={$optometria->ID}");
        } catch (Exception $err) {
            $_SESSION["error"] = "Si è verificato un errore durante il salvataggio della scheda optometrica.";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }

    public static function delete()
    {
        $id = (int) $_POST["id"];

        try {
            OptometriaModel::delete($id);
            $_SESSION["message"] = "La scheda optometrica è stata eliminata con successo.";
        } catch (Exception $err) {
            $_SESSION["error"] = "Si è verificato un errore durante l'eliminazione della scheda optometrica.";
        }

        header('Location: /optometrie');
    }
}
