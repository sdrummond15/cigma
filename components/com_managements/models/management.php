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
            $query->where('am.id_consultant = ' . $id_user);
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

    public static function getCars($car = '')
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__cars AS c');
        $query->where('c.published = 1');
        if (!empty($car)) {
            $query->where('id = ' . $car);
        }
        $db->setQuery($query);
        $results = $db->loadObjectList();

        return $results;
    }

    public static function getAdvancedsMoney()
    {
        $results = '';
        $id_adv_money = JRequest::getVar('id');

        if (!empty($id_adv_money)) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('ae.*, 
                            ce.descricao AS category');
            $query->from('#__accountability_expenses AS ae');
            $query->join('LEFT', '#__cat_expenses AS ce ON ce.id = ae.cat_expenses');
            $query->where('ae.published = 1');
            $query->where('id_adv_money = ' . $id_adv_money);
            $db->setQuery($query);
            $results = $db->loadObjectList();
        }

        return $results;
    }

}
