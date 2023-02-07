<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');


class ManagementsModelReport_Deliveries extends JModelLegacy
{

    public function getTasks()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('t.*');
        $query->from('#__tasks AS t');
        $query->join('LEFT', '#__tax_deliveries AS td ON td.id_task = t.id');
        $query->where('t.published = 1');
        $query->group('t.id');
        $query->order('t.id');
        $db->setQuery($query);
        $results = $db->loadObjectList();
        return $results;
    }

    public static function getClients()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('c.*');
        $query->from('#__clients AS c');
        $query->join('LEFT', '#__tax_deliveries AS td ON td.id_client = c.id');
        $query->where('c.published = 1');
        $query->group('c.id');
        $query->order('c.name');
        $db->setQuery($query);
        $results = $db->loadObjectList();

        return $results;
    }

    public static function getConsultants()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('u.*');
        $query->from('#__users AS u');
        $query->join('LEFT', '#__tax_deliveries AS td ON td.id_consultant = u.id');
        $query->where('u.block = 0');
        $query->group('u.id');
        $query->order('u.name');
        $db->setQuery($query);
        $results = $db->loadObjectList();

        return $results;
    }

    public function getReport($data)
    {

        echo $data; die();
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
            $results = $db->loadObjectList();
        }

        return $results;
    }

}
