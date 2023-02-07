<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class ManagementsViewReport_Deliveries extends JViewLegacy
{

    function display($tpl = null)
    {

        $doc = JFactory::getDocument();
        $doc->addStyleSheet('components/com_managements/assets/css/report_deliveries.css');
        $doc->addStyleSheet('components/com_managements/assets/css/select2.min.css');
        $doc->addCustomTag('<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>');
        $doc->addScript('components/com_managements/assets/js/jqueryUi.js');
        $doc->addScript('components/com_managements/assets/js/report_deliveries.js');
        $doc->addScript('components/com_managements/assets/js/jquery.maskedinput.min.js');
        $doc->addScript('components/com_managements/assets/js/jquery.maskMoney.js');
        $doc->addScript('components/com_managements/assets/js/select2.min.js');
        $doc->addScript('components/com_managements/assets/js/jquery.easing.min.js');
        $doc->addScript('components/com_managements/assets/js/jspdf.min.js');
        $doc->addScript('components/com_managements/assets/js/jspdf.plugin.autotable.js');

        $this->taxDeliveries = $this->get('TaxDeliveries');
        $this->tasks = $this->get('Tasks');
        $this->management = $this->get('Management');
        $this->clients = $this->get('Clients');
        $this->consultants = $this->get('Consultants');

        parent::display($tpl);
    }
}
