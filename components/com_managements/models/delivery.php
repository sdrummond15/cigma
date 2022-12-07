<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');


class ManagementsModelDelivery extends JModelLegacy
{
    public function getCheck($id = '')
    {
        $id = JRequest::getVar('id');

        $user = JFactory::getUser();
        $id_user = $user->get('id');

        if (!empty($id)) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__tax_deliveries AS td');
            $query->where('td.id = ' . $id);
            $query->where('td.published = 1');
            $query->where('td.created_by = ' . $id_user);
            $db->setQuery($query);
            $results = $db->loadObjectList();
            if (empty($results)) {
                return false;
            }
        }

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__user_usergroup_map AS uum');
        $query->join('LEFT', $db->quoteName('#__users', 'u') . ' ON ' . $db->quoteName('u.id') . ' = ' . $db->quoteName('uum.user_id'));
        $query->where('uum.group_id = 10');
        $query->where('u.block = 0');
        $query->where('u.id = ' . $id_user);
        $db->setQuery($query);
        $results = $db->loadObjectList();
        if (empty($results)) {
            return false;
        }

        return true;
    }

    public function getDelivery($id = '')
    {
        if ($this->getCheck() === true) {
            $id = JRequest::getVar('id');

            $results = '';
            if (!empty($id)) {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select($db->quoteName('td.id', 'taxDeliveryId'));
                $query->select($db->quoteName('td.id_task', 'task'));
                $query->select($db->quoteName('td.id_client', 'client'));
                $query->select($db->quoteName('td.id_consultant', 'consultant'));
                $query->select($db->quoteName('td.date_delivery', 'date_delivery'));
                $query->select($db->quoteName('td.delivery', 'delivery'));
                $query->select($db->quoteName('td.observation', 'observation'));
                $query->from('#__tax_deliveries AS td');
                $query->where('td.id = ' . $id);
                $db->setQuery($query);
                $results = $db->loadObjectList();
            }
            return $results;
        }
    }

    public static function getClients($clients = '', $all_clients = true)
    {
        $results = '';
        if (!empty($clients) || $all_clients == true) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__clients AS c');
            $query->where('c.published = 1');
            if ($all_clients == false) {
                $query->where('id in (' . $clients . ')');
            }
            $db->setQuery($query);
            $results = $db->loadObjectList();
        }

        return $results;
    }
    
    public static function getTasks($tasks = '', $all_tasks = true)
    {
        $results = '';
        if (!empty($tasks) || $all_tasks == true) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__tasks AS t');
            $query->where('t.published = 1');
            if ($all_tasks == false) {
                $query->where('id in (' . $tasks . ')');
            }
            $db->setQuery($query);
            $results = $db->loadObjectList();
        }

        return $results;
    }

}
