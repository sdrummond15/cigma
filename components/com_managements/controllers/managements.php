<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
require(JPATH_LIBRARIES . '/phpcrop/WideImage.php');

class ManagementsControllerManagements extends JControllerForm
{

    public function save()
    {

        $mainframe = JFactory::getApplication();

        $db = JFactory::getDbo();

        try {

            $id = intval($_POST['id']);
            $idRoute = '';
            $msgSuccess = 'Imóvel cadastrado com sucesso. <b>Aguarde a aprovação.</b>';

            //Identifica se é INSERT ou UPDATE
            if(!empty($id)){
                $idRoute = '&id='.$id;
                $msgSuccess = 'Imóvel alterado com sucesso. <b>Aguarde a aprovação.</b>';
            }

            //Valida Campos
            if (!isset($_POST['type']) || empty($_POST['type'])) {
                JFactory::getApplication()->enqueueMessage('Tipo de Imóvel Inválido', 'error');
                $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                return false;
            }

            if (!isset($_POST['business']) || empty($_POST['business'])) {
                JFactory::getApplication()->enqueueMessage('Tipo de Negócio Inválido', 'error');
                $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                return false;
            }

            if (in_array(1, $_POST['business']) && empty(trim($_POST['sale_price']))) {
                JFactory::getApplication()->enqueueMessage('Valor de venda Inválido', 'error');
                $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                return false;
            }

            if (in_array(2, $_POST['business']) && empty(trim($_POST['rent_price']))) {
                JFactory::getApplication()->enqueueMessage('Valor de aluguel Inválido', 'error');
                $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                return false;
            }

            if (in_array(3, $_POST['business']) && empty(trim($_POST['sharing_price']))) {
                JFactory::getApplication()->enqueueMessage('Valor de partilhamento Inválido', 'error');
                $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                return false;
            }

            if (empty(trim($_POST['market_value']))) {
                JFactory::getApplication()->enqueueMessage('Valor Venal Inválido', 'error');
                $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                return false;
            }

            if ($_POST['rented'] == 1 && empty(trim($_POST['date_rented']))) {
                JFactory::getApplication()->enqueueMessage('Data de Locação Inválido', 'error');
                $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                return false;
            }

            if (!isset($_POST['street']) || empty($_POST['street'])) {
                JFactory::getApplication()->enqueueMessage('Logradouro Inválido', 'error');
                $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                return false;
            }

            if (!isset($_POST['number']) || empty($_POST['number'])) {
                JFactory::getApplication()->enqueueMessage('Número Inválido', 'error');
                $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                return false;
            }

            if (!isset($_POST['district']) || empty($_POST['district'])) {
                JFactory::getApplication()->enqueueMessage('Bairro Inválido', 'error');
                $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                return false;
            }

            if (!isset($_POST['state']) || empty($_POST['state'])) {
                JFactory::getApplication()->enqueueMessage('Estado Inválido', 'error');
                $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                return false;
            }

            if (!isset($_POST['city']) || empty($_POST['city'])) {
                JFactory::getApplication()->enqueueMessage('Cidade Inválida', 'error');
                $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                return false;
            }

            if (!isset($_POST['cep']) || empty($_POST['cep'])) {
                JFactory::getApplication()->enqueueMessage('CEP Inválido', 'error');
                $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                return false;
            }

            if ($_POST['energy'] == 3 && empty(trim($_POST['number_energy']))) {
                JFactory::getApplication()->enqueueMessage('Número da Energia Elétrica Inválido', 'error');
                $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                return false;
            }

            if ($_POST['energy'] == 3 && empty(trim($_POST['clock_energy']))) {
                JFactory::getApplication()->enqueueMessage('Número do Relógio de Energia Inválido', 'error');
                $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                return false;
            }

            if ($_POST['water'] == 3 && empty(trim($_POST['number_water']))) {
                JFactory::getApplication()->enqueueMessage('Número da Água Inválido', 'error');
                $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                return false;
            }

            if ($_POST['water'] == 3 && empty(trim($_POST['clock_water']))) {
                JFactory::getApplication()->enqueueMessage('Número do Hidrômetro de Água Inválido', 'error');
                $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                return false;
            }

            if (empty($_POST['owner_id'])) {
                JFactory::getApplication()->enqueueMessage('Vincule pelo menos um cliente ao imóvel', 'error');
                $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                return false;
            }

            //Verifico se existe Capa
            $existCoverImage = false;
            if (!empty($_FILES['cover_image']['name'])) {
                $existCoverImage = true;
            }

            if(empty($id)){
                if (empty($_FILES['cover_image']['name']) && $_FILES['cover_image']['error'] != 0) {
                    JFactory::getApplication()->enqueueMessage('Capa Inválida', 'error');
                    $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                    return false;
                }
            }


            //Verifico se existe Fotos
            $existPhoto = false;
            foreach ($_FILES['photo']['name'] as $file) {
                if (!empty($file)) {
                    $existPhoto = true;
                }
            }

            //verifico se não existe erro nas Fotos
            foreach ($_FILES['photo']['error'] as $error) {
                if ($existPhoto === true && !empty($error)) {
                    JFactory::getApplication()->enqueueMessage('Foto(s) Inválida(s)', 'error');
                    $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                    return false;
                }
            }

            $type = $_POST['type'];
            $business = implode(", ", $_POST['business']);

            $sale_price = trim($_POST['sale_price']);
            if (!empty($sale_price))
                $sale_price = $this->convertMoeda($sale_price);

            $rent_price = trim($_POST['rent_price']);
            if (!empty($rent_price))
                $rent_price = $this->convertMoeda($rent_price);

            $sharing_price = trim($_POST['sharing_price']);
            if (!empty($sharing_price))
                $sharing_price = $this->convertMoeda($sharing_price);

            $rented = $_POST['rented'];

            $date_rented = trim($_POST['date_rented']);
            if (!empty($date_rented)) {
                $date_rented = explode('/', $date_rented);
                $date_rented = $date_rented[2] . '-' . $date_rented[1] . '-' . $date_rented[0];
            }

            $iptu = trim($_POST['iptu']);
            if (!empty($iptu))
                $iptu = $this->convertMoeda($iptu);

            $condominium = trim($_POST['condominium']);
            if (!empty($condominium))
                $condominium = $this->convertMoeda($condominium);

            $safe_bail = trim($_POST['safe_bail']);
            if (!empty($safe_bail))
                $safe_bail = $this->convertMoeda($safe_bail);

            $safe_fire = trim($_POST['safe_fire']);
            if (!empty($safe_fire))
                $safe_fire = $this->convertMoeda($safe_fire);

            $market_value = trim($_POST['market_value']);
            if (!empty($market_value))
                $market_value = $this->convertMoeda($market_value);

            $owner_id = $_POST['owner_id'];
            if (!empty($owner_id)) {
                $owner_id = implode(', ', $_POST['owner_id']);
            }
            $animal = $_POST['animal'];
            $anunciar = $_POST['announced'];
            $street = trim($_POST['street']);
            $number = trim($_POST['number']);
            $complement = trim($_POST['complement']);
            $district = trim($_POST['district']);
            $region = trim($_POST['region']);

            $state = $_POST['state'];
            $db = JFactory::getDbo();
            $queryState = $db->getQuery(true);
            $queryState->select('*');
            $queryState->from('#__state');
            $queryState->where('abbreviation = "' . $state . '"');
            $db->setQuery($queryState);
            $resultState = $db->loadObjectList();
            $idState = $resultState[0]->id;

            $city = $_POST['city'];
            $db = JFactory::getDbo();
            $queryState = $db->getQuery(true);
            $queryState->select('*');
            $queryState->from('#__city');
            $queryState->where('description = "' . $city . '"');
            $db->setQuery($queryState);
            $resultCity = $db->loadObjectList();
            if(!empty($resultCity)){
                $idCity = $resultCity[0]->id;
            }else{
                $query = $db->getQuery(true);
                $columns = array('id_state', 'description');
                $values = array($db->quote($idState), $db->quote($city));
                $query
                    ->insert($db->quoteName('#__city'))
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $values));
                $db->setQuery($query);
                $result = $db->execute();
                $idCity = $db->insertid();
            }

