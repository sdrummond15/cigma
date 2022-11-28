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
class ManagementsControllerCities extends JControllerAdmin
{
    /**
     * The prefix to use with controller messages.
     *
     * @var    string
     * @since  1.6
     */
    protected $text_prefix = 'COM_MANAGEMENTS_CIRCUITS';

    /**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JControllerLegacy
	 * @since   1.6
	 */
    public function __construct($config = array())
    {
        parent::__construct($config);

		$this->registerTask('sticky_unpublish', 'sticky_publish');
    }

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JModelLegacy  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'City', $prefix = 'ManagementsModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Stick items
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function sticky_publish()
    {
		// Check for request forgeries.
		$this->checkToken();

		$ids    = $this->input->get('cid', array(), 'array');
		$values = array('sticky_publish' => 1, 'sticky_unpublish' => 0);
		$task   = $this->getTask();
		$value  = ArrayHelper::getValue($values, $task, 0, 'int');

		if (empty($ids)) {
			JError::raiseWarning(500, JText::_('COM_MANAGEMENTS_NO_CITIES_SELECTED'));
		} else {
			// Get the model.
			/** @var ManagementsModelCities $model */
			$model = $this->getModel();

			// Change the state of the records.
			if (!$model->stick($ids, $value)) {
				JError::raiseWarning(500, $model->getError());
			} else {
				if ($value == 1) {
					$ntext = 'COM_MANAGEMENTS_N_CITIES_STUCK';
				} else {
					$ntext = 'COM_MANAGEMENTS_N_CITIES_UNSTUCK';
				}

				$this->setMessage(JText::plural($ntext, count($ids)));
			}
		}

		$this->setRedirect('index.php?option=com_managements&view=cities');
    }
}
