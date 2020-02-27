<?php

defined('_JEXEC') or die;

$controller = JControllerLegacy::getInstance('Managements');
$controller->execute(JRequest::getVar('task', 'click'));
$controller->redirect();
