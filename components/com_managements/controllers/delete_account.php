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

$id = JRequest::getVar('id');
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$conditions = array(
    $db->quoteName('id') . ' = ' . $id
);
$query->delete($db->quoteName('#__accountability_expenses'));
$query->where($conditions);
$db->setQuery($query);
$result = $db->execute();
echo true;
exit;
