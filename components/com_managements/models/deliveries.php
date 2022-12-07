<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');


class ManagementsModelDeliveries extends JModelLegacy
{

    public function getCheck()
    {
        $user = JFactory::getUser();
        $id_user = $user->get('id');

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__user_usergroup_map AS uum');
        $query->join('LEFT', '#__users AS u ON u.id = uum.user_id');
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

    public static function getDeliveries()
    {

        $user = JFactory::getUser();
        $id_user = $user->get('id');

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('td.id AS id,
                        t.task AS task,
                        c.name AS client,
                        td.date_delivery AS date_delivery,
                        td.published AS published,
                        td.created AS created');
        $query->from('#__tax_deliveries AS td');
        $query->join('LEFT', '#__clients AS c ON c.id = td.id_client');
        $query->join('LEFT', '#__tasks AS t ON t.id = td.id_task');
        $query->where('td.id_consultant = ' . $id_user);
        $query->where('td.published = 1');
        $query->order('td.published ASC');
        $query->order('td.id DESC');
        $db->setQuery($query);
        $results = $db->loadObjectList();
        return $results;
    }

    public static function getClients($clients = '')
    {
        $results = '';

        if (!empty($clients)) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('name AS client');
            $query->from('#__clients AS c');
            $query->where('c.published = 1');
            $query->where('c.id in (' . $clients . ')');
            $db->setQuery($query);
            $results = $db->loadObjectList();
        }

        return $results;
    }
}
