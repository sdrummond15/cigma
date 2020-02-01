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

class ManagementsViewChampionship extends JViewLegacy
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
        $checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
        $canDo      = ManagementsHelper::getActions($this->item);
        
        JToolBarHelper::title($isNew ? JText::_('COM_MANAGEMENTS_CHAMPIONSHIP_ADD') : JText::_('COM_MANAGEMENTS_CHAMPIONSHIP_EDIT'), 'campeonato.png');
        if ($canDo->get('core.edit')){
                JToolBarHelper::apply('championship.apply');
                JToolBarHelper::save('championship.save');
                
                if ($canDo->get('core.create')) {
                JToolBarHelper::save2new('championship.save2new');
                }
        }
        if (!$isNew && $canDo->get('core.create')) {
                JToolBarHelper::save2copy('championship.save2copy');
        }

		if (empty($this->item->id))  {
			JToolBarHelper::cancel('championship.cancel');
		} else {
			JToolBarHelper::cancel('championship.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::divider();
		JToolBarHelper::help('JHELP_COMPONENTS_MANAGEMENTS_CHAMPIONSHIP_EDIT');
	}
}