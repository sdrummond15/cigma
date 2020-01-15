<?php

//não direciona acesso para as pastas
//
//importa biblioteca de controle Joomla

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class ServicesModelServices extends JModelList
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])){
            $config['filter_fields'] = array(
                'id','a.id',
                'name','a.name',
                'published','a.published',
                'ordering','a.ordering',
                'featured', 'a.featured',
                'publish_up', 'a.publish_up',
                'publish_down', 'a.publish_down'
            );

        }
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication();

        // Adjust the context to support modal layouts.
        if ($layout = $app->input->get('layout'))
        {
            $this->context .= '.' . $layout;
        }
        $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published','', 'string');
        $this->setState('filter.published', $published);

        parent::populateState('a.name','asc');
    }

    protected function getStoreId($id = '')
    {

        $id .=':'.$this->getState('filter.search');
        $id .=':'.$this->getState('filter.published');

        return parent::getStoreId($id);
    }

    protected function getListQuery()
    {
        $db         = $this->getDbo();
        $query      = $db->getQuery(true);

        $query->select(
            $this->getState(
                'list.select',
                'a.id, a.name, a.alias'.
                ', a.checked_out, a.checked_out_time' .
                ', a.published, a.created'.
                ', a.created_by, a.ordering'.
                ', a.publish_up, a.publish_down'
            )
        );


        $query->from('#__services AS a');

        $query->select('uc.name AS editor');
        $query->join('LEFT','#__users AS uc ON uc.id = a.checked_out');

        $published = $this->getState('filter.published');

        if(is_numeric($published)){
            $query->where('a.published = '. (int) $published);
        }
        elseif ($published === '') {
            $query->where('(a.published= 0 OR a.published = 1)');
        }

        $search = $this->getState('filter.search');
        if (!empty($search)){
            if(stripos($search, 'id:')=== 0){
                $query->where('a.id = '.(int) substr($search, 3));
            }
            else {
                $search = $db->Quote('%'.$db->escape($search,true).'%');
                $query->where('(a.name LIKE '.$search.'OR a.alias LIKE'.$search.')');
            }
        }

        $orderCol = $this->state->get('list.ordering','a.name');
        $orderDirn = $this->state->get('list.direction','asc');

        if ($orderCol == 'ordering')
        {
            $orderCol = 'a.name ';
        }

        $query->order($db->escape($orderCol.' '.$orderDirn));

        return $query;

    }
}


?>
