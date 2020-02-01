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
class ManagementsViewKartings extends JViewLegacy
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
        $this->addToolbar();

        // Include the component HTML helpers.
        JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        require_once JPATH_COMPONENT . '/helpers/managements.php';

        JToolBarHelper::title(JText::_('COM_MANAGEMENTS_MANAGER_KARTINGS'), 'kartodromo.png');

        JToolBarHelper::addNew('karting.add');

        JToolBarHelper::editList('karting.edit');

        if ($this->state->get('filter.state') != 2) {
            JToolBarHelper::divider();
            JToolBarHelper::publish('kartings.publish', 'JTOOLBAR_PUBLISH', true);
            JToolBarHelper::unpublish('kartings.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        }

        if ($this->state->get('filter.state') != -1) {
            JToolBarHelper::divider();
            if ($this->state->get('filter.state') != 2) {
                JToolBarHelper::archiveList('kartings.archive');
            } elseif ($this->state->get('filter.state') == 2) {
                JToolBarHelper::unarchiveList('kartings.publish');
            }
        }

        JToolBarHelper::checkin('kartings.checkin');

        if ($this->state->get('filter.state') == -2) {
            JToolBarHelper::deleteList('', 'kartings.delete', 'JTOOLBAR_EMPTY_TRASH');
            JToolBarHelper::divider();
        }

        JToolBarHelper::trash('kartings.trash');
        JToolBarHelper::divider();

        JToolBarHelper::preferences('com_managements');
        JToolBarHelper::divider();

        JToolBarHelper::help('kartings', $com = true);
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