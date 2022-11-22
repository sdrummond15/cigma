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


class ManagementsModelTax_Delivery extends JModelAdmin
{

    public function getTable($type = 'Tax_Delivery', $prefix = 'ManagementsTable', $config = array())
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
        $form = $this->loadForm('com_managements.tax_delivery', 'tax_delivery', array('control' => 'jform', 'load_data' => $loadData));
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
        $data = JFactory::getApplication()->getUserState('com_managements.edit.tax_delivery.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }
        $business = explode(',', $data->get('id_client'));
        $data->set('id_client', $business);

//        $date_in = explode('-',$data->get('date_in'));
//        $date_in = $date_in[2].'-'.$date_in[1].'-'.$date_in[0];
//        $data['date_in'] = $date_in;
//
//        $date_out = explode('-',$data->get('date_out'));
//        $date_out = $date_out[2].'-'.$date_out[1].'-'.$date_out[0];
//        $data['date_out'] = $date_out;

        return $data;
    }


    public function save($data)
    {
        if (isset($data['cash'])) {
            $preco = substr($data['cash'], 3);
            $preco = str_replace('.', '', $preco);
            $preco = str_replace(',', '.', $preco);
            $preco = number_format($preco, 2, '.', '');
            $data['cash'] = $preco;
        }
        
        if (isset($data['id_client'])) {
            $data['id_client'] = implode(',', $data['id_client']);
        }

        if (parent::save($data)) {
            return true;
        }

        return false;
    }

    public static function getAccounts($id_adv_money = '')
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
