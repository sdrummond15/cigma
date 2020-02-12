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

class ManagementsViewCitie extends JViewLegacy
{
    protected $form;
    protected $item;
    protected $state;
    
    public function display($tpl = null) 
    {
        $this->form         = $this->get('Form');
        $this->item         = $this->get('Item');
        $this->state        = $this->get('State');
        
        if(count($erros = $this->get('Errors'))){
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }
        
        $doc = JFactory::getDocument();
        $doc ->addStyleSheet(JURI::root().'../media/com_managements/css/backend.css');
        
        $this->addToolbar();
        
        parent::display($tpl);
    }
    
    protected function addToolbar()
    {
        JRequest::setVar('hidemainmenu', true);
        
        $user       = JFactory::getUser();
        $userId     = $user->get('id');
        $isNew      = ($this->item->id == 0);
        $checkedOut = 0;
        $canDo      = ManagementsHelper::getActions($this->item);
        
        JToolBarHelper::title($isNew ? JText::_('COM_MANAGEMENTS_CITIE_ADD') : JText::_('COM_MANAGEMENTS_CITIE_EDIT'), 'cidade.png');
       
        if (!$checkedOut && $canDo->get('core.edit') > 0){
                JToolBarHelper::apply('citie.apply');
                JToolBarHelper::save('citie.save');
                
                if ($canDo->get('core.create')) {
                JToolBarHelper::save2new('citie.save2new');
                }
        }
        if (!$isNew && $canDo->get('core.create')) {
                JToolBarHelper::save2copy('citie.save2copy');
        }

		if (empty($this->item->id))  {
			JToolBarHelper::cancel('citie.cancel');
		} else {
			JToolBarHelper::cancel('citie.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::divider();
		JToolBarHelper::help('JHELP_COMPONENTS_MANAGEMENTS_CIRCUIT_EDIT');
	}
}