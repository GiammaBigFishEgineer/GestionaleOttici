<?php

/*
PRESCRIZIONE
    sfero -35 a +35 compresi due decimali dopo la virgola
    clind quanto vuoi con due decimali dopo la virgola
    ax in gradi e va da 0 na 180 (non decimali)
    prisma quanto vuoi decimale
    base quello che vuoi decimale
    addiz 0 10
    visus da 0 a 20 in rapporto su /10
 
AUTO REFRATTOMETRO
 sfero cilind ax e visus come prima sia sx che sinistro

TEST PRELIMINARI
 scelta destra o sinistro

*/

require_once('BaseModel.php');
require_once('LeadModel.php');

require_once(__ROOT__ . '/utils/Validation.php');

class OptometriaModel extends BaseModel
{
    public static string $nome_tabella = 'AN_Analisi_Visive';
    protected array $_fields = [
        "ID",
        "ID Cliente",
        "Data Rilevazione",
        "Data Ricetta",
        "Data Esame",
        "Definitivo L Sfero OD",
        "Definitivo MD Sfero OD",
        "Definitivo V Sfero OD",
        "Definitivo L Sfero OS",
        "Definitivo MD Sfero OS",
        "Definitivo V Sfero OS",
        "Definitivo L Cilindro OD",
        "Definitivo MD Cilindro OD",
        "Definitivo V Cilindro OD",
        "Definitivo L Cilindro OS",
        "Definitivo MD Cilindro OS",
        "Definitivo V Cilindro OS",
        "Definitivo L Asse OD",
        "Definitivo MD Asse OD",
        "Definitivo V Asse OD",
        "Definitivo L Asse OS",
        "Definitivo MD Asse OS",
        "Definitivo V Asse OS",
        "Definitivo L Diottrie Prismatiche OD",
        "Definitivo MD Diottrie Prismatiche OD",
        "Definitivo V Diottrie Prismatiche OD",
        "Definitivo L Diottrie Prismatiche OS",
        "Definitivo MD Diottrie Prismatiche OS",
        "Definitivo V Diottrie Prismatiche OS",
        "Definitivo L Posizione del Prisma OD",
        "Definitivo MD Posizione del Prisma OD",
        "Definitivo V Posizione del Prisma OD",
        "Definitivo L Posizione del Prisma OS",
        "Definitivo MD Posizione del Prisma OS",
        "Definitivo V Posizione del Prisma OS",
        "Definitivo L Addizione OD",
        "Definitivo MD Addizione OD",
        "Definitivo V Addizione OD",
        "Definitivo L Addizione OS",
        "Definitivo MD Addizione OS",
        "Definitivo V Addizione OS",
        "Definitivo L Visus OD",
        "Definitivo MD Visus OD",
        "Definitivo V Visus OD",
        "Definitivo L Visus OS",
        "Definitivo MD Visus OS",
        "Definitivo V Visus OS",
        "Definitivo L Distanza Interpupillare OD",
        "Definitivo L Distanza Interpupillare OS",
        "Definitivo MD Distanza Interpupillare OD",
        "Definitivo MD Distanza Interpupillare OS",
        "Definitivo V Distanza Interpupillare OD",
        "Definitivo V Distanza Interpupillare OS",
        "Definitivo L Distanza Interpupillare OO",
        "Definitivo MD Distanza Interpupillare OO",
        "Definitivo V Distanza Interpupillare OO",
        //Cheratometrie
        "K Raggio 1 OD",
        "K Raggio 2 OD",
        "K Raggio 1 OS",
        "K Raggio 2 OS",
        "K Diottrie 1 OD",
        "K Diottrie 2 OD",
        "K Diottrie 1 OS",
        "K Diottrie 2 OS",
        "K Meridiano 1 OD",
        "K Meridiano 2 OD",
        "K Meridiano 1 OS",
        "K Meridiano 2 OS",
        "K Astigmatico Corneale OD",
        "K Astigmatico Corneale OS",
        "Note Cheratometria",
        //Test Preliminari
        "Occhio Dominante Lontano",
        "Occhio Dominante Vicino",
        "Forie L Nat",
        "Forie V Nat",
        "Forie L Corr",
        "Forie V Corr",
        "Visus Dx Nat",
        "Visus Dx Corr",
        "Visus Bin Nat",
        "Visus Bin Corr",
        "Visus Sx Nat",
        "Visus Sx Corr",
        //Autorefrattometro
        "Autorefrattometro Sfero OD",
        "Autorefrattometro Sfero OS",
        "Autorefrattometro Cilindro OD",
        "Autorefrattometro Cilindro OS",
        "Autorefrattometro Asse OD",
        "Autorefrattometro Asse OS",
        "Autorefrattometro Visus OD",
        "Autorefrattometro Visus OS"
    ];

