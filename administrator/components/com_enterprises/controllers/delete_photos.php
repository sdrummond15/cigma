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


$id = $_POST['id'];

$msg = 'success';

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*');
$query->from('#__enterprise_photos');
$query->where('id = ' . $id);
$db->setQuery($query);
$results = $db->loadObjectList();

foreach ($results as $result) {
    $photo = $result->photo;
}

if (!empty($photo)) {
    unlink(JPATH_ROOT . DS .$photo);
} else {
    $msg = 'error';
    echo $msg;
    return;
}

//Delete image
$query = $db->getQuery(true);
$conditions = array(
    $db->quoteName('id') . ' = ' . $db->quote($id)
);
$query->delete($db->quoteName('#__enterprise_photos'));
$query->where($conditions);
$db->setQuery($query);
$result = $db->execute();

echo $msg;