            $cep = trim($_POST['cep']);
            $suites = trim($_POST['suites']);
            $bedrooms = trim($_POST['bedrooms']);
            $bathrooms = trim($_POST['bathrooms']);
            $rooms = trim($_POST['rooms']);
            $garage = trim($_POST['garage']);
            $garage_desc = trim($_POST['garage_desc']);

            $total_area = trim($_POST['total_area']);
            if (!empty($total_area))
                $total_area = $this->convertMedidas($total_area);

            $building_area = trim($_POST['building_area']);
            if (!empty($building_area))
                $building_area = $this->convertMedidas($building_area);

            $description = trim($_POST['description']);
            $video = trim($_POST['video']);
            $registry = trim($_POST['registry']);
            $registry_office = trim($_POST['registry_office']);
            $iptu_register = trim($_POST['iptu_register']);
            $energy = $_POST['energy'];
            $number_energy = trim($_POST['number_energy']);
            $clock_energy = trim($_POST['clock_energy']);
            $water = $_POST['water'];
            $number_water = trim($_POST['number_water']);
            $clock_water = trim($_POST['clock_water']);
            $gate_keys = trim($_POST['gate_keys']);
            $gate_controls = trim($_POST['gate_controls']);
            $security_alarm = trim($_POST['security_alarm']);

