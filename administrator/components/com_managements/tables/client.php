<?php

/*
 * @package Managements
 * @com_admininistrations
 * @copyright Copyright (C) Sdrummond, Inc. All rights reserved.
 * @license Sdrummond
 */

// no direct access
defined('_JEXEC') or die;

class ManagementsTableClient extends JTable
{
    /**
     * Constructor
     *
     * @since    2.5
     */
    function __construct(&$_db)
    {
        parent::__construct('#__clients', 'id', $_db);
    }


    public function bind($array, $ignore = '')
    {
        if (isset($array['params']) && is_array($array['params'])) {
            $registry = new JRegistry();
            $registry->loadArray($array['params']);
            $array['params'] = (string)$registry;

        }

        return parent::bind($array, $ignore);
    }


    public function check()
    {
        return true;
    }

    public function store($updateNulls = false)
    {
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        if (empty($this->id)) {
            $this->modified = $date->toSQL();
            $this->modified_by = $user->get('id');
            $this->created = $date->toSQL();
            $this->created_by = $user->get('id');
        } else {
            if (!intval($this->created)) {
                $this->created = $date->toSQL();
            }
            if (empty($this->created_by)) {
                $this->created_by = $user->get('id');
            }
        }

        $table = JTable::getInstance('Client', 'ManagementsTable', array('dbo' => $this->_db));

        return parent::store($updateNulls);
    }


}
