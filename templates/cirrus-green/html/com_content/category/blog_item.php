<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

$params =& $this->item->params;
$images = json_decode($this->item->images);
$app = JFactory::getApplication();
$canEdit = $this->item->params->get('access-edit');

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

$globallink = '';

//Buscando informações do menu ativo
$menu = JFactory::getApplication()->getMenu();
$active = $menu->getActive();

//inicializando DB para consultas
$db = JFactory::getDbo();

//Buscando parametros do menu HOME
$queryhome = $db->getQuery(true);
$queryhome->select('link');
$queryhome->from('#__menu As m');
$queryhome->where('home = ' . 1);
$db->setQuery($queryhome);
$home = (array)$db->loadObjectList();

//tratando item de menu quando home for categoria como blog OU menu igual a categoria como blog da home
if($active->home == 1 || $home[0]->link == $active->link ){
    $query = $db->getQuery(true);
    $query->select('*');
    $query->from('#__menu As m');
    $query->where('link = \'index.php?option=com_content&view=category&layout=blog&id=' . $this->item->catid . '\'');
    $query->where('home <> ' . 1);
    $db->setQuery($query);
    $rows = (array)$db->loadObjectList();
    if ($rows) {
        $globallink = 'index.php' . DIRECTORY_SEPARATOR .$rows[0]->path . DIRECTORY_SEPARATOR . $this->item->id . '-' . $this->item->alias;
    }
}


?>
<?php if ($this->item->state == 0) : ?>
<div class="system-unpublished">
    <?php endif; ?>

    <?php if (isset($images->image_intro) and !empty($images->image_intro)) : ?>
        <?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
        <div class="img-intro blog-img-<?php echo htmlspecialchars($imgfloat); ?>">
            <a href="<?php echo (empty($globallink)) ? JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)) : $globallink ; ?>">
                <span style="background-image: url(<?php echo htmlspecialchars($images->image_intro); ?>)"></span>
            </a>
        </div>
    <?php endif; ?>
    <?php if ($params->get('show_title')) : ?>
        <h2>
            <?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
                <a href="<?php echo (empty($globallink)) ? JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)) : $globallink ; ?>">
                    <?php echo $this->escape($this->item->title); ?></a>
            <?php else : ?>
                <?php echo $this->escape($this->item->title); ?>
            <?php endif; ?>
        </h2>
    <?php endif; ?>

    <?php if ($params->get('show_print_icon') || $params->get('show_email_icon') || $canEdit) : ?>
        <ul class="actions">
            <?php if ($params->get('show_print_icon')) : ?>
                <li class="print-icon">
                    <?php echo JHtml::_('icon.print_popup', $this->item, $params, array(), true); ?>
                </li>
            <?php endif; ?>
            <?php if ($params->get('show_email_icon')) : ?>
                <li class="email-icon">
                    <?php echo JHtml::_('icon.email', $this->item, $params, array(), true); ?>
                </li>
            <?php endif; ?>
            <?php if ($canEdit) : ?>
                <li class="edit-icon">
                    <?php echo JHtml::_('icon.edit', $this->item, $params, array(), true); ?>
                </li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>

    <?php if (!$params->get('show_intro')) : ?>
        <?php echo $this->item->event->afterDisplayTitle; ?>
    <?php endif; ?>

    <?php echo $this->item->event->beforeDisplayContent; ?>

    <?php // to do not that elegant would be nice to group the params ?>

    <?php if (($params->get('show_author')) or ($params->get('show_category')) or ($params->get('show_create_date')) or ($params->get('show_modify_date')) or ($params->get('show_publish_date')) or ($params->get('show_parent_category')) or ($params->get('show_hits'))) : ?>
    <dl class="article-info">
        <dt class="article-info-term"><?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?></dt>
        <?php endif; ?>
        <?php if ($params->get('show_parent_category') && $this->item->parent_id != 1) : ?>
            <dd class="parent-category-name">
                <?php $title = $this->escape($this->item->parent_title);
                $url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_id)) . '">' . $title . '</a>'; ?>
                <?php if ($params->get('link_parent_category')) : ?>
                    <?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
                <?php else : ?>
                    <?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
                <?php endif; ?>
            </dd>
        <?php endif; ?>
        <?php if ($params->get('show_category')) : ?>
            <dd class="category-name">
                <?php $title = $this->escape($this->item->category_title);
                $url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catid)) . '">' . $title . '</a>'; ?>
                <?php if ($params->get('link_category')) : ?>
                    <?php echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?>
                <?php else : ?>
                    <?php echo JText::sprintf('COM_CONTENT_CATEGORY', $title); ?>
                <?php endif; ?>
            </dd>
        <?php endif; ?>
        <?php if ($params->get('show_create_date')) : ?>
            <dd class="create">
                <?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC2'))); ?>
            </dd>
        <?php endif; ?>
        <?php if ($params->get('show_modify_date')) : ?>
            <dd class="modified">
                <?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC2'))); ?>
            </dd>
        <?php endif; ?>
        <?php if ($params->get('show_publish_date')) : ?>
            <dd class="published">
                <?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC2'))); ?>
            </dd>
        <?php endif; ?>
        <?php if ($params->get('show_author') && !empty($this->item->author)) : ?>
            <dd class="createdby">
                <?php $author = $this->item->author; ?>
                <?php $author = ($this->item->created_by_alias ? $this->item->created_by_alias : $author); ?>

                <?php if (!empty($this->item->contactid) && $params->get('link_author') == true): ?>
                    <?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY',
                        JHtml::_('link', JRoute::_('index.php?option=com_contact&view=contact&id=' . $this->item->contactid), $author)
                    ); ?>

                <?php else : ?>
                    <?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
                <?php endif; ?>
            </dd>
        <?php endif; ?>
        <?php if ($params->get('show_hits')) : ?>
            <dd class="hits">
                <?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
            </dd>
        <?php endif; ?>
        <?php if (($params->get('show_author')) or ($params->get('show_category')) or ($params->get('show_create_date')) or ($params->get('show_modify_date')) or ($params->get('show_publish_date')) or ($params->get('show_parent_category')) or ($params->get('show_hits'))) : ?>
    </dl>
<?php endif; ?>

    <div class="desc-text">
        <?php echo $this->item->introtext; ?>
    </div>

    <?php if ($params->get('show_readmore') && $this->item->readmore) :
        if ($params->get('access-view')) :
            $link = (empty($globallink)) ? JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)) : $globallink ;
        else :
            $menu = JFactory::getApplication()->getMenu();
            $active = $menu->getActive();
            $itemId = $active->id;
            $link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
            $returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug));
            $link = new JURI($link1);
            $link->setVar('return', base64_encode($returnURL));
        endif;
        ?>
        <p class="readmore">
            <a href="<?php echo $link; ?>">
                <?php if (!$params->get('access-view')) :
                    echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
                elseif ($readmore = $this->item->alternative_readmore) :
                    echo $readmore;
                    if ($params->get('show_readmore_title', 0) != 0) :
                        echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
                    endif;
                elseif ($params->get('show_readmore_title', 0) == 0) :
                    echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
                else :
                    echo JText::_('COM_CONTENT_READ_MORE');
                    echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
                endif; ?></a>
        </p>
    <?php endif; ?>

    <?php if ($this->item->state == 0) : ?>
</div>
<?php endif; ?>

<div class="item-separator"></div>
<?php echo $this->item->event->afterDisplayContent; ?>
