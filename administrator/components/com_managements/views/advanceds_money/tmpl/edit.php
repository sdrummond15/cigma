<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_managements
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$assoc = JLanguageAssociations::isEnabled();

$document = JFactory::getDocument();
$document->addScript('components/com_managements/assets/js/jquery.maskedinput.js');
$document->addScript('components/com_managements/assets/js/jquery.maskMoney.js');
$document->addScript('components/com_managements/assets/js/script.js');

?>
<script type="text/javascript">
    Joomla.submitbutton = function (task) {
        if (task == 'advanceds_money.cancel' || document.formvalidator.isValid(document.id('advanceds_money-form'))) {
            Joomla.submitform(task, document.getElementById('advanceds_money-form'));
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_managements&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="advanceds_money-form" class="form-validate">

    <?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_MANAGEMENTS_NEW_ADVANCEDS_MONEY', true) : JText::_('COM_MANAGEMENTS_EDIT_ADVANCEDS_MONEY', true)); ?>
        <div class="row-fluid">
            <div class="span9">
                <div class="row-fluid form-horizontal-desktop">
                    <div class="span6">
                        <?php echo $this->form->renderField('id_consultant'); ?>
                        <?php echo $this->form->renderField('cash'); ?>
                        <?php echo $this->form->renderField('pendency'); ?>
                        <?php echo $this->form->renderField('date_in'); ?>
                        <?php echo $this->form->renderField('date_out'); ?>
                        <?php echo $this->form->renderField('id_client'); ?>
                        <?php echo $this->form->renderField('observation'); ?>
                    </div>
                </div>
            </div>
            <div class="span3">
                <?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">
                <?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
            </div>
            <div class="span6">
                <?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>



        <?php if ($assoc) : ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'associations', JText::_('JGLOBAL_FIELDSET_ASSOCIATIONS', true)); ?>
            <?php echo $this->loadTemplate('associations'); ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php endif; ?>

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
    </div>

    <input type="hidden" name="task" value=""/>
    <?php echo JHtml::_('form.token'); ?>
</form>
