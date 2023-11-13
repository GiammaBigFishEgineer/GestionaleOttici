<?php

require_once(__ROOT__ . '/models/OptometriaModel.php');
require_once(__ROOT__ . '/models/LeadModel.php');
require_once('BaseView.php');

class OptometriaView extends BaseView
{
    public function list(array $optometrie)
    {
        echo $this->twig->render('optometria/list.html.twig', [
            'optometrie' => $optometrie
        ]);
    }

    public function form(?OptometriaModel $optometria, ?LeadModel $cliente)
    {
        echo $this->twig->render('optometria/form.html.twig', [
            'action' => '/optometria',
            'optometria' => $optometria,
            'cliente' => $cliente,
        ]);
    }
}
