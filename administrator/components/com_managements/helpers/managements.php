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
            $vName == 'citie'
        );
        JSubMenuHelper::addEntry(
            JText::_('COM_MANAGEMENTS_SUBMENU_CARS'),
            'index.php?option=com_managements&view=cars',
            $vName == 'cars'
        );
        JSubMenuHelper::addEntry(
            JText::_('COM_MANAGEMENTS_SUBMENU_ADVANCEDS_MONEY'),
            'index.php?option=com_managements&view=advanceds_money',
            $vName == 'advanceds_money'
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

    public static function getPilotOptions()
    {
        // Initialize variables.
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id As value, name As text');
        $query->from('#__pilots AS a');
        $query->order('a.name');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        // Merge any additional options in the XML definition.
        //$options = array_merge(parent::getOptions(), $options);

        array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_MANAGEMENTS_NO_PILOT')));

        return $options;
    }

    public static function getPilotResultOptions()
    {
        // Initialize variables.
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id As value, name As text');
        $query->from('#__pilots AS a');
        $query->where('published = 1');
        $query->order('a.name');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        // Merge any additional options in the XML definition.
        //$options = array_merge(parent::getOptions(), $options);

        return $options;
    }

    public static function getChampionshipOptions()
    {
        // Initialize variables.
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id As value, title As text');
        $query->from('#__championships AS a');
        $query->order('a.title');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        // Merge any additional options in the XML definition.
        //$options = array_merge(parent::getOptions(), $options);

        return $options;
    }

    public static function getCircuitOptions()
    {
        // Initialize variables.
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id As value, title As text');
        $query->from('#__circuits AS a');
        $query->order('a.title');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        // Merge any additional options in the XML definition.
        //$options = array_merge(parent::getOptions(), $options);

        return $options;
    }

    public static function getStageOptions()
    {
        // Initialize variables.
        $options = array();

        $ano = date("Y");

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('s.id As value, s.title As text');
        $query->from('#__stages AS s');
        $query->where('s.id_championship = (SELECT id FROM #__championships WHERE year = '.$ano.')');
        //   $query->order('s.title');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        // Merge any additional options in the XML definition.
        //$options = array_merge(parent::getOptions(), $options);

        return $options;
    }

    public static function getTeamOptions()
    {
        // Initialize variables.
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id As value, title As text');
        $query->from('#__teams AS a');
        $query->order('a.title');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        // Merge any additional options in the XML definition.
        //$options = array_merge(parent::getOptions(), $options);

        array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_MANAGEMENTS_NO_TEAM')));

        return $options;
    }

    public static function getPilotGridOptions()
    {
        // Initialize variables.
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id As value, name As text');
        $query->from('#__pilots AS a');
        $query->where('published = 1');
        $query->order('a.name');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        // Merge any additional options in the XML definition.
        //$options = array_merge(parent::getOptions(), $options);

        array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_MANAGEMENTS_NO_PILOT')));

        return $options;
    }

    public static function getStageGridOptions()
    {
        // Initialize variables.
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $ano = date("Y");
        $query->select('id As value, title As text');
        $query->from('#__stages AS a');
        $query->where('a.id_championship = (SELECT id FROM #__championships WHERE year = '.$ano.')');
        $query->order('a.id');
        $query->group('a.title');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        // Merge any additional options in the XML definition.
        //$options = array_merge(parent::getOptions(), $options);

        return $options;
    }

    public static function getPilotPreviouOptions()
    {
        // Initialize variables.
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id As value, name As text');
        $query->from('#__pilots AS a');
        $query->order('a.name');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        // Merge any additional options in the XML definition.
        //$options = array_merge(parent::getOptions(), $options);

        return $options;
    }

}