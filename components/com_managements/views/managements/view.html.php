<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
JPluginHelper::importPlugin('content.joomplu');

class ManagementsViewManagements extends JViewLegacy {

    function display($tpl = null) {

        $doc = JFactory::getDocument();
        $doc->addStyleSheet('components/com_managements/assets/css/managements.css');
        $doc->addStyleSheet('components/com_managements/assets/css/select2.min.css');
        $doc->addScript('components/com_managements/assets/js/managements.js');
        $doc->addScript('components/com_managements/assets/js/jquery.maskcpfcnpj.js');
        $doc->addScript('components/com_managements/assets/js/select2.min.js');

        $this->managements = $this->get('Managements');
        $this->clientslist = $this->get('ClientsList');
        $this->clients = $this->get('Clients');

        parent::display($tpl);
    }
}
