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

// Creating an app instance
$app = JFactory::getApplication('site');
$app->initialise();

jimport('joomla.user.user');
jimport('joomla.user.helper');

$id_state = $_POST['id'];

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*');
$query->from('#__city');
if(!empty($id_state)){
    $query->where('id_state = ' . $id_state);
}
$query->order('description ASC');
$db->setQuery($query);
$items = $db->loadObjectList();
$option = array();
foreach ($items as $key => $item) {
    $option[] = "<option value='{$item->id}'>{$item->description}</option>";
}
$return = implode("", $option);
echo $return;
exit;