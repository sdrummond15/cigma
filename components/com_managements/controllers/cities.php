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

$json_file = file_get_contents('../assets/js/estados-cidades.json');
$json_str = json_decode($json_file, true);

$id_state = $_POST['id'];

$options = '';

foreach ($json_str['estados'] as $estado) {

    if ($estado['sigla'] == $id_state) {
        $options .= '<option value="">Selecione a cidade</option>';
        foreach ($estado['cidades'] as $cidades) {
            $options .= "<option value='" . $cidades . "' >" . $cidades . "</option>";
        }
    }

}

echo $options;

exit;