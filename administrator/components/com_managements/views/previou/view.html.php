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

class ManagementsViewPreviou extends JViewLegacy
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
        
        JToolBarHelper::title($isNew ? JText::_('COM_MANAGEMENTS_PREVIOU_ADD') : JText::_('COM_MANAGEMENTS_PREVIOU_EDIT'), 'previou.png');
        
        
			JToolBarHelper::apply('previou.apply');
			JToolBarHelper::save('previou.save');
		
		

			JToolBarHelper::save2new('previou.save2new');
		
		
			JToolBarHelper::save2copy('previou.save2copy');
		

		if (empty($this->item->id))  {
			JToolBarHelper::cancel('previou.cancel');
		} else {
			JToolBarHelper::cancel('previou.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::divider();
		JToolBarHelper::help('JHELP_COMPONENTS_MANAGEMENTS_PREVIOUS_EDIT');
	}
}