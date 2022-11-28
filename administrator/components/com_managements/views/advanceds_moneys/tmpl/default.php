<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_managements
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$archived  = $this->state->get('filter.published') == 2 ? true : false;
$trashed   = $this->state->get('filter.published') == -2 ? true : false;
$saveOrder = $listOrder == 'a.ordering';
if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_managements&task=advanceds_moneys.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'managementList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
$assoc = JLanguageAssociations::isEnabled();
?>

<script type="text/javascript">
    Joomla.orderTable = function() {
        table = document.getElementById("sortTable");
        direction = document.getElementById("directionTable");
        order = table.options[table.selectedIndex].value;
        if (order != '<?php echo $listOrder; ?>') {
            dirn = 'asc';
        } else {
            dirn = direction.options[direction.selectedIndex].value;
        }
        Joomla.tableOrdering(order, dirn, '');
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_managements&view=advanceds_moneys'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
        <?php
        // Search tools bar
        echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
        ?>
        <?php if (empty($this->items)) : ?>
            <div class="alert alert-no-items">
                <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
            </div>
        <?php else : ?>
            <table class="table table-striped" id="articleList">
                <thead>
                    <tr>
                        <th width="1%" class="nowrap center hidden-phone">
                            <?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
                        </th>
                        <th width="1%" class="hidden-phone">
                            <?php echo JHtml::_('grid.checkall'); ?>
                        </th>
                        <th width="1%" style="min-width:55px" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
                        </th>
                        <th width="30%" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'COM_MANAGEMENTS_HEADING_CONSULTANTS', 'u.name', $listDirn, $listOrder); ?>
                        </th>
                        <th width="5%" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'COM_MANAGEMENTS_HEADING_DATE_IN', 'a.date_in', $listDirn, $listOrder); ?>
                        </th>
                        <th width="5%" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'COM_MANAGEMENTS_HEADING_DATE_OUT', 'a.date_out', $listDirn, $listOrder); ?>
                        </th>
                        <th width="1%" class="nowrap center hidden-phone">
                            <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($this->items as $i => $item) :
                        $ordering = $listOrder == 'a.ordering';
                        $canCreate = $user->authorise('core.create');
                        $canEdit = $user->authorise('core.edit');
                        $canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
                        $canEditOwn = $user->authorise('core.edit.own') && $item->created_by == $userId;
                        $canChange = $user->authorise('core.edit.state') && $canCheckin;
                    ?>
                        <tr class="row<?php echo $i % 2; ?>">
                            <td class="order nowrap center hidden-phone">
                                <?php
                                $iconClass = '';
                                if (!$canChange) {
                                    $iconClass = ' inactive';
                                } elseif (!$saveOrder) {
                                    $iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
                                }
                                ?>
                                <span class="sortable-handler<?php echo $iconClass ?>">
                                    <i class="icon-menu"></i>
                                </span>
                                <?php if ($canChange && $saveOrder) : ?>
                                    <input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
                                <?php endif; ?>
                            </td>
                            <td class="center hidden-phone">
                                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                            </td>
                            <td class="center">
                                <div class="btn-group">
                                    <?php echo JHtml::_('jgrid.published', $item->published, $i, 'advanceds_moneys.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
                                    <?php
                                    // Create dropdown items
                                    $action = $archived ? 'unarchive' : 'archive';
                                    JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'advanceds_moneys');

                                    $action = $trashed ? 'untrash' : 'trash';
                                    JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'advanceds_moneys');

                                    // Render dropdown list
                                    echo JHtml::_('actionsdropdown.render', $this->escape($item->id));
                                    ?>
                                </div>
                            </td>
                            <td class="nowrap has-context">
                                <div class="center">
                                    <?php if ($item->checked_out) : ?>
                                        <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'advanceds_moneys.', $canCheckin); ?>
                                    <?php endif; ?>
                                    <?php if ($canEdit || $canEditOwn) : ?>
                                        <a href="<?php echo JRoute::_('index.php?option=com_managements&task=advanceds_money.edit&id=' . (int)$item->id); ?>">
                                            <?php echo $this->escape($item->consultant); ?>
                                        </a>
                                    <?php else : ?>
                                        <?php echo $this->escape($item->consultant); ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="center hidden-phone">
                                <div class="center">
                                    <?php echo ($item->date_in == '0000-00-00') ? '-' : JHtml::_('date', $item->date_in, 'DATE_FORMAT_JS2'); ?>
                                </div>
                            </td>
                            <td class="center hidden-phone">
                                <div class="center">
                                <?php echo ($item->date_out == '0000-00-00') ? '-' : JHtml::_('date', $item->date_out, 'DATE_FORMAT_JS2'); ?>
                                </div>
                            </td>
                            <td class="center hidden-phone">
                                <?php echo $item->id; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php // Load the batch processing form. 
            ?>
            <?php if (
                $user->authorise('core.create', 'com_managements')
                && $user->authorise('core.edit', 'com_managements')
                && $user->authorise('core.edit.state', 'com_managements')
            ) : ?>
                <?php echo JHtml::_(
                    'bootstrap.renderModal',
                    'collapseModal',
                    array(
                        'title'  => JText::_('COM_MANAGEMENTS_BATCH_OPTIONS'),
                        'footer' => $this->loadTemplate('batch_footer'),
                    ),
                    $this->loadTemplate('batch_body')
                ); ?>
            <?php endif; ?>
        <?php endif; ?>

        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>