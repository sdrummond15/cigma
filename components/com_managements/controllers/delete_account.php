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

$id = $_POST['id'];
$expense = $_POST['expense'];

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$conditions = array(
    $db->quoteName('id') . ' = ' . $id
);
$query->delete($db->quoteName('#__accountability_expenses'));
$query->where($conditions);
$db->setQuery($query);
$result = $db->execute();

$db = JFactory::getDbo();
$queryCash = $db->getQuery(true);
$queryCash->select('SUM(cash) AS cash');
$queryCash->from('#__accountability_expenses AS ae');
$queryCash->where('ae.id_adv_money = ' . $expense);
$db->setQuery($queryCash);
$db->query();
$cash = (array)$db->loadObjectList();

echo 'R$ ' .number_format($cash[0]->cash, 2, ',', '.');
exit;
