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
                am.id_consultants AS consultants,
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
    $consultants = $expense->consultants;

    $date_in = ' - ';
    if (!empty($expense->date_in) && $expense->date_in != '0000-00-00') {
        $date_in = explode('-', $expense->date_in);
        $date_in = $date_in[2] . '/' . $date_in[1] . '/' . $date_in[0];
    }

    $date_out = ' - ';
    if (!empty($expense->date_out) && $expense->date_out != '0000-00-00') {
        $date_out = explode('-', $expense->date_out);
        $date_out = $date_out[2] . '/' . $date_out[1] . '/' . $date_out[0];
    }

    $car = [];
    if (!empty($expense->mark)) {
        array_push($car, $expense->mark);
    }
    if (!empty($expense->mark)) {
        array_push($car, $expense->model);
    }
    if (!empty($expense->mark)) {
        array_push($car, $expense->plate);
    }

    $thisCar = 'Sem Carro';
    if (!empty($car)) {
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
foreach ($clients as $client) {
    array_push($clientes, $client->name);
}

$thisClient = '';
if (!empty($clientes)) {
    $thisClient = implode(', ', $clientes);
}

//Acompanhantes
$queryConsultant = $db->getQuery(true);
$queryConsultant->select('*');
$queryConsultant->from('#__users AS usr');
$queryConsultant->where('usr.id IN (' . $consultants . ')');
$db->setQuery($queryConsultant);
$db->query();
$consultants = (array)$db->loadObjectList();
$consultantss = [];
foreach ($consultants as $consultants1) {
    array_push($consultantss, $consultants1->name);
}

$thisConsultants = '';
if (!empty($consultantss)) {
    $thisConsultants = implode(', ', $consultantss);
}



$id_num = str_pad($id, 10, '0', STR_PAD_LEFT);

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
$pdf->Multicell(27, 6, utf8_decode('nº ' . $id_num), 1, 'L');
$pdf->SetFont('Helvetica', 'B', 15);
$pdf->Ln(10);
$pdf->Multicell(190, 15, 'RELATÓRIO DE DESPESAS DE VIAGEM', 0, 'C');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(190, 7, utf8_decode('Consultor: ' . $consultant), 0, 1, 'L');
$pdf->Cell(190, 7, utf8_decode('E-mail: ' . $email), 0, 1, 'L');
$pdf->Ln(5);
$pdf->Multicell(190, 5, utf8_decode('Cliente(s): ' . $thisClient), 0, 'L');
$pdf->Ln(2);
$pdf->Cell(95, 7, utf8_decode('Adiantamento: R$ ' . number_format($cash, 2, ',', '.')), 0, 0, 'L');
$pdf->Cell(95, 7, utf8_decode('Carro: ' . $thisCar), 0, 1, 'L');
$pdf->Cell(95, 7, utf8_decode('Data de Ida: ' . $date_in), 0, 0, 'L');
$pdf->Cell(95, 7, utf8_decode('Data de Volta: ' . $date_out), 0, 1, 'L');
$pdf->Ln();
$pdf->Multicell(190, 5, utf8_decode('Acompanhante(s): ' . $thisConsultants), 0, 'L');
$pdf->Ln(2);

$queryExpenses = $db->getQuery(true);
$queryExpenses->select('ae.*, ce.descricao AS category, cp.descricao AS payment');
$queryExpenses->from('#__accountability_expenses AS ae');
$queryExpenses->join('LEFT','#__cat_expenses AS ce ON ce.id = ae.cat_expenses');
$queryExpenses->join('LEFT','#__cat_payments AS cp ON cp.id = ae.cat_payments');
$queryExpenses->where('ae.id_adv_money = ' . $id);
$db->setQuery($queryExpenses);
$db->query();
$accounts = (array)$db->loadObjectList();

$pdf->Ln(5);
$pdf->SetFont('Helvetica', 'B', 10);
$pdf->Cell(20, 6, utf8_decode('Data'), 1, 0, 'C');
$pdf->Cell(20, 6, utf8_decode('Nota'), 1, 0, 'C');
$pdf->Cell(50, 6, utf8_decode('Descrição'), 1, 0, 'C');
$pdf->Cell(30, 6, utf8_decode('Categoria'), 1, 0, 'C');
$pdf->Cell(40, 6, utf8_decode('Pagamento'), 1, 0, 'C');
$pdf->Cell(30, 6, utf8_decode('Valor'), 1, 1, 'C');

$pdf->SetFont('Helvetica', '', 9);
$totalCash = 0;
foreach ($accounts as $account) {
    $expense_date = ' - ';
    if (!empty($account->expense_date) && $account->expense_date != '0000-00-00') {
        $expense_date = explode('-', $account->expense_date);
        $expense_date = $expense_date[2] . '/' . $expense_date[1] . '/' . $expense_date[0];
    }

    $note = ' - ';
    if(!empty($account->note)){
        $note = substr($account->note, 0, 15);
    }
    $description = substr($account->description, 0, 50);


    $totalCash += $account->cash;

    $pdf->Cell(20, 6, $expense_date, 1, 0, 'C');
    $pdf->Cell(20, 6, utf8_decode($note), 1, 0, 'C');
    $pdf->Cell(50, 6, utf8_decode($description), 1, 0, 'L');
    $pdf->Cell(30, 6, utf8_decode($account->category), 1, 0, 'C');
    $pdf->Cell(40, 6, utf8_decode($account->payment), 1, 0, 'C');
    $pdf->Cell(30, 6, utf8_decode('R$ '.number_format($account->cash, 2, ',', '.')), 1, 1, 'C');
}

$pdf->SetFont('Helvetica', 'B', 11);
$pdf->Cell(190, 8, utf8_decode('ADIANTAMENTO   R$ '.number_format($cash, 2, ',', '.')), 1, 1, 'R');
$pdf->Cell(190, 8, utf8_decode('TOTAL DESPESAS  R$ '.number_format($totalCash, 2, ',', '.')), 1, 1, 'R');
$total = $cash - $totalCash;
$pdf->Cell(190, 8, utf8_decode('TOTAL   R$ '.number_format($total, 2, ',', '.')), 1, 1, 'R');
$pdf->Ln(15);
$pdf->SetFont('Helvetica', 'B', 10);
$pdf->Multicell(190, 6, 'Belo Horizonte, ' . date("d/m/Y") , 0, 'C');
$pdf->Ln(10);
$pdf->Cell(90, 5, '___________________________________________', 0, 0, 'C');
$pdf->Cell(10, 5, '', 0, 'C');
$pdf->Cell(90, 5, '___________________________________________', 0, 1, 'C');
$pdf->Cell(90, 3, $consultant, 0, 0, 'C');
$pdf->Cell(10, 3, '', 0, 'C');
$pdf->Cell(90, 3, 'CIGMA - Consultoria', 0, 1, 'C');



ob_start();

$pdf->Output('despesas_cigma.pdf', 'I');

?>
