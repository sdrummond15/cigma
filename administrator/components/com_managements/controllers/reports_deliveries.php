<?php

/*
 * @package Managements
 * @com_admininistrations
 * @copyright Copyright (C) Sdrummond, Inc. All rights reserved.
 * @license Sdrummond
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Clients list controller class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_banners
 * @since		1.6
 */
class ManagementsControllerReports_Deliveries extends JControllerAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_MANAGEMENTS_REPORTS_DELIVERIES';

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.6
	 */

	public function __construct($config = array())
	{
		parent::__construct($config);
	}


	public function getModel($name = 'Reports_Deliveries', $prefix = 'ManagementsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	public function gerarRelatorio()
	{
		$model = $this->getModel();
		$result = $model->getReport(JRequest::getVar('jform'));
		$array_result = array();

		foreach ($result as $i => $r) {
			$array_result[$i]['desc_task'] = $r->desc_task;
			$array_result[$i]['name'] = $r->name_consultant;
			$array_result[$i]['observation'] = $r->observation;
			$array_result[$i]['date_delivery'] = $this->inverteData($r->date_delivery);
			$array_result[$i]['clients'] = $this->getClient($r->clients);
		}

		echo json_encode($array_result);
		exit;
	}

	public function getClient($ids)
	{
		$model = $this->getModel();
		$result = $model->getClients($ids);

		$clients = array();
		foreach ($result as $r) {
			array_push($clients, $r->name);
		}
		$listclients = implode(', ', $clients);

		return $listclients;
	}

	public function inverteData($data)
	{
		if ($data == '0000-00-00') {
			$rdata = ' -- ';
		} else {
			$data = explode('-', $data);
			$rdata = $data[2] . '/' . $data[1] . '/' . $data[0];
		}
		return $rdata;
	}
}
