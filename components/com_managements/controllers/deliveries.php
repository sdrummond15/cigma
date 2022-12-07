<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class ManagementsControllerDeliveries extends JControllerForm
{

    public function save($param = null, $param2 = null)
    {

        $mainframe = JFactory::getApplication();

        $db = JFactory::getDbo();

        try {

            // print_r($_POST);die();
            $id = intval($_POST['id']);
            $idRoute = '';
            $msgSuccess = 'Solicitação cadastrada com sucesso.';

            //Identifica se é INSERT ou UPDATE
            if (!empty($id)) {
                $idRoute = '&id=' . $id;
                $msgSuccess = 'Solicitação alterada com sucesso.';
            }

            $delivery = 0;
            if (isset($_POST['date_delivery'])) {
                $date_delivery = trim($_POST['date_delivery']);
                if (!empty($date_delivery)) {
                    $date_delivery = explode('/', $date_delivery);
                    $date_delivery = $date_delivery[2] . '-' . $date_delivery[1] . '-' . $date_delivery[0];
                    $delivery = 1;
                }
            }

            //Buscando usuário que está logado
            $user = JFactory::getUser();
            $id_user = $user->get('id');

            //Buscando a data atual
            $date = new DateTime("now", new DateTimeZone('America/Sao_Paulo'));
            $date_management = $date->format('Y-m-d H:i:s');

            //INSERT
            $object = new stdClass();
            $object->published = 1;
            $object->id_task = $_POST['id_task'];
            $object->id_client = $_POST['client'];
            $object->delivery = $delivery;
            $object->date_delivery = $date_delivery;
            $object->id_consultant = $id_user;
            $object->observation = $_POST['observation'];

            if (empty($id)) {
                $object->created = $date_management;
                $object->created_by = $id_user;
                $result = $db->insertObject('#__tax_deliveries', $object);
                $id = $db->insertid();
            } else {
                $object->modified = $date_management;
                $object->modified_by = $id_user;
                $object->id = $id;
                $result = $db->updateObject('#__tax_deliveries', $object, 'id');
            }

        } catch (Exception $e) {
            // catch any database errors.
            JErrorPage::render($e);
        }

        JFactory::getApplication()->enqueueMessage($msgSuccess, 'success');
        $mainframe->redirect(JRoute::_('index.php?option=com_managements'));
    }

}
