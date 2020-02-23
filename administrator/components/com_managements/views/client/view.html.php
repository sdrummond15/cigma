<?php

/*
 * @package Managements
 * @com_admininistrations
 * @copyright Copyright (C) Sdrummond, Inc. All rights reserved.
 * @license Sdrummond
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class ManagementsViewClient extends JViewLegacy
{
    protected $form;
    protected $item;
    protected $state;


    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');

        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);
        }

        $doc = JFactory::getDocument();
        $doc->addStyleSheet('components/com_managements/assets/css/backend.css');

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JRequest::setVar('hidemainmenu', true);

        $isNew = ($this->item->id == 0);

        JToolBarHelper::title($isNew ? JText::_('COM_MANAGEMENTS_CLIENT_ADD') : JText::_('COM_MANAGEMENTS_CLIENT_EDIT'), 'client.png');

        JToolBarHelper::apply('client.apply');
        JToolBarHelper::save('client.save');
        JToolBarHelper::save2new('client.save2new');
        JToolBarHelper::save2copy('client.save2copy');

        if (empty($this->item->id)) {
            JToolBarHelper::cancel('client.cancel');
        } else {
            JToolBarHelper::cancel('client.cancel', 'JTOOLBAR_CLOSE');
        }

        JToolBarHelper::divider();
        JToolBarHelper::help('JHELP_COMPONENTS_MANAGEMENTS_CLIENTS_EDIT');
    }
}