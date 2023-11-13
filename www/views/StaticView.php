<?php

require_once(__ROOT__ . '/vendor/autoload.php');

/*
 * OGNI VIEW è ASSOCIATA AL RENDERING DI UNA PAGINA E AL RELATIVO TEMPLATE
 * L'USO DI TWIG PERMETTE DI PASSARE LE VARIABILI AL TEMPLATE IN MODO FACILE E VELOCE
 */

class StaticView
{
    static private $navbarContent =
    '
    <li class="nav-item active">
        <a class="nav-link fw-bold" href="#"> Promemoria <span class="sr-only">(current)</span></a>
    </li>
    <li class="nav-item active">
        <a class="nav-link fw-bold" href="#"> Sistema T.S. <span class="sr-only">(current)</span></a>
    </li>
    <li class="nav-item active">
        <a class="nav-link fw-bold" href="#"> Sistema F.E. <span class="sr-only">(current)</span></a>
    </li>
    <li class="nav-item active">
        <a class="nav-link fw-bold" href="#">Statistiche <span class="sr-only">(current)</span></a>
    </li>
    ';

    public function render()
    {
        // Carica il template Twig
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);
        // Passa la lista dei clienti alla vista
        echo $twig->render('static/home.html.twig', 
            ["dynamicNavbar" => self::$navbarContent]);
    }
}

?>