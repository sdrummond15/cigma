<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_enterprises
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of enterprises.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_enterprises
 * @since       1.6
 */
class EnterprisesViewEnterprises extends JViewLegacy
{
        protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}


		$this->addToolbar();
                
		$this->sidebar = JHtmlSidebar::render();
		
                parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
                require_once JPATH_COMPONENT.'/helpers/enterprises.php';
            
                $canDo	= EnterprisesHelper::getActions();
                
                $user	= JFactory::getUser();

		JToolbarHelper::title(JText::_('COM_ENTERPRISES_MANAGER_ENTERPRISES'), 'enterprises.png');

		JToolbarHelper::addNew('enterprise.add');
		
		if (($canDo->get('core.edit')))
		{
			JToolbarHelper::editList('enterprise.edit');
		}
                

		if ($this->state->get('filter.published') != 2)
                {
                        JToolBarHelper::divider();
                        JToolBarHelper::publish('enterprises.publish', 'JTOOLBAR_PUBLISH', true);
                        JToolBarHelper::unpublish('enterprises.unpublish', 'JTOOLBAR_UNPUBLISH', true);
                }

                if ($this->state->get('filter.published') != -1)
                {
                        JToolBarHelper::divider();
                        if ($this->state->get('filter.published') != 2)
                        {
                                JToolBarHelper::archiveList('enterprises.archive');
                        }
                        elseif ($this->state->get('filter.published') == 2)
                        {
                                JToolBarHelper::unarchiveList('enterprises.publish');
                        }
                }
                
                if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::checkin('enterprises.checkin');
		}
                
		if($this->state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('','enterprises.delete','JTOOLBAR_EMPTY_TRASH');
                        
		}
                
                elseif ($canDo->get('core.edit.state'))
                {
                    JToolbarHelper::trash('enterprises.trash');
                    JToolbarHelper::divider();
                }
                
                if($canDo->get('core.admin'))
                {
                    JToolbarHelper::preferences('com_enterprises');
                    JToolbarHelper::divider();
                }
                JToolbarHelper::help('enterprises',$com = true);
	}

        
        /**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
			'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'a.published' => JText::_('JSTATUS'),
			'a.name' => JText::_('JGLOBAL_TITLE'),
			'ul.name' => JText::_('COM_ENTERPRISES_FIELD_LINKED_USER_LABEL'),
			'a.featured' => JText::_('JFEATURED'),
			'a.access' => JText::_('JGRID_HEADING_ACCESS'),
			'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
	
}
