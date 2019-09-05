<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_services
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Service model.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_services
 * @since       1.6
 */

jimport('joomla.application.component.modeladmin');

class ServicesModelService extends JModelAdmin
{

    public function getTable($type='Service', $prefix='ServicesTable', $config=array())
    {
        return JTable::getInstance($type,$prefix,$config);
    }

    protected function batchCopy($value, $pks, $contexts)
    {
        // Check that the user has create permission for the component
        $extension = JFactory::getApplication()->input->get('option', '');
        $user = JFactory::getUser();
        if (!$user->authorise('core.create', $extension ))
        {
            $this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_CREATE'));
            return false;
        }

        // Parent exists so we let's proceed
        while (!empty($pks))
        {
            // Pop the first ID off the stack
            $pk = array_shift($pks);

            $table->reset();

            // Check that the row actually exists
            if (!$table->load($pk))
            {
                if ($error = $table->getError())
                {
                    // Fatal error
                    $this->setError($error);
                    return false;
                }
                else
                {
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
            if (!$table->check())
            {
                $this->setError($table->getError());
                return false;
            }

            // Store the row.
            if (!$table->store())
            {
                $this->setError($table->getError());
                return false;
            }

            // Get the new item ID
            $newId = $table->get('id');

            // Add the new ID to the array
            $newIds[$i]	= $newId;
            $i++;

            // Check if the article was featured and update the #__content_frontpage table
            if ($featured == 1)
            {
                $db = $this->getDbo();
                $query = $db->getQuery(true);
                $query->insert($db->quoteName('#___frontpage'));
                $query->values($newId . ', 0');
                $db->setQuery($query);
                $db->query();
            }
        }

        // Clean the cache
        $this->cleanCache();

        return $newIds;
    }




    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_services.service','service', array('control' => 'jform','load_data' => $loadData));
        if (empty($form))
        {
            return false;
        }

        $jinput = JFactory::getApplication()->input;

        // The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
        if ($jinput->get('a_id'))
        {
            $id =  $jinput->get('a_id', 0);
        }
        // The back end uses id so we use that the rest of the time and set it to 0 by default.
        else
        {
            $id =  $jinput->get('id', 0);
        }

        //(Destaque (Wanderson 19/09/13))
        $user = JFactory::getUser();

        // Check for existing article.
        // Modify the form based on Edit State access controls.
        if ($id != 0 && (!$user->authorise('core.edit.state', 'com_services.service.'.(int) $id))
            || ($id == 0 && !$user->authorise('core.edit.state', 'com_services'))
        )
        {
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

    protected function loadFormData()
    {

        $data = JFactory::getApplication()->getUserState('com_services.edit.service.data', array());

        if (empty($data))
        {
            $data = $this->getItem();
        }

        return $data;
    }

    public function save($data)
    {
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
        // Alter the title for save as copy
        if (JRequest::getVar('task') == 'save2copy') {
            list($title, $alias) = $this->generateNewTitle($data['alias'], $data['title']);
            $data['title']	= $title;
            $data['alias']	= $alias;
        }

        if (parent::save($data)) {

            if (isset($data['featured'])) {
                $this->featured($this->getState($this->getName().'.id'), $data['featured']);
            }

            return true;
        }


        return false;
    }

}
