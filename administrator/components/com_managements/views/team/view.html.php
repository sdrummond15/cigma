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

class ManagementsViewTeam extends JViewLegacy
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
        
        JToolBarHelper::title($isNew ? JText::_('COM_MANAGEMENTS_TEAM_ADD') : JText::_('COM_MANAGEMENTS_TEAM_EDIT'), 'equipe.png');
        
        
			JToolBarHelper::apply('team.apply');
			JToolBarHelper::save('team.save');
		
		

			JToolBarHelper::save2new('team.save2new');
		
		
			JToolBarHelper::save2copy('team.save2copy');
		

		if (empty($this->item->id))  {
			JToolBarHelper::cancel('team.cancel');
		} else {
			JToolBarHelper::cancel('team.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::divider();
		JToolBarHelper::help('JHELP_COMPONENTS_MANAGEMENTS_TEAMS_EDIT');
	}
}