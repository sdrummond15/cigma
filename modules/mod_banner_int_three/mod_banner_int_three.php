<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_representadas
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base(true) . '/modules/mod_banner_int_three/assets/css/style.css');

$imagem1 = $params->get('image');
$imagem2 = $params->get('image1');
$imagem3 = $params->get('image2');
$module = JModuleHelper::getModule('banner_int_three');

require JModuleHelper::getLayoutPath('mod_banner_int_three', $params->get('layout', 'default'));