            $user = JFactory::getUser();
            $id_user = $user->get('id');

            $date = new DateTime("now", new DateTimeZone('America/Sao_Paulo'));
            $date_management = $date->format('Y-m-d H:i:s');

            //INSERT e UPDATE
            $object = new stdClass();
            $object->owner_id = $owner_id;
            $object->type = $type;
            $object->business = $business;
            $object->sale_price = $sale_price;
            $object->rent_price = $rent_price;
            $object->sharing_price = $sharing_price;
            $object->announced = $anunciar;
            $object->rented = $rented;
            $object->date_rented = $date_rented;
            $object->iptu = $iptu;
            $object->safe_bail = $safe_bail;
            $object->safe_fire = $safe_fire;
            $object->market_value = $market_value;
            $object->condominium = $condominium;
            $object->animal = $animal;
            $object->cep = $cep;
            $object->state = $idState;
            $object->city = $idCity;
            $object->region = $region;
            $object->district = $district;
            $object->street = $street;
            $object->complement = $complement;
            $object->number = $number;
            $object->suites = $suites;
            $object->bedrooms = $bedrooms;
            $object->bathrooms = $bathrooms;
            $object->rooms = $rooms;
            $object->garage = $garage;
            $object->garage_desc = $garage_desc;
            $object->total_area = $total_area;
            $object->building_area = $building_area;
            $object->video = $video;
            $object->description = $description;
            $object->registry = $registry;
            $object->registry_office = $registry_office;
            $object->iptu_register = $iptu_register;
            $object->energy = $energy;
            $object->number_energy = $number_energy;
            $object->clock_energy = $clock_energy;
            $object->water = $water;
            $object->number_water = $number_water;
            $object->clock_water = $clock_water;
            $object->gate_keys = $gate_keys;
            $object->gate_controls = $gate_controls;
            $object->security_alarm = $security_alarm;
            $object->published = 0;

            if(!empty($id)){
                $object->modified = $date_management;
                $object->modified_by = $id_user;
                $object->id = $id;
                $result = $db->updateObject('#__managements', $object, 'id');
            }else{
                $object->created = $date_management;
                $object->created_by = $id_user;
                $result = $db->insertObject('#__managements', $object);
                $id = $db->insertid();
            }


            ///////////////// INSERINDO IMAGEM CAPA /////////////////////
            if ($existCoverImage === true) {

                $cover_image = $_FILES['cover_image'];

                if (!JFolder::exists(JPATH_SITE . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "imoveis" . DIRECTORY_SEPARATOR . $id)) {
                    JFolder::create(JPATH_SITE . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "imoveis" . DIRECTORY_SEPARATOR . $id);
                }

                $capa = $this->removeCharacters($cover_image['name']);
                $filename = JFile::makeSafe($cover_image['name']);
                $dest = JPATH_SITE . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "imoveis" . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $this->removeCharacters($cover_image['name']);
                $capaImage = JPATH_SITE . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "imoveis" . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'comprarealugar_imovel_' . $id . '.jpg';
                if (empty($cover_image['error'])) {
                    if ($this->validateImage($cover_image) == true) {
                        JFile::upload($cover_image['tmp_name'], $dest);

                        //Diminui e corta quadrado imagem
                        $image = WideImage::load($dest);
                        $resized = $image->resize(450, 450, 'outside');
                        $resized = $resized->crop('center', 'center', 450, 450);
                        $resized->saveToFile($capaImage, 80);
                    } else {
                        JFactory::getApplication()->enqueueMessage('Imagen(s) Inválida(s)', 'error');
                        $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                        return false;
                    }
                }

                $capaImg = "images/imoveis/" . $id . '/comprarealugar_imovel_' . $id . '.jpg';

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('*');
                $query->from('#__managements');
                $query->where('id = ' . $id);
                $query->where('cover_image LIKE "%' . $capaImg . '%"');
                $db->setQuery($query);
                $resultCapa = $db->loadObjectList();

                if (empty($resultCapa)) {
                    $db = JFactory::getDbo();

                    $query = $db->getQuery(true);

                    $fields = array(
                        $db->quoteName('cover_image') . ' = ' . $db->quote($capaImg)
                    );

                    $conditions = array(
                        $db->quoteName('id') . ' = ' . $db->quote($id)
                    );

                    $query->update($db->quoteName('#__managements'))->set($fields)->where($conditions);

                    // Set the query using our newly populated query object and execute it.
                    $db->setQuery($query);

                    $result = $db->execute();

                }

                $firstPhoto = "images/imoveis/" . $id . '/' . $capa;

                $db = JFactory::getDbo();
                $queryFirstPhoto = $db->getQuery(true);
                $queryFirstPhoto->select('*');
                $queryFirstPhoto->from('#__management_photos');
                $queryFirstPhoto->where('id_management = ' . $id);
                $queryFirstPhoto->where('photo LIKE "%' . $firstPhoto . '%"');
                $db->setQuery($queryFirstPhoto);
                $resultFirstPhoto = $db->loadObjectList();

                if (empty($resultFirstPhoto)) {
                    $queryInsertPhoto = $db->getQuery(true);

                    $columns = array(
                        'id_management',
                        'photo'
                    );

                    // Insert values.
                    $values = array(
                        $db->quote($id),
                        $db->quote($firstPhoto)
                    );

                    // Prepare the insert query.
                    $queryInsertPhoto
                        ->insert($db->quoteName('#__management_photos'))
                        ->columns($db->quoteName($columns))
                        ->values(implode(',', $values));

                    // Set the query using our newly populated query object and execute it.
                    $db->setQuery($queryInsertPhoto);

                    $result = $db->execute();

                }


            }
            ///////////////// INSERINDO IMAGEM CAPA /////////////////////

