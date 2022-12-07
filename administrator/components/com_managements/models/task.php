<?php

/*
 * @package Managements
 * @com_admininistrations
 * @copyright Copyright (C) Sdrummond, Inc. All rights reserved.
 * @license Sdrummond
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');


class ManagementsModelTask extends JModelAdmin
{

    public function getTable($type = 'Task', $prefix = 'ManagementsTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param    array $data Data for the form.
     * @param    boolean $loadData True if the form is to load its own data (default case), false if not.
     * @return    mixed    A JForm object on success, false on failure
     * @since    1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_managements.task', 'task', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return    mixed    The data for the form.
     * @since    1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_managements.edit.task.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }
        $clients = explode(',', $data->get('id_clients'));
        $data->set('id_clients', $clients);
        $consultants = explode(',', $data->get('id_consultants'));
        $data->set('id_consultants', $consultants);

        return $data;
    }


    public function save($data)
    {

        $deadline = explode('/', $data['deadline']);
        $deadline = $deadline[2] . '-' . $deadline[1] . '-' . $deadline[0];
        $data['deadline'] = $deadline;
        

        if (isset($data['id_clients'])) {
            $data['id_clients'] = implode(',', $data['id_clients']);
        }

        if (isset($data['id_consultants'])) {
            $data['id_consultants'] = implode(',', $data['id_consultants']);
        }

        if (parent::save($data)) {
            return true;
        }

        return false;
    }
}
