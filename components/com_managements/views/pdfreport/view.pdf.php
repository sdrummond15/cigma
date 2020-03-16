<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class AdministrationsViewPdfreport extends JView
{
    protected $form;
    protected $item;
    protected $state;
    protected $cidade;
    
    public function display($tpl = null) 
    {
        $this->form         = $this->get('Form');
        $this->item         = $this->get('Item');
        $this->state        = $this->get('State');
        $this->cidade       = $this->get('Cidade');
        
        if(count($erros = $this->get('Errors'))){
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }
        
        $document = JFactory::getDocument();
        $document->setName('Relatorio');
        
        parent::display($tpl);
    }
    
    protected function addToolbar()
    {
        JRequest::setVar('hidemainmenu', true);
        
        $user       = JFactory::getUser();
        $userId     = $user->get('id');
        $isNew      = ($this->item->id == 0);
        $checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
        
        JToolBarHelper::title($isNew ? JText::_('COM_ADMINISTRATIONS_INSTITUTION_ADD') : JText::_('COM_ADMINISTRATIONS_INSTITUTION_EDIT'), 'institution.png');
        
        
	}
}
?>
