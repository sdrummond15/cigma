<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_enterprises
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Enterprises component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_enterprises
 * @since       1.6
 */
class EnterprisesHelper {

    public static function getActions()
        {
            $user = JFactory::getUser();
            $result = new JObject;
            $assetName = 'com_enterprises';
            
            $actions = array(
                'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
            );
            
            foreach ($actions as $action) {
                $result ->set($action, $user->authorise($action,$assetName));
            }
            return $result;
            
        }


    public static function getBairroOptions() {
        // Initialize variables.
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id As value, name As text');
        $query->from('#__bairros');
        $query->order('name');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        return $options;
    }

    public static function getCidadeOptions() {
        // Initialize variables.
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id As value, name As text');
        $query->from('#__cidades');
        $query->order('name');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        return $options;
    }
    
}
