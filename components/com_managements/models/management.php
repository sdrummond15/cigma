<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');


class ManagementsModelManagement extends JModelLegacy
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
            $query->from('#__advanced_money AS am');
            $query->where('am.id = ' . $id);
            $query->where('am.published = 1');
            $query->where('am.id_consultor = ' . $id_user);
            $db->setQuery($query);
            $results = $db->loadObjectList();
            if (empty($results)) {
                return false;
            }
        }

        return true;
    }

    public function getManagement($id = '')
    {
        if ($this->getCheck() === true) {
            $id = JRequest::getVar('id');

            $results = '';
            if (!empty($id)) {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('*');
                $query->from('#__advanced_money AS am');
                $query->where('am.id = ' . $id);
                $db->setQuery($query);
                $results = $db->loadObjectList();
            }
            return $results;
        }
    }

    public function getClients()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__clients AS c');
        $query->where('c.published = 1');
        $db->setQuery($query);
        $results = $db->loadObjectList();

        return $results;
    }
    
    public function getCategories()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__cat_expenses AS c');
        $query->where('c.published = 1');
        $db->setQuery($query);
        $results = $db->loadObjectList();

        return $results;
    }
    
    public function getCars()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__cars AS c');
        $query->where('c.published = 1');
        $db->setQuery($query);
        $results = $db->loadObjectList();

        return $results;
    }

}
