<?php

/*
 * @package Managements
 * @com_managements
 * @copyright Copyright (C) Sdrummond, Inc. All rights reserved.
 * @license Sdrummond
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Methods supporting a list of tax_deliveries.
 *
 * @package  managements
 * @subpackage com_managements
 * @since 2.5
 */
class ManagementsModelTax_Deliveries extends JModelList
{
    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'consultant', 'u.name',
                'published', 'a.published',
                'publish_up', 'a.publish_up',
                'publish_down', 'a.publish_down',
                'created', 'a.created',
                'created_by', 'a.created_by'
            );
        }

        parent::__construct($config);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return    JDatabaseQuery
     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'a.id AS id,' .
                'a.checked_out,' .
                'a.checked_out_time,' .
                'a.date_delivery AS date_delivery,' .
                'tk.task AS task,' .
                'cl.name AS client,' .
                'u.name AS consultant,' .
                'a.created AS created,' .
                'ua.name AS created_by,' .
                'a.publish_up, a.publish_down,'.
                'a.published AS published'

            )
        );

        $query->from($db->quoteName('#__tax_deliveries') . ' AS a');

        $query->join('LEFT', '#__tasks AS tk ON tk.id = a.id_task');
        $query->join('LEFT', '#__clients AS cl ON cl.id = a.id_client');
        
        // Join over the users for the checked out user.
        $query->select('u.name AS consultant');
        $query->join('LEFT', '#__users AS u ON u.id = a.id_consultant');

        // Join over the users for the checked out user.
        $query->select('uc.name AS editor');
        $query->join('LEFT', '#__users AS uc ON uc.id = a.checked_out');

        // Join over the users for the author.
        $query->select('ua.name AS author_name');
        $query->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

        // Filter by published state
        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where('a.published = ' . (int)$published);
        } elseif ($published === '') {
            $query->where('(a.published IN (0, 1))');
        }

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int)substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('u.name LIKE ' . $search);
            }
        }

        $orderCol = $this->state->get('list.ordering', 'u.name');
        $orderDirn = $this->state->get('list.direction', 'asc');

        if ($orderCol == 'inst') {
            $orderCol = 'u.name';
        }
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }
    
    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param    string $id A prefix for the store id.
     *
     * @return    string        A store id.
     */
    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.published');

        return parent::getStoreId($id);
    }

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param   string  $type    The table type to instantiate
     * @param   string  $prefix  A prefix for the table class name. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  JTable  A JTable object
     *
     * @since   1.6
     */
    public function getTable($type = 'Tax_Deliveries', $prefix = 'ManagementsTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since    1.6
     */
    protected function populateState($ordering = 'u.name', $direction = 'asc')
    {
        // Initialise variables.
        $app = JFactory::getApplication();

        if ($layout = JRequest::getVar('layout')) {
            $this->context .= '.' . $layout;
        }
        // Load the filter state.
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

        // List state information.
        parent::populateState($ordering, $direction);
    }
}