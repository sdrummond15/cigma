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
$document->addStyleSheet('components/com_managements/assets/css/font-awesome.min.css');
$document->addStyleSheet('components/com_managements/assets/css/management.css');
$document->addScript('components/com_managements/assets/js/script.js');
?>
<script type="text/javascript">
    Joomla.submitbutton = function (task) {
        if (task == 'grid.cancel' || document.formvalidator.isValid(document.id('grid-form'))) {
            Joomla.submitform(task, document.getElementById('grid-form'));
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_managements&layout=edit&id=' . (int)$this->item->id); ?>" method="post" name="adminForm" id="grid-form" class="form-validate" enctype="multipart/form-data">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_MANAGEMENTS_NEW_GRID', true) : JText::_('COM_MANAGEMENTS_EDIT_GRID', true)); ?>
        <div class="row-fluid">
            <div class="span9">
                <div class="row-fluid form-horizontal-desktop">
                    <div class="span6">
                        <?php echo $this->form->renderField('id_stage'); ?>

                        <?php
                        $positions = explode(',', $this->item->positions);
                        if (!empty($positions)): ?>
                            <div id="positions">
                                <?php
                                $count = 1;
                                foreach ($positions as $position):
                                    ?>

                                    <div class="control-group">
                                        <div class="control-label">
                                            <label><?php echo $count; ?>ª Posição</label>
                                        </div>
                                        <div class="controls">
                                            <select id="positions" name="positions[]" class="positions">
                                                <option value="0"><?php echo JText::_('COM_MANAGEMENTS_NO_PILOT'); ?></option>
                                                <?php foreach ($this->pilots as $pilot): ?>
                                                    <option value="<?php echo $pilot->id; ?>" <?php if($position == $pilot->id) echo 'selected'; ?> ><?php echo $pilot->name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <a class="btn btn-danger remove"><i class="fa fa-minus fa-sm"></i></a>
                                        </div>
                                    </div>

                                <?php
                                $count ++;
                                endforeach; ?>
                            </div>
                            <?php
                        else:
                            ?>
                            <div id="positions">
                                <div class="control-group">
                                    <div class="control-label">
                                        <label>1ª Posição</label>
                                    </div>
                                    <div class="controls">
                                        <select id="positions" name="positions[]" class="positions">
                                            <option value="0"><?php echo JText::_('COM_MANAGEMENTS_NO_PILOT'); ?></option>
                                            <?php foreach ($this->pilots as $pilot): ?>
                                                <option value="<?php echo $pilot->id; ?>"><?php echo $pilot->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <a class="btn btn-danger remove"><i class="fa fa-minus fa-sm"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="control-group">
                            <a id="add" class="btn btn-primary"><i class="fa fa-user-plus fa-sm"></i> Adicionar Piloto</a>
                        </div>
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