    public function generatePDF(){
        //Per UTF-8 controllare http://www.fpdf.org/en/script/script92.php
        $pdf = new \Mpdf\Mpdf();
        $pdf->AddPage();
        
        // $pdf->simpleTables = false;
        //  Get PDF dimensions
        $pdf->WriteHTML("
        <div style='align:right; text-align:right;'>
            <h1>Scheda Optometrica</h1>
            del {$this->{'Data Ricetta'}}
        </div>

        Distanza Interpupillare:
        <table style='border: 1;'>
            <thead>
                <tr style='border: 1'>
                    <th scope='col'>Distanza</th>
                    <th scope='col'>Destro</th>
                    <th scope='col'>Sinistro</th>
                    <th scope='col'>Totale</th>
                </tr>
            </thead>
        <tbody>
            <tr style='border: 1'>
                <th scope='row'>Lontano</th>
                <td>{$this->{'Definitivo L Distanza Interpupillare OD'}}</td>
                <td>{$this->{'Definitivo L Distanza Interpupillare OS'}}</td>
                <td>{$this->{'Definitivo L Distanza Interpupillare OO'}}</td>
            </tr>
            <tr style='border: 1'>
                <th scope='row'>Medio</th>
                <td>{$this->{'Definitivo MD Distanza Interpupillare OD'}}</td>
                <td>{$this->{'Definitivo MD Distanza Interpupillare OS'}}</td>
                <td>{$this->{'Definitivo MD Distanza Interpupillare OO'}}</td>
            </tr>
            <tr style='border: 1'>
                <th scope='row'>Vicino</th>
                <td>{$this->{'Definitivo V Distanza Interpupillare OD'}}</td>
                <td>{$this->{'Definitivo V Distanza Interpupillare OS'}}</td>
                <td>{$this->{'Definitivo V Distanza Interpupillare OO'}}</td>
            </tr>
        </tbody>
    </table>
    <br>
    
    Occhio DX:
    <table class='table' style='border:1 '>
		<thead>
		<tr>
			<th scope='col'>Distanza</th>
			<th scope='col'>Sfero</th>
			<th scope='col'>Cilind</th>
			<th scope='col'>Ax</th>
			<th scope='col'>Prisma</th>
			<th scope='col'>Base</th>
			<th scope='col'>Addiz</th>
			<th scope='col'>Visus</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>Lontano</td>
			<td>{$this->{'Definitivo L Sfero OD'}}</td>
			<td>{$this->{'Definitivo L Cilindro OD'}}</td>
			<td>{$this->{'Definitivo L Asse OD'}}</td>
			<td>{$this->{'Definitivo L Diottrie Prismatiche OD'}}</td>
			<td>{$this->{'Definitivo L Posizione del Prisma OD'}}</td>
			<td>{$this->{'Definitivo L Addizione OD'}}</td>
			<td>{$this->{'Definitivo L Visus OD'}}</td>
		</tr>
		<tr>
			<td>Medio</td>
			<td>{$this->{'Definitivo MD Sfero OD'}}</td>
			<td>{$this->{'Definitivo MD Cilindro OD'}}</td>
			<td>{$this->{'Definitivo MD Asse OD'}}</td>
			<td>{$this->{'Definitivo MD Diottrie Prismatiche OD'}}</td>
			<td>{$this->{'Definitivo MD Posizione del Prisma OD'}}</td>
			<td>{$this->{'Definitivo MD Addizione OD'}}</td>
			<td>{$this->{'Definitivo MD Visus OD'}}</td>
		</tr>
		<tr>
			<td>Vicino</td>
			<td>{$this->{'Definitivo V Sfero OD'}}</td>
			<td>{$this->{'Definitivo V Cilindro OD'}}</td>
			<td>{$this->{'Definitivo V Asse OD'}}</td>
			<td>{$this->{'Definitivo V Diottrie Prismatiche OD'}}</td>
			<td>{$this->{'Definitivo V Posizione del Prisma OD'}}</td>
			<td>{$this->{'Definitivo V Addizione OD'}}</td>
			<td>{$this->{'Definitivo V Visus OD'}}</td>
		</tr>
		</tbody>
	</table>
    <br>
        
    Occhio SX:
    <table class='table' style='border:1 '>
		<thead>
		<tr>
			<th scope='col'>Distanza</th>
			<th scope='col'>Sfero</th>
			<th scope='col'>Cilind</th>
			<th scope='col'>Ax</th>
			<th scope='col'>Prisma</th>
			<th scope='col'>Base</th>
			<th scope='col'>Addiz</th>
			<th scope='col'>Visus</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>Lontano</td>
			<td>{$this->{'Definitivo L Sfero OS'}}</td>
			<td>{$this->{'Definitivo L Cilindro OS'}}</td>
			<td>{$this->{'Definitivo L Asse OS'}}</td>
			<td>{$this->{'Definitivo L Diottrie Prismatiche OS'}}</td>
			<td>{$this->{'Definitivo L Posizione del Prisma OS'}}</td>
			<td>{$this->{'Definitivo L Addizione OS'}}</td>
			<td>{$this->{'Definitivo L Visus OS'}}</td>
		</tr>
		<tr>
			<td>Medio</td>
			<td>{$this->{'Definitivo MD Sfero OS'}}</td>
			<td>{$this->{'Definitivo MD Cilindro OS'}}</td>
			<td>{$this->{'Definitivo MD Asse OS'}}</td>
			<td>{$this->{'Definitivo MD Diottrie Prismatiche OS'}}</td>
			<td>{$this->{'Definitivo MD Posizione del Prisma OS'}}</td>
			<td>{$this->{'Definitivo MD Addizione OS'}}</td>
			<td>{$this->{'Definitivo MD Visus OS'}}</td>
		</tr>
		<tr>
			<td>Vicino</td>
			<td>{$this->{'Definitivo V Sfero OS'}}</td>
			<td>{$this->{'Definitivo V Cilindro OS'}}</td>
			<td>{$this->{'Definitivo V Asse OS'}}</td>
			<td>{$this->{'Definitivo V Diottrie Prismatiche OS'}}</td>
			<td>{$this->{'Definitivo V Posizione del Prisma OS'}}</td>
			<td>{$this->{'Definitivo V Addizione OS'}}</td>
			<td>{$this->{'Definitivo V Visus OS'}}</td>
		</tr>
		</tbody>
	</table>
    <br>

    <table class='table' style='border: 1'>
        <thead>
        <tr>
            <td></td>
            <td scope='col'>Visus DX</td>
            <td scope='col'>Binoc.</td>
            <td scope='col'>Visus SX</td>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>Naturale</td>
                <td>{$this->{'Visus Dx Nat'}}</td>
                <td>{$this->{'Visus Bin Nat'}}</td>
                <td>{$this->{'Visus Sx Nat'}}</td>
            </tr>
            <tr>
                <td>Corretto</td>
                <td>{$this->{'Visus Dx Corr'}}</td>
                <td>{$this->{'Visus Bin Corr'}}</td>
                <td>{$this->{'Visus Sx Corr'}}</td>
            </tr>
        </tbody>
    </table>
        ");


        $directory = __ROOT__ . "/user_data/pdf/". OptometriaModel::$nome_tabella . "/";
        $filename = $this->ID. ".pdf";

        if(!is_dir($directory)){
            mkdir($directory, recursive: true);
        }

        $pdf->Output($directory . $filename, \Mpdf\Output\Destination::FILE);
    }


    /* TEST PRELIMINARI */

    // public function validate(): bool
    // {
    //     $val = new Validation();

    //     $val->name('Definitivo L Sfero OD')->value((float) $this->{'Definitivo L Sfero OD'})->min(-35)->max(35);
    //     $val->name('Definitivo MD Sfero OD')->value((float) $this->{'Definitivo MD Sfero OD'})->min(-35)->max(35);
    //     $val->name('Definitivo V Sfero OD')->value((float) $this->{'Definitivo V Sfero OD'})->min(-35)->max(35);
    //     $val->name('Definitivo L Sfero OS')->value((float) $this->{'Definitivo L Sfero OS'})->min(-35)->max(35);
    //     $val->name('Definitivo MD Sfero OS')->value((float) $this->{'Definitivo MD Sfero OS'})->min(-35)->max(35);
    //     $val->name('Definitivo V Sfero OS')->value((float) $this->{'Definitivo V Sfero OS'})->min(-35)->max(35);
    //     // $val->name('sfero_dx_ref')->value((float) $this->sfero_dx_ref)->min(-35)->max(35);
    //     // $val->name('sfero_sx_ref')->value((float) $this->sfero_sx_ref)->min(-35)->max(35);

    //     $val->name('Definitivo L Asse OD')->value((int) $this->{'Definitivo L Asse OD'})->min(0)->max(180);
    //     $val->name('Definitivo MD Asse OD')->value((int) $this->{'Definitivo MD Asse OD'})->min(0)->max(180);
    //     $val->name('Definitivo V Asse OD')->value((int) $this->{'Definitivo V Asse OD'})->min(0)->max(180);
    //     $val->name('Definitivo L Asse OS')->value((int) $this->{'Definitivo L Asse OS'})->min(0)->max(180);
    //     $val->name('Definitivo MD Asse OS')->value((int) $this->{'Definitivo MD Asse OS'})->min(0)->max(180);
    //     $val->name('Definitivo V Asse OS')->value((int) $this->{'Definitivo V Asse OS'})->min(0)->max(180);
    //     // $val->name('ax_dx_ref')->value((int) $this->ax_dx_ref)->min(0)->max(180);
    //     // $val->name('ax_sx_ref')->value((int) $this->ax_sx_ref)->min(0)->max(180);

    //     $val->name('Definitivo L Addizione OD')->value((int) $this->{'Definitivo L Addizione OD'})->min(0)->max(10);
    //     $val->name('Definitivo MD Addizione OD')->value((int) $this->{'Definitivo MD Addizione OD'})->min(0)->max(10);
    //     $val->name('Definitivo V Addizione OD')->value((int) $this->{'Definitivo V Addizione OD'})->min(0)->max(10);
    //     $val->name('Definitivo L Addizione OS')->value((int) $this->{'Definitivo L Addizione OS'})->min(0)->max(10);
    //     $val->name('Definitivo MD Addizione OS')->value((int) $this->{'Definitivo MD Addizione OS'})->min(0)->max(10);
    //     $val->name('Definitivo V Addizione OS')->value((int) $this->{'Definitivo V Addizione OS'})->min(0)->max(10);

    //     $val->name('Definitivo L Visus OD')->value((int) $this->{'Definitivo L Visus OD'})->min(0)->max(20);
    //     $val->name('Definitivo MD Visus OD')->value((int) $this->{'Definitivo MD Visus OD'})->min(0)->max(20);
    //     $val->name('Definitivo V Visus OD')->value((int) $this->{'Definitivo V Visus OD'})->min(0)->max(20);
    //     $val->name('Definitivo L Visus OS')->value((int) $this->{'Definitivo L Visus OS'})->min(0)->max(20);
    //     $val->name('Definitivo MD Visus OS')->value((int) $this->{'Definitivo MD Visus OS'})->min(0)->max(20);
    //     $val->name('Definitivo V Visus OS')->value((int) $this->{'Definitivo V Visus OS'})->min(0)->max(20);
    //     // $val->name('visus_dx_ref')->value((int)$this->visus_dx_ref)->min(0)->max(20);
    //     // $val->name('visus_sx_ref')->value((int)$this->visus_sx_ref)->min(0)->max(20);

    //     $val->name('Definitivo L Distanza Interpupillare OD')->value((float) $this->{'Definitivo L Distanza Interpupillare OD'});
    //     $val->name('Definitivo L Distanza Interpupillare OS')->value((float) $this->{'Definitivo L Distanza Interpupillare OS'});
    //     $val->name('Definitivo L Distanza Interpupillare OO')->value((float) $this->{'Definitivo L Distanza Interpupillare OO'});

    //     $val->name('Definitivo MD Distanza Interpupillare OD')->value((float) $this->{'Definitivo MD Distanza Interpupillare OD'});
    //     $val->name('Definitivo MD Distanza Interpupillare OS')->value((float) $this->{'Definitivo MD Distanza Interpupillare OS'});
    //     $val->name('Definitivo MD Distanza Interpupillare OO')->value((float) $this->{'Definitivo MD Distanza Interpupillare OO'});

    //     $val->name('Definitivo V Distanza Interpupillare OD')->value((float) $this->{'Definitivo V Distanza Interpupillare OD'});
    //     $val->name('Definitivo V Distanza Interpupillare OS')->value((float) $this->{'Definitivo V Distanza Interpupillare OS'});
    //     $val->name('Definitivo V Distanza Interpupillare OO')->value((float) $this->{'Definitivo V Distanza Interpupillare OO'});

    //     //Cheratometrie
    //     $val->name('K Raggio 1 OD');
    //     $val->name('K Raggio 1 OS');
    //     $val->name('K Raggio 2 OD');
    //     $val->name('K Raggio 2 OS');
    //     $val->name('K Diottrie 1 OD');
    //     $val->name('K Diottrie 2 OD');
    //     $val->name('K Diottrie 1 OS');
    //     $val->name('K Diottrie 2 OS');
    //     $val->name('K Meridiano 1 OD');
    //     $val->name('K Meridiano 2 OD');
    //     $val->name('K Meridiano 1 OS');
    //     $val->name('K Meridiano 2 OS');
    //     $val->name('K Astigmatico Corneale OD');
    //     $val->name('K Astigmatico Corneale OS');
    //     $val->name('Note Cheratometria');

    //     //Test Preliminari

    //     $val->name('Occhio Dominante Lontano');
    //     $val->name('Occhio Dominante Vicino');
    //     $val->name('Forie L Nat');
    //     $val->name('Forie V Nat');
    //     $val->name('Forie L Corr');
    //     $val->name('Forie V Corr');
    //     $val->name('Visus Dx Nat');
    //     $val->name('Visus Dx Corr');
    //     $val->name('Visus Bin Nat');
    //     $val->name('Visus Bin Corr');
    //     $val->name('Visus Sx Nat');
    //     $val->name('Visus Sx Corr');

    //     $this->errors = $val->getErrors();
    //     return $val->isSuccess();
    // }

    /* RELATIONSHIPS */
    private ?LeadModel $cliente = null;

    public function getCliente(): ?LeadModel
    {
        if (!$this->cliente && $this->{'ID Cliente'})
            $this->cliente = LeadModel::get($this->{'ID Cliente'});
        
        return $this->cliente;
    }
}
