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

/**
 * View class for a list of management.
 *
 * @package  management
 * @subpackage com_adminstration
 * @since 2.5
 */
class ManagementsViewGrids extends JViewLegacy
{
    protected $items;
    protected $paginaton;
    protected $state;

    /*
     * Method to display the view.
     * 
     * @param string $tpl  A template file to load. [optional]
     * 
     * @return mixed   A string if successful, otherwise a JError object.
     * 
     * @since 1.6
     */
    public function display($tpl = null)
    {
        // Initialise variables
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');

        if (count($errors = $this->get('Erros'))) {
            JError::raiseError(500, implode("\n", $errors));
        }

        //get document
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JURI::root() . 'media/com_managements/css/backend.css');

        $this->addToolbar();

        // Include the component HTML helpers.
        JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        require_once JPATH_COMPONENT . '/helpers/managements.php';

        JToolBarHelper::title(JText::_('COM_MANAGEMENTS_MANAGER_GRIDS'), 'grid.png');

        JToolBarHelper::addNew('grid.add');

        JToolBarHelper::editList('grid.edit');

        if ($this->state->get('filter.state') != 2) {
            JToolBarHelper::divider();
            JToolBarHelper::publish('grids.publish', 'JTOOLBAR_PUBLISH', true);
            JToolBarHelper::unpublish('grids.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        }

        if ($this->state->get('filter.state') != -1) {
            JToolBarHelper::divider();
            if ($this->state->get('filter.state') != 2) {
                JToolBarHelper::archiveList('grids.archive');
            } elseif ($this->state->get('filter.state') == 2) {
                JToolBarHelper::unarchiveList('grids.publish');
            }
        }

        JToolBarHelper::checkin('grids.checkin');

        if ($this->state->get('filter.state') == -2) {
            JToolBarHelper::deleteList('', 'grids.delete', 'JTOOLBAR_EMPTY_TRASH');
            JToolBarHelper::divider();
        }

        JToolBarHelper::trash('grids.trash');
        JToolBarHelper::divider();

        JToolBarHelper::preferences('com_managements');
        JToolBarHelper::divider();

        JToolBarHelper::help('grids', $com = true);
    }

    protected function getSortFields()
    {
        return array(
            'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
            'a.published' => JText::_('JSTATUS'),
            'a.name' => JText::_('JGLOBAL_TITLE'),
            'ul.name' => JText::_('COM_MANAGEMENTS_FIELD_LINKED_USER_LABEL'),
            'a.featured' => JText::_('JFEATURED'),
            'a.access' => JText::_('JGRID_HEADING_ACCESS'),
            'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
            'a.id' => JText::_('JGRID_HEADING_ID')
        );
    }
}