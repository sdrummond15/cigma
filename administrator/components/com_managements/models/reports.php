<?php

/*
 * @package Managements
 * @com_admininistrations
 * @copyright Copyright (C) Sdrummond, Inc. All rights reserved.
 * @license Sdrummond
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of administration.
 *
 * @package  administration
 * @subpackage com_adminstration
 * @since 2.5
 */
class ManagementsModelReports extends JModelList
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

    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_managements.reports', 'reports', array('control' => 'jform', 'load_data' => $loadData));
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
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_managements.edit.reports.data', array());

        return $data;
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
                    'a.date_in AS date_in,' .
                    'a.date_out AS date_out,' .
                    'a.created AS created,' .
                    'ua.name AS created_by,' .
                    'a.publish_up, a.publish_down,' .
                    'a.published AS published'

            )
        );

        $query->from($db->quoteName('#__advanced_money') . ' AS a');

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
            $query->where('a.published = ' . (int) $published);
        } elseif ($published === '') {
            $query->where('(a.published IN (0, 1))');
        }

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
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
        $query->from($db->quoteName('#__advanced_money') . ' AS a');

        //FILTRO CONSULTORES
        if (isset($data['id_consultant']) && !empty($data['id_consultant'])) {
            $query->where('a.id_consultant = ' . $data['id_consultant']);
        }

        //FILTRO CARROS
        if (isset($data['id_car']) && !empty($data['id_car'])) {
            $query->where('a.id_car IN (' . implode(',', $data['id_car']) . ')');
        }

        //FILTRO VALORES
        $minCash = '0.00';
        if (isset($data['min_cash']) && !empty($data['min_cash'])) {
            $minCash = $this->convertMoeda($data['min_cash']);
        }

        $maxCash = '0.00';
        if (isset($data['max_cash']) && !empty($data['max_cash'])) {
            $maxCash = $this->convertMoeda($data['max_cash']);
        }

        if ($minCash != '0.00' && $maxCash == '0.00') {
            $query->where('a.cash >= \'' . $minCash . '\'');
        } elseif ($minCash == '0.00' && $maxCash != '0.00') {
            $query->where('a.cash <= \'' . $maxCash . '\'');
        } elseif ($minCash != '0.00' && $maxCash != '0.00') {
            $query->where('(a.cash BETWEEN \'' . $minCash . '\' AND \'' . $maxCash . '\')');
        }

        //FILTRO PENDÊNCIA
        if (isset($data['pendency'])) {
            $query->where('a.pendency = ' . intval($data['pendency']));
        }

        //FILTRO DATAS
        if (
            (isset($data['date_in']) && !empty($data['date_in'])) &&
            (!isset($data['date_out']) || empty($data['date_out']))
        ) {
            $query->where('a.date_in >= \'' . $this->inverteData($data['date_in']) . '\'');
        } elseif (
            (!isset($data['date_in']) || !empty($data['date_in'])) &&
            (isset($data['date_out']) && !empty($data['date_out']))
        ) {
            $query->where('a.date_out <= \'' . $this->inverteData($data['date_out']) . '\'');
        } elseif (
            (isset($data['date_in']) && !empty($data['date_in'])) &&
            (isset($data['date_out']) && !empty($data['date_out']))
        ) {
            $query->where('(a.date_in >= \'' . $this->inverteData($data['date_in']) . '\' AND a.date_out >= \'' . $this->inverteData($data['date_out']) . '\')');
        }

        $db->setQuery($query);
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
                $queryAdv->from($db->quoteName('#__advanced_money') . ' AS a');
                $queryAdv->where('a.id IN (' . implode(',', $all_adv_money) . ')');
                $db->setQuery($queryAdv);
                $results = $db->loadObjectList();
            }

            $ids = array();
            foreach ($results as $r) {
                array_push($ids, $r->id);
            }

            $queryR = $db->getQuery(true);
            $queryR->select('
                u.name as name_consultant,
                coalesce(CONCAT(c.mark, " - ", c.model, " (", c.plate, ")"), "Sem Carro") as car,
                a.cash,
                CASE
                    WHEN a.pendency = 1 THEN "NÃO"
                    ELSE "SIM"
                END pendency,
                a.date_in,
                a.date_out,
                a.id_client AS clients
            ');
            $queryR->from($db->quoteName('#__advanced_money') . ' AS a');
            $queryR->join('LEFT', $db->quoteName('#__users') . ' AS u ON a.id_consultant = u.id');
            $queryR->join('LEFT', $db->quoteName('#__cars') . ' AS c ON a.id_car = c.id');
            if (!empty($ids)) {
                $queryR->where('a.id IN (' . implode(',', $ids) . ')');
            }

            //ORDENAR
            if (isset($data['order']) && !empty($data['order'])) {
                $order = 'a.' . implode(',', $data['order']);
                if (strpos($order, ',')) {
                    $order = str_replace(',', ',a.', $order);
                }
                $queryR->order($order . ' ' . $data['order_to']);
            }

            $db->setQuery($queryR);
            $results = $db->loadObjectList();
        }

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

    public function convertMoeda($valor)
    {
        $preco = substr($valor, 3);
        $preco = str_replace('.', '', $preco);
        $preco = str_replace(',', '.', $preco);
        $preco = number_format($preco, 2, '.', '');
        return $preco;
    }

    public function inverteData($data)
    {
        $data = explode('-', $data);
        return $data[2] . '-' . $data[1] . '-' . $data[0];
    }
}
