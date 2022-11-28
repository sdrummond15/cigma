<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_managements
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('ManagementsHelper', JPATH_ADMINISTRATOR . '/components/com_managements/helpers/clients.php');

/**
 * View to edit a client.
 *
 * @since  1.5
 */
class ManagementsViewClient extends JViewLegacy
{
	/**
	 * The JForm object
	 *
	 * @var  JForm
	 */
	protected $form;

	/**
	 * The active item
	 *
	 * @var  object
	 */
	protected $item;

	/**
	 * The model state
	 *
	 * @var  object
	 */
	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		// Initialiase variables.
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
		}

		$doc = JFactory::getDocument();
		$doc->addStyleSheet('components/com_managements/assets/css/backend.css');

		$this->addToolbar();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user       = JFactory::getUser();
		$userId     = $user->id;
		$isNew      = ($this->item->id == 0);
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

		JToolbarHelper::title($isNew ? JText::_('COM_MANAGEMENTS_CLIENT_NEW') : JText::_('COM_MANAGEMENTS_CLIENT_EDIT'), 'clients');

		// If not checked out, can save the item.
		if (!$checkedOut) {
			JToolbarHelper::apply('client.apply');
			JToolbarHelper::save('client.save');
		}

		// If an existing item, can save to a copy.
		if (!$isNew) {
			JToolbarHelper::save2copy('client.save2copy');
		}

		if (empty($this->item->id)) {
			JToolbarHelper::cancel('client.cancel');
		} else {
			JToolbarHelper::cancel('client.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_COMPONENTS_MANAGEMENTS_CLIENTS_EDIT');
	}
}
