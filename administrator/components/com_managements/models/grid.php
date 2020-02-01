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


class ManagementsModelGrid extends JModelAdmin
{
	
	public function getTable($type = 'Grid', $prefix = 'ManagementsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_managements.grid', 'grid', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */

    public function save($data)
    {

        if(!empty($_POST['positions'])){
            $position = implode(',',$_POST['positions']);
            $data['positions'] = $position;
        }

        if (parent::save($data)) {

            return true;
        }


        return false;
    }

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_managements.edit.grid.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

    public function getPilots()
    {
        $options = array();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id, name');
        $query->from('#__pilots');
        $query->where('published = 1');
        $query->order('name');
        $db->setQuery($query);
        $options = $db->loadObjectList();
        return $options;
    }

}
