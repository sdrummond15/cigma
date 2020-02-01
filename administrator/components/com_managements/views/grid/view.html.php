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

class ManagementsViewGrid extends JViewLegacy
{
    protected $form;
    protected $item;
    protected $state;
    
    
    public function display($tpl = null) 
    {
        $this->form         = $this->get('Form');
        $this->item         = $this->get('Item');
        $this->state        = $this->get('State');
        $this->pilots        = $this->get('Pilots');

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
        
        JToolBarHelper::title($isNew ? JText::_('COM_MANAGEMENTS_GRID_ADD') : JText::_('COM_MANAGEMENTS_GRID_EDIT'), 'grid.png');
        
        
			JToolBarHelper::apply('grid.apply');
			JToolBarHelper::save('grid.save');
		
		

			JToolBarHelper::save2new('grid.save2new');
		
		
			JToolBarHelper::save2copy('grid.save2copy');
		

		if (empty($this->item->id))  {
			JToolBarHelper::cancel('grid.cancel');
		} else {
			JToolBarHelper::cancel('grid.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::divider();
		JToolBarHelper::help('JHELP_COMPONENTS_MANAGEMENTS_GRIDS_EDIT');
	}
}