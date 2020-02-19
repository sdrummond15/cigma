<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_enterprises
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Enterprise model.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_enterprises
 * @since       1.6
 */

jimport('joomla.application.component.modeladmin');
jimport('joomla.filesystem.file');
require(JPATH_LIBRARIES . '/phpcrop/WideImage.php');

class EnterprisesModelEnterprise extends JModelAdmin
{

    public function getTable($type = 'Enterprise', $prefix = 'EnterprisesTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_enterprises.enterprise', 'enterprise', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        $jinput = JFactory::getApplication()->input;

        // The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
        if ($jinput->get('a_id')) {
            $id = $jinput->get('a_id', 0);
        } // The back end uses id so we use that the rest of the time and set it to 0 by default.
        else {
            $id = $jinput->get('id', 0);
        }

        if ($id) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__enterprise_photos');
            $query->where('id_enterprise = ' . $id);
            $db->setQuery($query);
            $results = $db->loadObjectList();

            $array = array();
            foreach ($results as $result) {
                $images['id'] = $result->id;
                $images['photo'] = $result->photo;
                $array[] = $images;
            }

            $form->photo = $array;
        }


        //(Destaque (Wanderson 19/09/13))
        $user = JFactory::getUser();

        // Check for existing article.
        // Modify the form based on Edit State access controls.
        if ($id != 0 && (!$user->authorise('core.edit.state', 'com_enterprises.enterprise.' . (int)$id))
            || ($id == 0 && !$user->authorise('core.edit.state', 'com_enterprises'))
        ) {
            // Disable fields for display.
            $form->setFieldAttribute('featured', 'disabled', 'true');
            $form->setFieldAttribute('ordering', 'disabled', 'true');
            $form->setFieldAttribute('publish_up', 'disabled', 'true');
            $form->setFieldAttribute('publish_down', 'disabled', 'true');
            $form->setFieldAttribute('published', 'disabled', 'true');

            // Disable fields while saving.
            // The controller has already verified this is an article you can edit.
            $form->setFieldAttribute('featured', 'filter', 'unset');
            $form->setFieldAttribute('ordering', 'filter', 'unset');
            $form->setFieldAttribute('publish_up', 'filter', 'unset');
            $form->setFieldAttribute('publish_down', 'filter', 'unset');
            $form->setFieldAttribute('published', 'filter', 'unset');

        }
        return $form;
    }

    public function save($data)
    {

        if (isset($data['owner_id']) && is_array($data['owner_id'])) {
            $owner_id = implode(", ", $data['owner_id']);
            $data['owner_id'] = $owner_id;
        } else {
            $data['owner_id'] = '';
        }

        if (isset($data['sale_price'])) {
            $preco = substr($data['sale_price'], 3);
            $preco = str_replace('.', '', $preco);
            $preco = str_replace(',', '.', $preco);
            $preco = number_format($preco, 2, '.', '');
            $data['sale_price'] = $preco;
        }

        if (isset($data['rent_price'])) {
            $preco = substr($data['rent_price'], 3);
            $preco = str_replace('.', '', $preco);
            $preco = str_replace(',', '.', $preco);
            $preco = number_format($preco, 2, '.', '');
            $data['rent_price'] = $preco;
        }

        if (isset($data['sharing_price'])) {
            $preco = substr($data['sharing_price'], 3);
            $preco = str_replace('.', '', $preco);
            $preco = str_replace(',', '.', $preco);
            $preco = number_format($preco, 2, '.', '');
            $data['sharing_price'] = $preco;
        }

        if (isset($data['condominium'])) {
            $preco = substr($data['condominium'], 3);
            $preco = str_replace('.', '', $preco);
            $preco = str_replace(',', '.', $preco);
            $preco = number_format($preco, 2, '.', '');
            $data['condominium'] = $preco;
        }

        if (isset($data['iptu'])) {
            $preco = substr($data['iptu'], 3);
            $preco = str_replace('.', '', $preco);
            $preco = str_replace(',', '.', $preco);
            $preco = number_format($preco, 2, '.', '');
            $data['iptu'] = $preco;
        }

        if (isset($data['safe'])) {
            $preco = substr($data['safe'], 3);
            $preco = str_replace('.', '', $preco);
            $preco = str_replace(',', '.', $preco);
            $preco = number_format($preco, 2, '.', '');
            $data['safe'] = $preco;
        }

        if (isset($data['market_value'])) {
            $preco = substr($data['market_value'], 3);
            $preco = str_replace('.', '', $preco);
            $preco = str_replace(',', '.', $preco);
            $preco = number_format($preco, 2, '.', '');
            $data['market_value'] = $preco;
        }

        if (isset($data['date_rented'])) {
            $date_rented = explode("/", $data['date_rented']);
            $data['date_rented'] = $date_rented[2].'-'.$date_rented[1].'-'.$date_rented[0];
        }

        if(isset($data['rented']) && $data['rented'] == 0){
            $data['date_rented'] = '';
        }

        if (isset($data['business']) && is_array($data['business'])) {
            $business = implode(", ", $data['business']);
            $data['business'] = $business;
        } else {
            $data['business'] = '';
        }

        if (isset($data['images']) && is_array($data['images'])) {
            $registry = new JRegistry;
            $registry->loadArray($data['images']);
            $data['images'] = (string)$registry;
        }

        if (isset($data['urls']) && is_array($data['urls'])) {
            $registry = new JRegistry;
            $registry->loadArray($data['urls']);
            $data['urls'] = (string)$registry;
        }

        if (parent::save($data)) {

            $db = JFactory::getDbo();
            if (empty($data['id'])) {
                $data['id'] = $db->insertID();
            }

            ///////////////// INSERINDO IMAGEM CAPA /////////////////////
            if (isset($_FILES['cover_image']) && empty($_FILES['cover_image']['error'][0])) {

                $cover_image = $_FILES['cover_image'];

                jimport('joomla.filesystem.file');

                if (!JFolder::exists(JPATH_SITE . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "imoveis" . DIRECTORY_SEPARATOR . $data["id"])) {
                    JFolder::create(JPATH_SITE . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "imoveis" . DIRECTORY_SEPARATOR . $data["id"]);
                }

                $capa = $this->removeCharacters($cover_image['name']);
                $filename = JFile::makeSafe($cover_image['name']);
                $dest = JPATH_SITE . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "imoveis" . DIRECTORY_SEPARATOR . $data["id"] . DIRECTORY_SEPARATOR . $this->removeCharacters($cover_image['name']);
                $capaImage = JPATH_SITE . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "imoveis" . DIRECTORY_SEPARATOR . $data["id"] . DIRECTORY_SEPARATOR . '/comprarealugar_imovel_' . $data["id"] . '.jpg';
                if (empty($cover_image['error'])) {
                    if ((strtolower(JFile::getExt($filename)) == 'jpg') || (strtolower(JFile::getExt($filename)) == 'gif')
                        || (strtolower(JFile::getExt($filename)) == 'png') || (strtolower(JFile::getExt($filename)) == 'jpeg')
                    ) {
                        JFile::upload($cover_image['tmp_name'], $dest);

                        //Diminui e corta quadrado imagem
                        $image = WideImage::load($dest);
                        $resized = $image->resize(450, 450, 'outside');
                        $resized = $resized->crop('center', 'center', 450, 450);
                        $resized->saveToFile($capaImage, 80);
                    }
                }

                $capaImg = "images/imoveis/" . $data["id"] . '/comprarealugar_imovel_' . $data["id"] . '.jpg';

                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('*');
                $query->from('#__enterprises');
                $query->where('id = ' . $data['id']);
                $query->where('cover_image LIKE "%' . $capa . '%"');
                $db->setQuery($query);
                $resultCapa = $db->loadObjectList();

                if (empty($resultCapa)) {
                    $db = JFactory::getDbo();

                    $query = $db->getQuery(true);

                    $fields = array(
                        $db->quoteName('cover_image') . ' = ' . $db->quote($capaImg)
                    );

                    $conditions = array(
                        $db->quoteName('id') . ' = ' . $db->quote($data["id"])
                    );

                    $query->update($db->quoteName('#__enterprises'))->set($fields)->where($conditions);

                    // Set the query using our newly populated query object and execute it.
                    $db->setQuery($query);

                    $result = $db->execute();
                    $db->transactionCommit();

                }

                $firstPhoto = "images/imoveis/" . $data["id"] . '/' . $capa;

                $db = JFactory::getDbo();
                $queryFirstPhoto = $db->getQuery(true);
                $queryFirstPhoto->select('*');
                $queryFirstPhoto->from('#__enterprise_photos');
                $queryFirstPhoto->where('id_enterprise = ' . $data["id"]);
                $queryFirstPhoto->where('photo LIKE "%' . $firstPhoto . '%"');
                $db->setQuery($queryFirstPhoto);
                $resultFirstPhoto = $db->loadObjectList();

                if (empty($resultFirstPhoto)) {
                    $queryInsertPhoto = $db->getQuery(true);

                    $columns = array(
                        'id_enterprise',
                        'photo'
                    );

                    // Insert values.
                    $values = array(
                        $db->quote($data["id"]),
                        $db->quote($firstPhoto)
                    );

                    // Prepare the insert query.
                    $queryInsertPhoto
                        ->insert($db->quoteName('#__enterprise_photos'))
                        ->columns($db->quoteName($columns))
                        ->values(implode(',', $values));

                    // Set the query using our newly populated query object and execute it.
                    $db->setQuery($queryInsertPhoto);

                    $result = $db->execute();

                }


            }
            ///////////////// INSERINDO IMAGEM CAPA /////////////////////

            ///////////////// INSERINDO IMAGENS /////////////////////
            if (isset($_FILES['photo']) && empty($_FILES['photo']['error'][0])) {

                $photos = $_FILES['photo'];

                jimport('joomla.filesystem.file');

                if (!JFolder::exists(JPATH_SITE . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "imoveis" . DIRECTORY_SEPARATOR . $data["id"])) {
                    JFolder::create(JPATH_SITE . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "imoveis" . DIRECTORY_SEPARATOR . $data["id"]);
                }

                for ($k = 0; $k < sizeof($photos['name']); $k++) {
                    $imagem[] = $this->removeCharacters($photos['name'][$k]);
                    $filename = JFile::makeSafe($photos['name'][$k]);
                    $dest = JPATH_SITE . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "imoveis" . DIRECTORY_SEPARATOR . $data["id"] . DIRECTORY_SEPARATOR . $this->removeCharacters($photos['name'][$k]);
                    if (empty($photos['error'][$k])) {
                        if ((strtolower(JFile::getExt($filename)) == 'jpg') || (strtolower(JFile::getExt($filename)) == 'gif')
                            || (strtolower(JFile::getExt($filename)) == 'png') || (strtolower(JFile::getExt($filename)) == 'jpeg')
                        ) {
                            JFile::upload($photos['tmp_name'][$k], $dest);
                        }
                    }


                }

                foreach ($imagem as $img) {

                    $photo = "images/imoveis/" . $data["id"] . '/' . $img;

                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $query->select('*');
                    $query->from('#__enterprise_photos');
                    $query->where('id_enterprise = ' . $data['id']);
                    $query->where('photo LIKE "%' . $img . '%"');
                    $db->setQuery($query);
                    $resultPhoto = $db->loadObjectList();

                    if (empty($resultPhoto)) {
                        $db = JFactory::getDbo();

                        $query = $db->getQuery(true);

                        $columns = array(
                            'id_enterprise',
                            'photo'
                        );

                        // Insert values.
                        $values = array(
                            $db->quote($data['id']),
                            $db->quote($photo)
                        );

                        // Prepare the insert query.
                        $query
                            ->insert($db->quoteName('#__enterprise_photos'))
                            ->columns($db->quoteName($columns))
                            ->values(implode(',', $values));

                        // Set the query using our newly populated query object and execute it.
                        $db->setQuery($query);

                        $result = $db->execute();
                        $db->transactionCommit();
                    }

                }

            }
            ///////////////// INSERINDO IMAGENS /////////////////////

            return true;
        }


        return false;
    }

    protected function batchCopy($value, $pks, $contexts)
    {
        // Check that the user has create permission for the component
        $extension = JFactory::getApplication()->input->get('option', '');
        $user = JFactory::getUser();
        if (!$user->authorise('core.create', $extension)) {
            $this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_CREATE'));
            return false;
        }

        // Parent exists so we let's proceed
        while (!empty($pks)) {
            // Pop the first ID off the stack
            $pk = array_shift($pks);

            $table->reset();

            // Check that the row actually exists
            if (!$table->load($pk)) {
                if ($error = $table->getError()) {
                    // Fatal error
                    $this->setError($error);
                    return false;
                } else {
                    // Not fatal error
                    $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
                    continue;
                }
            }

            // Alter the title & alias
            $data = $this->generateNewTitle($table->alias, $table->title);
            $table->title = $data['0'];
            $table->alias = $data['1'];

            // Reset the ID because we are making a copy
            $table->id = 0;

            // TODO: Deal with ordering?
            //$table->ordering	= 1;

            // Get the featured state
            $featured = $table->featured;

            // Check the row.
            if (!$table->check()) {
                $this->setError($table->getError());
                return false;
            }

            // Store the row.
            if (!$table->store()) {
                $this->setError($table->getError());
                return false;
            }

            // Get the new item ID
            $newId = $table->get('id');

            // Add the new ID to the array
            $newIds[$i] = $newId;
            $i++;

            // Check if the article was featured and update the #__content_frontpage table
            if ($featured == 1) {
                $db = $this->getDbo();
                $query = $db->getQuery(true);
                $query->insert($db->quoteName('#__enterprises_frontpage'));
                $query->values($newId . ', 0');
                $db->setQuery($query);
                $db->query();
            }
        }

        // Clean the cache
        $this->cleanCache();

        return $newIds;
    }

    protected function loadFormData()
    {

        $data = JFactory::getApplication()->getUserState('com_enterprises.edit.enterprise.data', array());

        if (empty($data)) {
            $data = $this->getItem();


        }

        $business = explode(', ', $data->get('business'));
        $data->set('business', $business);

        return $data;
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

}
