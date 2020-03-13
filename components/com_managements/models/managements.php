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
        $query->where('am.pendency = 0');
        $query->order('am.published ASC');
        $query->order('am.id DESC');
        $db->setQuery($query);
        $results = $db->loadObjectList();
        return $results;
    }

    public static function getClients($clients = '')
    {
        $results = '';
        
        if(!empty($clients)) {
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