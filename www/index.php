<?php

define('__ROOT__', dirname(__FILE__));

// * Controllers
require_once(__ROOT__ . '/controllers/LeadController.php');
require_once(__ROOT__ . '/controllers/UserController.php');
require_once(__ROOT__ . '/controllers/PDFController.php');
require_once(__ROOT__ . '/controllers/OptometriaController.php');

// * Views
require_once(__ROOT__ . '/views/StaticView.php');

// * Utilities
require_once(__ROOT__ . '/utils/Cache.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


session_start();
mb_internal_encoding("ISO-8859-1");
// mb_internal_encoding("UTF-8");
ini_set('memory_limit', '4096M');
// setlocale(LC_ALL, "en_US.utf8");
/*
 * DISPATCHER BASATO SU MVC, OGNI URL USA UN CONTROLLER PER ACCEDERE AL MODELLO E INTERFACCIARSI CON UNA VIEW
*/

setup_caching();

class Dispatcher
{
    private $method;
    private $path;

    public function __construct()

    {
        // mb_internal_encoding("UTF-8");
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    }

    public function dispatch()
    {
        switch ($this->path) {
            case '/':
                $view = new StaticView();
                $view->render();
                break;
            
            case '/login':
                if ($this->method == "GET") {
                    UserController::showLogin();        
                } else if ($this->method == "POST") {
                    UserController::login();
                }
                break;

            case '/api/clients/list':
                $controller = new LeadController();
                $controller->apiList();
                break;

            case '/lista_clienti':
                LeadController::list();
                break;

            case '/post_cliente':
                if ($this->method == "GET") {
                    LeadController::show();
                } else if ($this->method == "POST") {
                    LeadController::save();
                } else {
                    echo "Unsupported method";
                }
                break;

            case '/elimina_cliente':
                LeadController::delete();
                break;

            case '/cliente':
                $id = $_GET['id'];
                $controller = new LeadController();
                $controller->getCliente($id);
                break;

            case '/optometrie':
                OptometriaController::list();
                break;

            case '/optometria':
                if ($this->method == "GET") {
                    OptometriaController::show();
                } else if ($this->method == "POST") {
                    OptometriaController::save();
                } else {
                    echo "Unsupported method";
                }
                break;

            case '/optometria':
                if ($this->method == "POST") {
                    OptometriaController::delete();
                } else {
                    echo "Unsupported method";
                }

                break;

            case '/create_pdf':
                PDFController::createPDF();
                break;
            default:
                //lista clienti
                echo "404 HTML<br>";
                echo $this->path;

                //$controller = new LeadController();
                //$controller->show();
                break;
        }
    }
}

$dispatcher = new Dispatcher();
$dispatcher->dispatch();
