<?php
define('FPDF_FONTPATH', 'font/');
require('write.php');

define('_JEXEC', 1);

// defining the base path.
if (stristr($_SERVER['SERVER_SOFTWARE'], 'win32')) {
    define('JPATH_BASE', realpath(dirname(__FILE__) . '\..\..\..\..\..'));
} else define('JPATH_BASE', realpath(dirname(__FILE__) . '/../../../../..'));
define('DS', DIRECTORY_SEPARATOR);

// including the main joomla files
require_once(JPATH_BASE . DS . 'includes' . DS . 'defines.php');
require_once(JPATH_BASE . DS . 'includes' . DS . 'framework.php');

// Creating an app instance
$app = JFactory::getApplication('site');

$app->initialise();
jimport('joomla.user.user');
jimport('joomla.user.helper');

if (!empty(JRequest::getInt("id"))) {
    $id = JRequest::getInt("id");
}

//Dados adicionais do Contrato
$db = JFactory::getDBO();
$query = $db->getQuery(true);
$query->select('am.cash AS cash,
                am.id_client AS client,
                am.date_in AS date_in,
                am.date_out AS date_out,
                c.mark AS mark,
                c.model AS model,
                c.plate AS plate,
                u.name AS consultant,
                u.email AS email');
$query->from('#__advanced_money AS am');
$query->join('LEFT', '#__users AS u ON u.id = am.id_consultant');
$query->join('LEFT', '#__cars AS c ON c.id = am.id_car');
$query->where('am.id = ' . $id);

$db->setQuery($query);
$db->query();
$expenses = (array)$db->loadObjectList();

foreach ($expenses as $expense) {
    $cash = $expense->cash;
    $client = $expense->client;
    $date_in = $expense->date_in;
    $date_out = $expense->date_out;
    $car = [];
    if(!empty($expense->mark)){
        array_push($car, $expense->mark);
    }
    if(!empty($expense->mark)) {
        array_push($car, $expense->model);
    }
    if(!empty($expense->mark)) {
        array_push($car, $expense->plate);
    }

    $thisCar = 'Sem Carro';
    if(!empty($car)){
        $thisCar = implode(' - ', $car);
    }

    $consultant = $expense->consultant;
    $email = $expense->email;
}

$queryClient = $db->getQuery(true);
$queryClient->select('*');
$queryClient->from('#__clients AS c');
$queryClient->where('c.id IN (' . $client . ')');
$db->setQuery($queryClient);
$db->query();
$clients = (array)$db->loadObjectList();
$clientes = [];
foreach ($clients as $client){
    array_push($clientes, $client->name);
}

$thisClient = '';
if(!empty($clientes)){
    $thisClient = implode(', ', $clientes);
}

$id = str_pad($id, 10, '0', STR_PAD_LEFT);

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

//INICIA PDF
$pdf = new PDF();
$pdf->SetTitle('Despesa');
$pdf->AddPage('P', 'A4');
$pdf->Image(JPATH_BASE . DS . 'images/cigma_consultoria_logo.png', 70, 10, 60, 12);
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(1, 10);
$pdf->SetFont('Helvetica', 'B', 10);
$pdf->SetX(173);
$pdf->Multicell(27, 6, utf8_decode('nº ' . $id ), 1, 'L');
$pdf->SetFont('Helvetica', 'B', 15);
$pdf->Ln(10);
$pdf->Multicell(190, 15, 'DESPESAS', 1, 'C');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(190, 7, utf8_decode('Consultor: ' . $consultant), 1, 1, 'L');
$pdf->Cell(190, 7, utf8_decode('E-mail: ' . $email), 1, 1, 'L');
$pdf->Ln(2);
$pdf->Multicell(190, 5, utf8_decode('Cliente(s): ' . $thisClient), 1, 'L');
//$pdf->Cell(276, 5, 'RLO 75348681/2019 v�lida at� 21/08/2023, 39066558/2018 V�lida at� 26/11/2022 e IBAMA 7186489 / 6284774 ', 0, 1, 'C');
//$pdf->Cell(276, 5, 'Rua Geraldino Jos� dos Santos, 1701 - Maravilhas, S�o Jos� da Lapa - MG, 35350-000,', 0, 1, 'C');
//$pdf->Cell(276, 5, 'confere o certificado que prestou servi�o de tratamento de res�duo, conforme os dados abaixo', 0, 1, 'C');
//$pdf->Ln(1);
//$pdf->SetFont('Helvetica', 'I', 9);
//$pdf->Cell(276, 5, 'Obs.: os efluentes s�o tratados conforme padr�es estabelecidos pela COPAM 01/2018', 0, 1, 'C');
//
//$pdf->Ln(1);
//$pdf->SetFont('Helvetica', 'B', 9);
//$pdf->Cell(276, 5, 'DADOS DO RES�DUO', 1, 1, 'C');
//$pdf->SetFont('Helvetica', '', 9);
//$pdf->Multicell(276, 5, 'Caracteriza��o do Res�duo:' . "  " . strtoupper(utf8_decode($caracterizacao)), 1, 'L');
//$pdf->Multicell(40, 5, 'Quantidade:' . "\n" . strtoupper(utf8_decode($quantidade)), 1, 'L');
//$pdf->Ln(-10);
//$pdf->SetX(50);
//$pdf->Multicell(40, 5, 'Rer�odo de Recebimento:' . "\n" . strtoupper($data_recebimento), 1, 'L');
//$pdf->Ln(-10);
//$pdf->SetX(90);
//$pdf->Multicell(70, 5, 'Tipo de Tratamento:' . "\n" . strtoupper(utf8_decode($tipo_tratamento)), 1, 'L');
//$pdf->Ln(-10);
//$pdf->SetX(160);
//$pdf->Multicell(50, 5, 'Classe do Res�duo:' . "\n" . strtoupper($classe_residuo), 1, 'L');
//$pdf->Ln(-10);
//$pdf->SetX(210);
//$pdf->Multicell(76, 5, 'Estado(s) F�sico(s):' . "\n" . strtoupper(utf8_decode($estado_fisico)), 1, 'L');
//$pdf->Multicell(276, 5, 'Observa��o:' . strtoupper(utf8_decode($observacao)), 1, 'L');
//
//
////Identificando tipo de cliente PF ou PJ
//if (empty($type)) {
//    $descCliente = 'Nome:';
//    $documento = 'CPF:';
//} else {
//    $descCliente = 'Empresa/Raz�o Social:';
//    $documento = 'CNPJ:';
//}
//
//$endere�o = $street;
//if (!empty($number))
//    $endere�o .= ', ' . $number;
//if (!empty($complement))
//    $endere�o .= ' - ' . $complement;
//
//
//if (!empty($city)):
//    $city = explode(' - ', $city);
//    $cidade = $city[0];
//    $uf = $city[1];
//endif;
//
//$pdf->Ln(2);
//$pdf->SetFont('Helvetica', 'B', 9);
//$pdf->Cell(276, 5, 'DADOS DA GERADORA DO RES�DUO', 1, 1, 'C');
//$pdf->SetFont('Helvetica', '', 9);
//$pdf->Multicell(276, 5, $descCliente . "\n" . strtoupper(utf8_decode($cliente)), 1, 'L');
//$pdf->Multicell(138, 5, $documento . "\n" . $identification, 1, 'L');
//$pdf->Ln(-10);
//$pdf->SetX(148);
//$pdf->Multicell(138, 5, 'Contato:' . "\n" . $phone, 1, 'L');
//$pdf->Multicell(276, 5, 'Endere�o:' . "\n" . strtoupper(utf8_decode($endere�o)), 1, 'L');
//$pdf->Multicell(110, 5, 'Bairro:' . "\n" . strtoupper(utf8_decode($district)), 1, 'L');
//$pdf->Ln(-10);
//$pdf->SetX(120);
//$pdf->Multicell(110, 5, 'Cidade:' . "\n" . strtoupper(utf8_decode($cidade)), 1, 'L');
//$pdf->Ln(-10);
//$pdf->SetX(230);
//$pdf->Multicell(20, 5, 'UF:' . "\n" . strtoupper(utf8_decode($uf)), 1, 'L');
//$pdf->Ln(-10);
//$pdf->SetX(250);
//$pdf->Multicell(36, 5, 'CEP:' . "\n" . strtoupper(utf8_decode($postal_code)), 1, 'L');
//
//$transendere�o = $transstreet;
//if (!empty($transnumber))
//    $transendere�o .= ', ' . $transnumber;
//if (!empty($transcomplement))
//    $transendere�o .= ' - ' . $transcomplement;
//
//if (!empty($transcity)):
//    $transcity = explode(' - ', $transcity);
//    $transcidade = $transcity[0];
//    $transuf = $transcity[1];
//endif;
//
//$pdf->Ln(2);
//$pdf->SetFont('Helvetica', 'B', 9);
//$pdf->Cell(276, 5, 'DADOS DA TRANSPORTADORA DO RES�DUO', 1, 1, 'C');
//$pdf->SetFont('Helvetica', '', 9);
//$pdf->Multicell(276, 5, 'Raz�o Social:' . "  " . strtoupper(utf8_decode($transportadora)), 1, 'L');
//$pdf->Multicell(138, 5, 'CNPJ:' . "\n" . $transcnpj, 1, 'L');
//$pdf->Ln(-10);
//$pdf->SetX(148);
//$pdf->Multicell(138, 5, 'Contato:' . "\n" . $transphone, 1, 'L');
//$pdf->Multicell(276, 5, 'Endere�o:' . "\n" . strtoupper(utf8_decode($transendere�o)), 1, 'L');
//$pdf->Multicell(110, 5, 'Bairro:' . "\n" . strtoupper(utf8_decode($transdistrict)), 1, 'L');
//$pdf->Ln(-10);
//$pdf->SetX(120);
//$pdf->Multicell(110, 5, 'Cidade:' . "\n" . strtoupper(utf8_decode($transcidade)), 1, 'L');
//$pdf->Ln(-10);
//$pdf->SetX(230);
//$pdf->Multicell(20, 5, 'UF:' . "\n" . strtoupper(utf8_decode($transuf)), 1, 'L');
//$pdf->Ln(-10);
//$pdf->SetX(250);
//$pdf->Multicell(36, 5, 'CEP:' . "\n" . strtoupper(utf8_decode($transpostal_code)), 1, 'L');
//
//
//$pdf->Ln(5);
//$pdf->SetFont('Helvetica', 'B', 10);
//$pdf->Multicell(276, 6, 'S�O JOS� DA LAPA, ' . strtoupper($data) , 0, 'C');
//$pdf->Ln(10);
//$pdf->SetX(60);
//$pdf->Multicell(80, 5, '____________________________________', 0, 'C');
//$pdf->Ln(-5);
//$pdf->SetX(150);
//$pdf->Multicell(80, 5, '____________________________________', 0, 'C');
//$pdf->Ln(0);
//$pdf->SetX(60);
//$pdf->Multicell(80, 5, 'Odilon Vila�a Silva', 0, 'C');
//$pdf->Ln(-5);
//$pdf->SetX(150);
//$pdf->Multicell(80, 5, 'Braz Jos� de Freitas | CRQMG-03210236', 0, 'C');
//
//$pdf->Image(JPATH_SITE . '/images/assinaturabetel.png', 70, 183, 65);
//$pdf->Image(JPATH_SITE . '/images/assinaturaengenheiro.png', 160, 183, 65);

ob_start();

$pdf->Output('despesas_cigma.pdf', 'I');

?>
