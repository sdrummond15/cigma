<?php

/**
* @package     Joomla.Administrator
* @subpackage  com_managements
*/

defined('_JEXEC') or die;
JHtml::_('behavior.tabstate');

if (!JFactory::getUser()->authorise('core.manage', 'com_managements'))
{
throw new JAccessExceptionNotallowed(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

// Execute the task.
$controller = JControllerLegacy::getInstance('Managements');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();