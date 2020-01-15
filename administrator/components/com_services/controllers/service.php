<?php
/**
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controllerform');

class ServicesControllerService extends JControllerForm
{
    protected $text_prefix = 'COM_SERVICES_SERVICE';

    function __construct($config = array())
	{
        // An article edit form can come from the articles or featured view.
        // Adjust the redirect view on the value of 'return' in the request.
        if (JRequest::getCmd('return') == 'featured')
        {
            $this->view_list = 'featured';
            $this->view_item = 'service&return=featured';
        }

		parent::__construct($config);
	}

}