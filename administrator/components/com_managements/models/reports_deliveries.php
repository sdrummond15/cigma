<?php

/*
 * @package Managements
 * @com_admininistrations
 * @copyright Copyright (C) Sdrummond, Inc. All rights reserved.
 * @license Sdrummond
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomltd.application.component.modellist');

/**
 * Methods supporting a list of administration.
 *
 * @package  administration
 * @subpackage com_adminstration
 * @since 2.5
 */
class ManagementsModelReports_Deliveries extends JModelList
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
                'id', 'td.id',
                'consultant', 'u.name',
                'published', 'td.published',
                'publish_up', 'td.publish_up',
                'publish_down', 'td.publish_down',
                'created', 'td.created',
                'created_by', 'td.created_by'
            );
        }

        parent::__construct($config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_managements.reports_deliveries', 'reports_deliveries', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return    mixed    The data for the form.
     * @since    1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form dattd.
        $data = JFactory::getApplication()->getUserState('com_managements.edit.reports_deliveries.data', array());

        return $data;
    }

    /**
     * Build an SQL query to load the list dattd.
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
                'td.id AS id,' .
                    'td.checked_out,' .
                    'td.checked_out_time,' .
                    'td.date_delivery AS date_delivery,' .
                    'td.created AS created,' .
                    'utd.name AS created_by,' .
                    'td.publish_up, td.publish_down,' .
                    'td.published AS published'

            )
        );

        $query->from($db->quoteName('#__tax_deliveries') . ' AS td');

        // Join over the users for the checked out user.
        $query->select('u.name AS consultant');
        $query->join('LEFT', '#__users AS u ON u.id = td.id_consultant');

        // Join over the users for the checked out user.
        $query->select('uc.name AS editor');
        $query->join('LEFT', '#__users AS uc ON uc.id = td.checked_out');

        // Join over the users for the author.
        $query->select('utd.name AS author_name');
        $query->join('LEFT', '#__users AS ua ON utd.id = td.created_by');

        // Filter by published state
        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where('td.published = ' . (int) $published);
        } elseif ($published === '') {
            $query->where('(td.published IN (0, 1))');
        }

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('td.id = ' . (int) substr($search, 3));
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

    public function getReport($data)
    {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__tax_deliveries') . ' AS td');

        //FILTRO TAREFAS
        if (isset($data['id_task']) && !empty($data['id_task'])) {
            $query->where('td.id_task IN (' . implode(',', $data['id_task']) . ')');
        }
        
        //FILTRO CONSULTORES
        if (isset($data['id_consultant']) && !empty($data['id_consultant'])) {
            $query->where('td.id_consultant = ' . $data['id_consultant']);
        }

        //FILTRO OBSERVAÇÃO
        if (isset($data['observation']) && intval($data['observation']) == 0) {
            $query->where('td.observation = ""');
        } else if (isset($data['observation']) && intval($data['observation']) == 1) {
            $query->where('td.observation <> ""');
        }

        //FILTRO DATAS
        if (
            (isset($data['date_delivery_in']) && !empty($data['date_delivery_in'])) &&
            (!isset($data['date_delivery_out']) || empty($data['date_delivery_out']))
        ) {
            $query->where('td.date_delivery >= \'' . $this->inverteData($data['date_delivery_in']) . '\'');
        } elseif (
            (!isset($data['date_delivery_in']) || empty($data['date_delivery_in'])) &&
            (isset($data['date_delivery_out']) && !empty($data['date_delivery_out']))
        ) {
            $query->where('td.date_delivery <= \'' . $this->inverteData($data['date_delivery_out']) . '\'');
        } elseif (
            (isset($data['date_delivery_in']) && !empty($data['date_delivery_in'])) &&
            (isset($data['date_delivery_out']) && !empty($data['date_delivery_out']))
        ) {
            $query->where('(td.date_delivery >= \'' . $this->inverteData($data['date_delivery_in']) . '\' AND td.date_delivery <= \'' . $this->inverteData($data['date_delivery_out']) . '\')');
        }

        $db->setQuery($query);

        // echo $query;die();
        $results = $db->loadObjectList();

        if ($results) {
            //FILTRO CLIENTES
            if (isset($data['id_client']) && !empty($data['id_client'])) {

                $clients = array();
                foreach ($results as $result) {
                    $all_clients = explode(',', $result->id_client);
                    foreach ($all_clients as $client) {
                        array_push($clients, array('client' => $client, 'id_adv_money' => $result->id));
                    }
                }

                $id_adv_money = array();
                foreach ($data['id_client'] as $dataClient) {
                    foreach ($clients as $client) {
                        if ($client['client'] == $dataClient) {
                            array_push($id_adv_money, $client['id_adv_money']);
                        }
                    }
                }

                $all_adv_money = array_unique(array_merge($id_adv_money));

                $queryAdv = $db->getQuery(true);
                $queryAdv->select('id');
                $queryAdv->from($db->quoteName('#__tax_deliveries') . ' AS td');
                $queryAdv->where('td.id IN (' . implode(',', $all_adv_money) . ')');
                $db->setQuery($queryAdv);
                $results = $db->loadObjectList();
            }

            $ids = array();
            foreach ($results as $r) {
                array_push($ids, $r->id);
            }

            $queryR = $db->getQuery(true);
            $queryR->select('
                t.task as desc_task,
                u.name as name_consultant,
                td.observation as observation,
                td.date_delivery,
                td.id_client AS clients
            ');
            $queryR->from($db->quoteName('#__tax_deliveries') . ' AS td');
            $queryR->join('LEFT', $db->quoteName('#__users') . ' AS u ON td.id_consultant = u.id');
            $queryR->join('LEFT', $db->quoteName('#__tasks') . ' AS t ON td.id_task = t.id');
            if (!empty($ids)) {
                $queryR->where('td.id IN (' . implode(',', $ids) . ')');
            }

            //ORDENAR
            if (isset($data['order']) && !empty($data['order'])) {
                $order = 'td.' . implode(',', $data['order']);
                if (strpos($order, ',')) {
                    $order = str_replace(',', ',td.', $order);
                }
                $queryR->order($order . ' ' . $data['order_to']);
            }

            $db->setQuery($queryR);
            // echo $queryR; die();
            $results = $db->loadObjectList();
        }

        // die($results);
        return $results;
    }

    public function getClients($client)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('name');
        $query->from($db->quoteName('#__clients') . ' AS c');
        $query->where('c.id IN (' . $client . ')');

        $db->setQuery($query);
        $results = $db->loadObjectList();

        return $results;
    }

    public function inverteData($data)
    {
        $data = explode('-', $data);
        return $data[2] . '-' . $data[1] . '-' . $data[0];
    }
}
