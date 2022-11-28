<?php

/*
 * @package Managements
 * @com_admininistrations
 * @copyright Copyright (C) Sdrummond, Inc. All rights reserved.
 * @license Sdrummond
 */

defined('_JEXEC') or die;

/**
 * View class for a list of managements.
 *
 * @since  1.6
 */
class ManagementsViewCars extends JViewLegacy
{
    /**
	 * An array of items
	 *
	 * @var  array
	 */
    protected $items;

	/**
	 * The pagination object
	 *
	 * @var  JPagination
	 */
	protected $pagination;

	/**
	 * The model state
	 *
	 * @var  object
	 */
    protected $state;

    /**
     * Method to display the view.
     * 
	 * @param   string  $tpl  A template file to load. [optional]
     * 
	 * @return  mixed  A string if successful, otherwise a JError object.
     * 
	 * @since   1.6
     */
    public function display($tpl = null)
    {
        // Initialise variables
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->filterForm = $this->get('FilterForm');

        if (count($errors = $this->get('Errors')))
        {
            throw new Exception(implode("\n", $errors), 500);
        }

        //get document
        $doc = JFactory::getDocument();
        $doc->addStyleSheet('components/com_managements/assets/css/backend.css');

        ManagementsHelper::addSubmenu('managements');

        $this->addToolbar();

        // Include the component HTML helpers.
        JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

        $this->sidebar = JHtmlSidebar::render();

        return parent::display($tpl);
    }

    /**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
    protected function addToolbar()
    {

        JLoader::register('ManagementsHelper', JPATH_ADMINISTRATOR . '/components/com_managements/helpers/managements.php');

        $user  = JFactory::getUser();

        JToolBarHelper::title(JText::_('COM_MANAGEMENTS_MANAGER_CARS'), 'car');

        JToolBarHelper::addNew('car.add');

        JToolBarHelper::editList('car.edit');

        if ($this->state->get('filter.published') != 2) {
            JToolbarHelper::publish('cars.publish', 'JTOOLBAR_PUBLISH', true);
            JToolbarHelper::unpublish('cars.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        }

        if ($this->state->get('filter.state') != -1) {
            JToolBarHelper::divider();
            if ($this->state->get('filter.state') != 2) {
                JToolBarHelper::archiveList('cars.archive');
            } elseif ($this->state->get('filter.state') == 2) {
                JToolBarHelper::unarchiveList('cars.publish');
            }
        }

        JToolBarHelper::checkin('cars.checkin');

        // Add a batch button
        if (
            $user->authorise('core.create', 'com_managements')
            && $user->authorise('core.edit', 'com_managements')
            && $user->authorise('core.edit.state', 'com_managements')
        ) {
            $title = JText::_('JTOOLBAR_BATCH');

            // Instantiate a new JLayoutFile instance and render the batch button
            $layout = new JLayoutFile('joomla.toolbar.batch');

            $dhtml = $layout->render(array('title' => $title));
            JToolbar::getInstance('toolbar')->appendButton('Custom', $dhtml, 'batch');
        }

        JToolbarHelper::trash('cars.trash');

        if ($user->authorise('core.admin', 'com_managements') || $user->authorise('core.options', 'com_managements')) {
            JToolbarHelper::preferences('com_managements');
        }

        JToolbarHelper::help('JHELP_COMPONENTS_MANAGEMENTS_CARS');
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
            'a.plate' => JText::_('JGLOBAL_TITLE'),
            'ul.name' => JText::_('COM_MANAGEMENTS_FIELD_LINKED_USER_LABEL')
        );
    }
}