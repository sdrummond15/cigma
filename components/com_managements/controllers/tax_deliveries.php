<?php
define('_JEXEC', 1);

// defining the base path.
if (stristr($_SERVER['SERVER_SOFTWARE'], 'win32')) {
    define('JPATH_BASE', realpath(dirname(__FILE__) . '\..\..\..'));
} else define('JPATH_BASE', realpath(dirname(__FILE__) . '/../../..'));
define('DS', DIRECTORY_SEPARATOR);


// including the main joomla files
require_once(JPATH_BASE . DS . 'includes' . DS . 'defines.php');
require_once(JPATH_BASE . DS . 'includes' . DS . 'framework.php');

$path = JPATH_ROOT;

// Creating an app instance
$app = JFactory::getApplication('site');
$app->initialise();

jimport('joomla.user.user');
jimport('joomla.user.helper');

$baseRouter = JUri::base();

$tasks = (isset($_POST['tasks'])) ? implode(",", $_POST['tasks']) : '';
$clients = (isset($_POST['clients'])) ? implode(",", $_POST['clients']) : '';
$consultant = (isset($_POST['consultant'])) ? $_POST['consultant'] : '';
$date_in = (isset($_POST['date_in'])) ?  $_POST['date_in'] : '';
$date_out = (isset($_POST['date_out'])) ?  $_POST['date_out'] : '';
$observation = (isset($_POST['observation'])) ? $_POST['observation'] : '';
$order = (isset($_POST['order'])) ? $_POST['order'] : '';
$img = (isset($_POST['img'])) ? $_POST['img'] : '';

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('
    td.id AS id,
    td.id_client AS id_client, 
    td.observation AS observation,
    td.date_delivery AS date_delivery,
    t.task AS task_name,
    u.name AS consultant,
    c.name AS client
');
$query->from('#__tax_deliveries AS td');
$query->join('LEFT', '#__tasks AS t ON td.id_task = t.id');
$query->join('LEFT', '#__users AS u ON td.id_consultant = u.id');
$query->join('LEFT', '#__clients AS c ON td.id_client = c.id');

if (!empty($tasks)) {
    $query->where('td.id_task' . ' IN (' . $tasks . ')');
}

if (!empty($consultant)) {
    $query->where('td.id_consultant = ' . $consultant);
}

if (intval($observation) == 0) {
    $query->where('td.observation = ""');
} else if (intval($observation) == 1) {
    $query->where('td.observation <> ""');
}

if (!empty($date_in) && empty($date_out)) {
    $query->where('td.date_delivery >= \'' . inverteData($date_in) . '\'');
} elseif (empty($date_in) && !empty($date_out)) {
    $query->where('td.date_delivery <= \'' . inverteData($date_out) . '\'');
} elseif (!empty($date_in) && !empty($date_out)) {
    $query->where('(td.date_delivery >= \'' . inverteData($date_in) . '\' AND td.date_delivery <= \'' . inverteData($date_out) . '\')');
}

if (!empty($order)) {
    $query->order('t.task ' . $order);
}

$db->setQuery($query);
$results = $db->loadObjectList();

if (!empty($clients)) {
    $clients = explode(',', $clients);
}

$listDeliveries = array();
if (!empty($results) && !empty($clients)) {
    foreach ($results as $r) {
        if (in_array($r->id_client, $clients)) {
            array_push($listDeliveries, $r);
        }
    }
} else {
    $listDeliveries = $results;
}

if (!empty($listDeliveries)) {

    $ids = implode(',', array_column($listDeliveries, 'id'));
    $ids = urlencode(base64_encode($ids));

    $form = '
    <div id="relatorio">
    <h2>Relatório</h2>
    <table class="table table-striped" id="list-deliveries">
        <thead>
            <tr>
                <th>Tarefa</th>
                <th>Consultor</th>
                <th>Observação</th>
                <th>Entrega</th>
                <th>Cliente(s)</th>
            </tr>
        </thead>
        <tbody>';
    foreach ($listDeliveries as $l) :
        $date_delivery = ($l->date_delivery <> "0000-00-00") ? mostrarData($l->date_delivery) : "--";
        $form .= '<tr>
                    <td>' . $l->task_name . '</td>
                    <td>' . $l->consultant . '</td>
                    <td class="center">' . $l->observation . '</td>
                    <td class="center">' . $date_delivery . '</td>
                    <td>' . $l->client . '</td>
                </tr>';
    endforeach;
    $form .= '</tbody>
    </table>
    </div>
    <div><button type="button" class="btn btn-danger btn-pdf right"><span class="icon-download" aria-hidden="true"></span>Gerar PDF</button></div>';

    $array_result = array('form' => $form, 'img' => $img);

    echo json_encode($array_result);
}
exit;

function inverteData($data)
{
    $data = explode('/', $data);
    return $data[2] . '-' . $data[1] . '-' . $data[0];
}

function mostrarData($data)
{
    $data = explode('-', $data);
    return $data[2] . '/' . $data[1] . '/' . $data[0];
}
