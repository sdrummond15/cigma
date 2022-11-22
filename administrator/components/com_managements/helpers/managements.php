<?php

/*
 * @package Managements
 * @com_admininistrations
 * @copyright Copyright (C) Sdrummond, Inc. All rights reserved.
 * @license Sdrummond
 */

// no direct access
defined('_JEXEC') or die;

class ManagementsHelper
{
    /**
     * Configure the Linkbar.
     *
     * @param    string    The name of the active view.
     *
     * @return    void
     * @since    1.6
     */

    public static function addSubmenu($vName)
    {
        JSubMenuHelper::addEntry(
            JText::_('COM_MANAGEMENTS_SUBMENU_CLIENTS'),
            'index.php?option=com_managements&view=clients',
            $vName == 'clients'
        );
        JSubMenuHelper::addEntry(
            JText::_('COM_MANAGEMENTS_SUBMENU_CITIES'),
            'index.php?option=com_managements&view=cities',
            $vName == 'cities'
        );
        JSubMenuHelper::addEntry(
            JText::_('COM_MANAGEMENTS_SUBMENU_CARS'),
            'index.php?option=com_managements&view=cars',
            $vName == 'cars'
        );
        JSubMenuHelper::addEntry(
            JText::_('COM_MANAGEMENTS_SUBMENU_ADVANCEDS_MONEYS'),
            'index.php?option=com_managements&view=advanceds_moneys',
            $vName == 'advanceds_moneys'
        );
        JSubMenuHelper::addEntry(
            JText::_('COM_MANAGEMENTS_SUBMENU_TAX_DELIVERIES'),
            'index.php?option=com_managements&view=tax_deliveries',
            $vName == 'tax_deliveries'
        );
        JSubMenuHelper::addEntry(
            JText::_('COM_MANAGEMENTS_SUBMENU_REPORTS'),
            'index.php?option=com_managements&view=reports',
            $vName == 'reports'
        );
    }


    public static function getActions()
    {
        $user = JFactory::getUser();
        $result = new JObject;
        $assetName = 'com_managements';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }


    public static function getCityOptions()
    {
        // Initialize variables.
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id As value, description As text');
        $query->from('#__cities AS a');
        $query->order('a.description');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        return $options;
    }

    public static function getClientOptions()
    {
        // Initialize variables.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id As value, name As text');
        $query->from('#__clients AS c');
        $query->order('c.name');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        return $options;
    }

    public static function getConsultantOptions()
    {
        // Initialize variables.
        $groupConsultant = 10;

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id As value, name As text');
        $query->from('#__users AS u');
        $query->join('LEFT', '#__user_usergroup_map AS g ON u.id = g.user_id');
        $query->where('g.group_id = ' . $groupConsultant);
        $query->order('u.name');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        return $options;
    }

    public static function getCarOptions()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id AS value, CONCAT(mark, " - ", model, " - ", plate) AS text');
        $query->from('#__cars AS c');
        $query->where('c.published = 1');
        $query->order('c.mark');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        $noCar = [
            'value' => 0,
            'text' => 'Sem Carro',
        ];

        array_unshift($options, $noCar);

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        return $options;
    }

    public static function getConsultantReportOptions()
    {
        // Initialize variables.
        $groupConsultant = 10;

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id As value, name As text');
        $query->from('#__users AS u');
        $query->join('LEFT', '#__user_usergroup_map AS g ON u.id = g.user_id');
        $query->where('g.group_id = ' . $groupConsultant);
        $query->order('u.name');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        $noConsultant = [
            'value' => '',
            'text' => 'Todos',
        ];

        array_unshift($options, $noConsultant);
        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        return $options;
    }
}
