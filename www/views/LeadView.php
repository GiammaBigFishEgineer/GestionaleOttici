<?php

require_once(__ROOT__ . '/vendor/autoload.php');

require_once('BaseView.php');

/*
 * OGNI VIEW è ASSOCIATA AL RENDERING DI UNA PAGINA E AL RELATIVO TEMPLATE
 * L'USO DI TWIG PERMETTE DI PASSARE LE VARIABILI AL TEMPLATE IN MODO FACILE E VELOCE
 */

class LeadView extends BaseView
{

    public function render()
    {
        // Carica il template Twig
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);
        // Passa la lista dei clienti alla vista
        echo $twig->render('clienti/lista_clienti.html.twig', [
            'action' => '/api/clients/list',
        ]);
    }

    public function show($cliente, $id){
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);

        if($id !== null){
            echo $twig->render('clienti/form.html.twig', [
                'action' => '/post_cliente?id=' . $id,
                'cliente' => $cliente,
            ]);
        } else {
            echo $twig->render('clienti/form.html.twig', [
                'action' => '/post_cliente',
            ]);
        }
    }

    static private $navbarContent = 
    '
    <li class="nav-item active">
        <button class="btn btn-outline-success my-2 my-sm-0" onclick="showCategory(`anagrafica`)" type="submit">Anagrafica</button>
    </li>
    <li class="nav-item active">
        <button class="btn btn-outline-success my-2 my-sm-0" onclick="showCategory(`optometrie`)" type="submit">Optometria</button>
    </li>
    <li class="nav-item active">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Occhiali</button>
    </li>
    <li class="nav-item active">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Lenti a contatto</button>
    </li>
    <li class="nav-item active">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Documenti</button>
    </li>
    <li class="nav-item active">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Allegati</button>
    </li>
    ';

    // public function get(LeadModel $cliente, $optometrie){
    public function get($cliente, $optometrie){
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);

        echo $twig->render('clienti/cliente.html.twig', [
            'cliente' => $cliente,
            'optometrie' => $optometrie,
            'id_cliente' => $_GET['id'],
            'dynamicNavbar' => self::$navbarContent,
        ]);
    }
    
    public function delete(LeadModel $cliente, $id)
    {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);

        echo $twig->render('clienti/delete.html.twig', [
            'action' => '/elimina_cliente?id=' . $id,
            'nome' => $cliente->nome,
            'cognome' => $cliente->cognome,
        ]);
    }
}
