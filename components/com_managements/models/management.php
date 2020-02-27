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
            $query->from('#__managements AS e');
            $query->where('id = ' . $id);
            $query->where('e.published != -2 ');
            $query->where('e.created_by = ' . $id_user);
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
                $query->from('#__managements AS e');
                $query->where('id = ' . $id);
                $db->setQuery($query);
                $results = $db->loadObjectList();
            }
            return $results;
        }

    }

    public function getPhotos($id = '')
    {
        if ($this->getCheck() === true) {
            $id = JRequest::getVar('id');

            $results = '';
            if (!empty($id)) {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('*');
                $query->from('#__management_photos AS ep');
                $query->where('id_management = ' . $id);
                $db->setQuery($query);
                $results = $db->loadObjectList();
            }
            return $results;
        }
    }

    public function getOwners()
    {
        if ($this->getCheck() === true) {

            $user = JFactory::getUser();
            $id_user = $user->get('id');

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__owners AS o');
            $query->where('created_by = ' . $id_user);
            $query->where('published = 1');
            $query->order('name');
            $db->setQuery($query);
            $results = $db->loadObjectList();
            return $results;
        }
    }

    public function getStates()
    {
        if ($this->getCheck() === true) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('s.id AS id, s.abbreviation AS uf, s.description AS state');
            $query->from('#__state AS s');
            $query->order('s.description');
            $db->setQuery($query);
            $results = $db->loadObjectList();
            return $results;
        }
    }

    public function getCities()
    {
        if ($this->getCheck() === true) {
            $id = JRequest::getVar('id');

            $state = '';
            if (!empty($id)) {
                $state = $this->getManagement()[0]->state;
            }

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('c.id AS id, c.description AS city');
            $query->from('#__city AS c');
            if (!empty($state)) {
                $query->where('c.id_state = ' . $state);
            }
            $query->order('c.description');
            $db->setQuery($query);
            $results = $db->loadObjectList();
            return $results;
        }
    }

}