            //Inserindo photos no Imóvel se existir
            if ($existPhoto === true) {

                $photos = $_FILES['photo'];

                if (!JFolder::exists(JPATH_SITE . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "imoveis" . DIRECTORY_SEPARATOR . $id)) {
                    JFolder::create(JPATH_SITE . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "imoveis" . DIRECTORY_SEPARATOR . $id);
                }

                for ($k = 0; $k < sizeof($photos['name']); $k++) {

                    $tamanhoImg = getimagesize($photos['tmp_name'][$k]);
                    $tamanhoImgX = $tamanhoImg[0];
                    $tamanhoImgY = $tamanhoImg[1];

                    $mimeImg = explode('/', $photos['type'][$k]);

                    if($mimeImg[0] == 'image' && !empty($tamanhoImgX) && !empty($tamanhoImgY)){
                        $returnFoto = true;
                    }else{
                        $returnFoto =  false;
                    }

                    $imagem[] = $this->removeCharacters($photos['name'][$k]);
                    $filename = JFile::makeSafe($photos['name'][$k]);
                    $dest = JPATH_SITE . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "imoveis" . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $this->removeCharacters($photos['name'][$k]);
                    if (empty($photos['error'][$k])) {
                        if ($returnFoto == true) {
                            JFile::upload($photos['tmp_name'][$k], $dest);

                            //Comparando a largura e altura da Imagem
                            if($tamanhoImgX >= $tamanhoImgY){
                                if($tamanhoImgX > 1000){
                                    $tamImgX = 1000;
                                }else{
                                    $tamImgX = $tamanhoImgX;
                                }
                                $tamImgY = null;
                            }else{
                                if($tamanhoImgY > 1000){
                                    $tamImgY = 1000;
                                }else{
                                    $tamImgY = $tamanhoImgY;
                                }
                                $tamImgX = null;
                            }

                            //Redimensionando a imagem para salvar
                            $image = WideImage::load($dest);
                            $resized = $image->resize($tamImgX, $tamImgY, 'outside');
                            $resized->saveToFile($dest, 90);

                        } else {
                            JFactory::getApplication()->enqueueMessage('Imagen(s) Inválida(s)', 'error');
                            $mainframe->redirect(JRoute::_('index.php?option=com_managements&view=management'.$idRoute));
                            return false;
                        }
                    }
                }

                foreach ($imagem as $img) {

                    $photo = "images/imoveis/" . $id . '/' . $img;

                    $queryPhoto = $db->getQuery(true);
                    $queryPhoto->select('*');
                    $queryPhoto->from('#__management_photos');
                    $queryPhoto->where('id_management = ' . $id);
                    $queryPhoto->where('photo LIKE "%' . $img . '%"');
                    $db->setQuery($queryPhoto);
                    $resultPhoto = $db->loadObjectList();

                    if (empty($resultPhoto)) {

                        $queryInsertPhoto = $db->getQuery(true);

                        $columns = array(
                            'id_management',
                            'photo'
                        );

                        // Insert values.
                        $values = array(
                            $db->quote($id),
                            $db->quote($photo)
                        );

                        // Prepare the insert query.
                        $queryInsertPhoto
                            ->insert($db->quoteName('#__management_photos'))
                            ->columns($db->quoteName($columns))
                            ->values(implode(',', $values));

                        // Set the query using our newly populated query object and execute it.
                        $db->setQuery($queryInsertPhoto);

                        $result = $db->execute();
                    }

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

    public function validateImage($image){
        $tamanhoImg = getimagesize($image['tmp_name']);
        $tamanhoImgX = $tamanhoImg[0];
        $tamanhoImgY = $tamanhoImg[1];

        $mimeImg = explode('/', $image['type']);

        if($mimeImg[0] == 'image' && !empty($tamanhoImgX) && !empty($tamanhoImgY)){
            return true;
        }else{
            return false;
        }
    }
}

