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
class ManagementsControllerReports extends JControllerAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_MANAGEMENTS_REPORTS';

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


	public function getModel($name = 'Reports', $prefix = 'ManagementsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	public function gerarRelatorio()
	{
		$model = $this->getModel();
		$result = $model->getReport(JRequest::getVar('jform'));

		//echo '<pre>';
		//print_r($result);
		//  $tabela = 'Nenhum resultado encontrado!';
		//  if (!empty($result)) {
		//  	$tabela = '<div id="relatorio">';
		//  	$tabela .= '<h2>Relatório</h2>';
		//  	$tabela .= '<table class="table table-striped" id="articleList">';
		//  	$tabela .= '<thead>';
		//  	$tabela .= '<tr>';
		//  	$tabela .= '<th>Consultor</th>';
		//  	$tabela .= '<th>Carro</th>';
		//  	$tabela .= '<th>Adiantamento</th>';
		//  	$tabela .= '<th>Pendência</th>';
		//  	$tabela .= '<th>Ida</th>';
		//  	$tabela .= '<th>Volta</th>';
		//  	$tabela .= '<th>Cliente(s)</th>';
		//  	$tabela .= '</tr>';
		//  	$tabela .= '</thead>';
		//  	$tabela .= '<tbody>';
		//  	foreach ($result as $r) {
		//  		$tabela .= '<tr>';
		//  		$tabela .= '<td>' . $r->name_consultant . '</td>';
		//  		$tabela .= '<td>' . $r->car . '</td>';
		//  		$tabela .= '<td>' . $this->price($r->cash) . '</td>';
		//  		$tabela .= '<td class="center">' . $r->pendency . '</td>';
		//  		$tabela .= '<td class="center">' . $this->inverteData($r->date_in) . '</td>';
		//  		$tabela .= '<td class="center">' . $this->inverteData($r->date_out) . '</td>';
		//  		$tabela .= '<td width="30%">' . $this->getClient($r->clients) . '</td>';
		//  		$tabela .= '</tr>';
		//  	}
		//  	$tabela .= '</tbody>';
		//  	$tabela .= '</table>';
		//  	$tabela .= '</div>';
		//  	$tabela .= '<div>
		//  					<button type="button" class="btn btn-danger btn-pdf">
		//  						<span class="icon-download" aria-hidden="true"></span>
		//  							Gerar PDF
		//  					</button>
		//  				</div>';
		//  }

		$array_result = array();

		foreach ($result as $i => $r) {
			$array_result[$i]['name'] = $r->name_consultant;
			$array_result[$i]['car'] = $r->car;
			$array_result[$i]['cash'] = $this->price($r->cash);
			$array_result[$i]['pendency'] = $r->pendency;
			$array_result[$i]['date_in'] = $this->inverteData($r->date_in);
			$array_result[$i]['date_out'] = $this->inverteData($r->date_out);
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

	public function price($cash)
	{
		$preco = 'R$ ' . number_format($cash, 2, ',', '.');
		return $preco;
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
