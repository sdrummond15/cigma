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
class ManagementsViewTeams extends JViewLegacy
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

        JToolBarHelper::title(JText::_('COM_MANAGEMENTS_MANAGER_TEAMS'), 'equipe.png');

        JToolBarHelper::addNew('team.add');

        JToolBarHelper::editList('team.edit');


        if ($this->state->get('filter.state') != -1) {
            JToolBarHelper::divider();
            if ($this->state->get('filter.state') != 2) {
                JToolBarHelper::archiveList('teams.archive');
            } elseif ($this->state->get('filter.state') == 2) {
                JToolBarHelper::unarchiveList('teams.publish');
            }
        }

        JToolBarHelper::checkin('teams.checkin');

        if ($this->state->get('filter.state') == -2) {
            JToolBarHelper::deleteList('', 'teams.delete', 'JTOOLBAR_EMPTY_TRASH');
            JToolBarHelper::divider();
        }

        JToolBarHelper::trash('teams.trash');
        JToolBarHelper::divider();

        JToolBarHelper::preferences('com_managements');
        JToolBarHelper::divider();

        JToolBarHelper::help('teams', $com = true);
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