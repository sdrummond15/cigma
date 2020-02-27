<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');


class ManagementsModelManagements extends JModelLegacy
{

    public static function getManagements()
    {

        $user = JFactory::getUser();
        $id_user = $user->get('id');

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('am.id AS id,
                        am.id_consultant AS id_consultant,
                        am.id_client AS id_client,
                        am.date_in AS date_in,
                        am.date_out AS date_out,
                        am.published AS published,
                        am.created AS created');
        $query->from('#__advanced_money AS am');
        $query->where('am.id_consultant = '.$id_user);
        $query->where('am.published = 1');
        $query->order('am.published ASC');
        $query->order('am.id DESC');
        $db->setQuery($query);
        $results = $db->loadObjectList();
        return $results;
    }

    // public function getOwners()
    // {
    //     $user = JFactory::getUser();
    //     $id_user = $user->get('id');

    //     $db = JFactory::getDBO();
    //     $query = $db->getQuery(true);
    //     $query->select('*');
    //     $query->from('#__owners AS o');
    //     $query->where('o.created_by = '.$id_user);
    //     $query->where('o.published = 1');

    //     $db->setQuery($query);
    //     $results = $db->loadObjectList();
    //     return $results;
    // }

    // public function getTypes()
    // {
    //     $user = JFactory::getUser();
    //     $id_user = $user->get('id');

    //     $db = JFactory::getDBO();
    //     $query = $db->getQuery(true);
    //     $query->select('type');
    //     $query->from('#__managements AS e');
    //     $query->where('e.created_by = '.$id_user);
    //     $query->where('e.published != -2');
    //     $query->group('e.type');
    //     $db->setQuery($query);
    //     $results = $db->loadObjectList();
    //     return $results;
    // }

    // public function getBusiness()
    // {
    //     $user = JFactory::getUser();
    //     $id_user = $user->get('id');

    //     $db = JFactory::getDBO();
    //     $query = $db->getQuery(true);
    //     $query->select('business');
    //     $query->from('#__managements AS e');
    //     $query->where('e.created_by = '.$id_user);
    //     $query->where('e.published != -2');
    //     $query->group('e.business');
    //     $db->setQuery($query);
    //     $results = $db->loadObjectList();
    //     return $results;
    // }

    public static function getClients($clients = 1)
    {
        
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('name AS client');
        $query->from('#__clients AS c');
        $query->where('c.published = 1');
        $query->where('c.id in (' . $clients . ')');
        $db->setQuery($query);
        $results = $db->loadObjectList();

        return $results;
    }

    // public function getDistricts()
    // {
    //     $user = JFactory::getUser();
    //     $id_user = $user->get('id');

    //     $db = JFactory::getDBO();
    //     $query = $db->getQuery(true);
    //     $query->select('district');
    //     $query->from('#__managements AS e');
    //     $query->where('e.created_by = '.$id_user);
    //     $query->where('e.published != -2');
    //     $query->group('e.district');
    //     $db->setQuery($query);
    //     $results = $db->loadObjectList();
    //     return $results;
    // }

    // public function getCreated()
    // {
    //     $user = JFactory::getUser();
    //     $id_user = $user->get('id');

    //     $db = JFactory::getDBO();
    //     $query = $db->getQuery(true);
    //     $query->select('created');
    //     $query->from('#__managements AS e');
    //     $query->where('e.created_by = '.$id_user);
    //     $query->where('e.published != -2');
    //     $query->group('DATE (e.created)');
    //     $db->setQuery($query);
    //     $results = $db->loadObjectList();
    //     return $results;
    // }

}