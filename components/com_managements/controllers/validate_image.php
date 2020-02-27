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

$image = JRequest::get('image');

//    $tamanhoImg = getimagesize($image['tmp_name']);
//    $tamanhoImgX = $tamanhoImg[0];
//    $tamanhoImgY = $tamanhoImg[1];
//
//    $mimeImg = explode('/', $image['type']);

//    if($mimeImg[0] == 'image' && !empty($tamanhoImgX) && !empty($tamanhoImgY)){
//        return true;
//    }else{
//        return false;
//    }

print_r($_FILES[$image]);
exit;