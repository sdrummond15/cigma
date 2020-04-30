<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class ManagementsControllerManagements extends JControllerForm
{

    public function save($param = null, $param2 = null)
    {

        $mainframe = JFactory::getApplication();

        $db = JFactory::getDbo();

        //Criando array de cada conta
        $arrData = [];
        $arrCash = $_POST['cash'];
        $arrCategory = $_POST['category'];
        $arrPayment = $_POST['payment'];
        $arrNF = $_POST['nf'];
        $arrDateExpenses = $_POST['expense_date'];
        $arrDescription = $_POST['description'];

        if (
            !empty(implode('', $arrCash)) ||
            !empty(implode('', $arrNF)) ||
            !empty(implode('', $arrDateExpenses)) ||
            !empty(implode('', $arrDescription))
        ) {
            foreach ($_POST['cash'] as $key => $value) {
                if (!empty($arrCash[$key]) || !empty($arrNF[$key]) || !empty($arrDescription[$key])) {
                    array_push(
                        $arrData,
                        array(
                            'cash' => $arrCash[$key],
                            'category' => $arrCategory[$key],
                            'payment' => $arrPayment[$key],
                            'nf' => $arrNF[$key],
                            'expense_date' => $arrDateExpenses[$key],
                            'description' => $arrDescription[$key]
                        )
                    );
                }
            }
        }

        try {

            $id = intval($_POST['id']);
            $idRoute = '';
            $msgSuccess = 'Solicitação cadastrada com sucesso.';

            //Identifica se é INSERT ou UPDATE
            if (!empty($id)) {
                $idRoute = '&id=' . $id;
                $msgSuccess = 'Solicitação alterada com sucesso.';
            }

            if (isset($_POST['clients'])) {
                $clients = implode(",", $_POST['clients']);
            }

            if (isset($_POST['consultants']) && !empty($_POST['consultants'])) {
                $consultants = implode(",", $_POST['consultants']);
            } else {
                $consultants = "";
            }

            if (isset($_POST['date_in'])) {
                $date_in = trim($_POST['date_in']);
                if (!empty($date_in)) {
                    $date_in = explode('/', $date_in);
                    $date_in = $date_in[2] . '-' . $date_in[1] . '-' . $date_in[0];
                }
            }

            if (isset($_POST['date_out'])) {
                $date_out = trim($_POST['date_out']);
                if (!empty($date_out)) {
                    $date_out = explode('/', $date_out);
                    $date_out = $date_out[2] . '-' . $date_out[1] . '-' . $date_out[0];
                }
            }

            if (isset($_POST['date_in']) && isset($_POST['date_out'])) {
                if (empty($date_in) && !empty($date_out)) {
                    JFactory::getApplication()->enqueueMessage('Favor informar a data de ida.', 'error');
                    $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management' . $idRoute));
                    return false;
                }

                if (!empty($date_in) && !empty($date_out) && $date_in > $date_out) {
                    JFactory::getApplication()->enqueueMessage('Período Inválido', 'error');
                    $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management' . $idRoute));
                    return false;
                }
            }
            $description1 = $_POST['description1'];
            //Buscando usuário que está logado
            $user = JFactory::getUser();
            $id_user = $user->get('id');

            //Buscando a data atual
            $date = new DateTime("now", new DateTimeZone('America/Sao_Paulo'));
            $date_management = $date->format('Y-m-d H:i:s');

            if (isset($_POST['car'])) {
                $car = trim($_POST['car']);
            }

            //INSERT
            $object = new stdClass();
            $object->published = 1;
            $object->id_client = $clients;
            $object->date_in = $date_in;
            $object->date_out = $date_out;
            $object->id_car = $car;
            $object->id_consultant = $id_user;
            $object->id_consultants = $consultants;
            $object->description1 = $description1;

            if (empty($id)) {
                $object->created = $date_management;
                $object->created_by = $id_user;
                $result = $db->insertObject('#__advanced_money', $object);
                $id = $db->insertid();
            } else {
                $object->modified = $date_management;
                $object->modified_by = $id_user;
                $object->id = $id;
                $result = $db->updateObject('#__advanced_money', $object, 'id');
            }

            if (!empty($arrData)) {
                foreach ($arrData as $data) {
                    $object = new stdClass();
                    $object->id_adv_money = $id;

                    $cash = trim($data['cash']);
                    if (!empty($cash)) {
                        $cash = $this->convertMoeda($cash);
                    }


                    $expense_date = trim($data['expense_date']);
                    if (!empty($expense_date)) {
                        $expense_date = explode('/', $expense_date);
                        $expense_date = $expense_date[2] . '-' . $expense_date[1] . '-' . $expense_date[0];
                    }


                    $object->cash = $cash;
                    $object->note = $data['nf'];
                    $object->cat_expenses = $data['category'];
                    $object->cat_payments = $data['payment'];
                    $object->expense_date = $expense_date;
                    $object->description = $data['description'];
                    $object->created = $date_management;
                    $object->created_by = $id_user;
                    $object->published = 1;
                    $result = $db->insertObject('#__accountability_expenses', $object);
                }
            }
        } catch (Exception $e) {
            // catch any database errors.
            JErrorPage::render($e);
        }

        JFactory::getApplication()->enqueueMessage($msgSuccess, 'success');
        $mainframe->redirect(JRoute::_('index.php?option=com_managements'));
    }

    public function delete()
    {

        $mainframe = JFactory::getApplication();

        $db = JFactory::getDbo();

        try {

            $id = JRequest::getVar('id');

            //Verificando se existe o id
            if (!empty($id)) {

                //Verificando se o imóvel pertence ao usuário
                $user = JFactory::getUser();
                $id_user = $user->get('id');

                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('*');
                $query->from('#__managements AS e');
                $query->where('id = ' . $id);
                $query->where('e.created_by = ' . $id_user);
                $db->setQuery($query);
                $results = $db->loadObjectList();

                if (empty($results)) {
                    Factory::getApplication()->enqueueMessage('Este imóvel não existe ou não pertence a esse usuário.', 'error');
                    $mainframe->redirect(JRoute::_('index.php?option=com_managements'));
                }
            } else {
                Factory::getApplication()->enqueueMessage('Não foi possivel encontrar esse imóvel', 'error');
                $mainframe->redirect(JRoute::_('index.php?option=com_managements'));
            }

            $query = $db->getQuery(true);

            $fields = array(
                $db->quoteName('published') . ' = -2'
            );

            $conditions = array(
                $db->quoteName('id') . ' = ' . $id
            );

            $query->update($db->quoteName('#__managements'))->set($fields)->where($conditions);

            $db->setQuery($query);

            $result = $db->execute();
        } catch (Exception $e) {
            // catch any database errors.
            JErrorPage::render($e);
        }

        JFactory::getApplication()->enqueueMessage('Imóvel excluído com sucesso', 'success');
        $mainframe->redirect(JRoute::_('index.php?option=com_managements'));
    }

    public function convertMoeda($valor)
    {
        $preco = substr($valor, 3);
        $preco = str_replace('.', '', $preco);
        $preco = str_replace(',', '.', $preco);
        $preco = number_format($preco, 2, '.', '');
        return $preco;
    }

    public function convertMedidas($medida)
    {
        $medidaAtual = str_replace('.', '', $medida);
        $medidaAtual = str_replace(',', '.', $medidaAtual);
        $medidaAtual = number_format($medidaAtual, 2, '.', '');
        return $medidaAtual;
    }

    public function removeCharacters($string)
    {
        // matriz de entrada
        $what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç', ' ', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º');
        // matriz de saída
        $by = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_');
        // devolver a string
        return strtolower(str_replace($what, $by, $string));
    }

    public function validateImage($image)
    {
        $tamanhoImg = getimagesize($image['tmp_name']);
        $tamanhoImgX = $tamanhoImg[0];
        $tamanhoImgY = $tamanhoImg[1];

        $mimeImg = explode('/', $image['type']);

        if ($mimeImg[0] == 'image' && !empty($tamanhoImgX) && !empty($tamanhoImgY)) {
            return true;
        } else {
            return false;
        }
    }
}
