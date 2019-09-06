<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_article_single
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base(true) . '/modules/mod_article_home/assets/css/style.css');

$article = $params->get('article');
$image1 = $params->get('image1');
$image2 = $params->get('image2');
$image3 = $params->get('image3');
$image4 = $params->get('image4');
$link = $params->get('link');
$button_link = $params->get('button_link');

$article = ModArticleHomeHelper::getArticleHome($article);

require JModuleHelper::getLayoutPath('mod_article_home', $params->get('layout', 'default'));

