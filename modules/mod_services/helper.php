<?php
/**
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * @package		Joomla.Site
 * @subpackage	mod_qualification
 * @since		1.5
 */
class modServicesHelper
{
    public static function getServices(){
        
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__services As s');
        $query->where('s.published = 1');
        $query->order('s.ordering ASC');
        $query->setLimit(4);
        
        $db->setQuery($query);
	    $rows = (array) $db->loadObjectList();
        shuffle($rows);

        return $rows;
    }

}