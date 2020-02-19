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
require_once('..' . DS . 'models' . DS . 'fields' . DS . 'owners.php');

// Creating an app instance
$app = JFactory::getApplication('site');
$app->initialise();

jimport('joomla.user.user');
jimport('joomla.user.helper');

$idUser = $_POST['id_user'];
$name = $_POST['name'];
$id = $_POST['id'];

//Get form details
$db = JFactory::getDBO();
$query = "SELECT id, name FROM #__owners WHERE created_by = $idUser ORDER BY name";
$db->setQuery($query);
$owners = $db->loadObjectList();


$var_list = '';
$return = 'Nenhum Proprietário/Usufrutário Cadastrado!';


if (!empty($owners)) {
    foreach ($owners as $owner) {

        $checked = '';
        if (!empty($owner->id)) {
            $db = JFactory::getDBO();
            $query = "SELECT owner_id FROM #__enterprises WHERE id = $id";
            $db->setQuery($query);
            $enterprises_owners = $db->loadObjectList();

            if (!empty($enterprises_owners)) {
                $array_owner = explode(', ', $enterprises_owners[0]->owner_id);
                $owner_id = (int)$owner->id;
            }


            if (!empty($array_owner)) {
                foreach ($array_owner as $enterprises_owner) {
                    if ($enterprises_owner == $owner->id) {
                        $checked = 'checked';
                    }
                }
            }
        }


        $var_list .= '<label for="jform_owner_id' . $owner->id . '" class="checkbox"><input name="' . $name . '" type="checkbox" value="' . $owner->id . '" id="jform_owner_id' . $owner->id . '" ' . $checked . '>' . $owner->name . '</label>';
    }
    $return = $var_list;
}

echo $return;

exit;