<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class ManagementsViewManagement extends JViewLegacy {

    function display($tpl = null) {

        $doc = JFactory::getDocument();
        $doc->addStyleSheet('components/com_managements/assets/css/management.css');
        $doc->addStyleSheet('components/com_managements/assets/css/select2.min.css');
        $doc->addCustomTag('<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>');
        $doc->addScript('components/com_managements/assets/js/jqueryUi.js');
        $doc->addScript('components/com_managements/assets/js/management.js');
        $doc->addScript('components/com_managements/assets/js/jquery.maskedinput.min.js');
        $doc->addScript('components/com_managements/assets/js/jquery.maskMoney.js');
        $doc->addScript('components/com_managements/assets/js/select2.min.js');
        $doc->addScript('components/com_managements/assets/js/jquery.easing.min.js');

        $this->check = $this->get('Check');
        $this->management = $this->get('Management');
        $this->clients = $this->get('Clients');
        $this->categories = $this->get('Categories');
        $this->cars = $this->get('Cars');
        $this->advanceds_money = $this->get('AdvancedsMoney');

        parent::display($tpl);
    }
}
