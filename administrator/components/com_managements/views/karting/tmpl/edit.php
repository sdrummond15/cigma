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

?>
<script src="../administrator/components/com_managements/assets/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript">
    /* Máscaras TELEFONE */
    function mascara(o,f){
        v_obj=o
        v_fun=f
        setTimeout("execmascara()",1)
    }
    function execmascara(){
        v_obj.value=v_fun(v_obj.value)
    }
    function mtel(v){
        v=v.replace(/\D/g,""); //Remove tudo o que não é dígito
        v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
        v=v.replace(/(\d)(\d{4})$/,"$1-$2"); //Coloca hífen entre o quarto e o quinto dígitos
        return v;
    }
    function id( el ){
        return document.getElementById( el );
    }
    /*TELEFONE 1*/
    window.onload = function(){
        id('jform_phone_fix').onkeyup = function(){
            mascara( this, mtel );
        }
        id('jform_mobile_phone').onkeyup = function(){
            mascara( this, mtel );
        }
        id('jform_mobile_phone1').onkeyup = function(){
            mascara( this, mtel );
        }
    }
</script>
<script type="text/javascript">
    Joomla.submitbutton = function (task) {
        if (task == 'karting.cancel' || document.formvalidator.isValid(document.id('karting-form'))) {
            Joomla.submitform(task, document.getElementById('karting-form'));
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_managements&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="karting-form" class="form-validate">

    <?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_MANAGEMENTS_NEW_KARTING', true) : JText::_('COM_MANAGEMENTS_EDIT_KARTING', true)); ?>
        <div class="row-fluid">
            <div class="span9">
                <div class="row-fluid form-horizontal-desktop">
                    <div class="span6">
                        <?php echo $this->form->renderField('photo'); ?>
                        <?php echo $this->form->renderField('birth_date'); ?>
                        <?php echo $this->form->renderField('phone_fix'); ?>
                        <?php echo $this->form->renderField('mobile_phone'); ?>
                        <?php echo $this->form->renderField('mobile_phone1'); ?>
                        <?php echo $this->form->renderField('email'); ?>
                        <?php echo $this->form->renderField('email1'); ?>
                        <?php echo $this->form->renderField('site'); ?>
                        <?php echo $this->form->renderField('street'); ?>
                        <?php echo $this->form->renderField('number'); ?>
                        <?php echo $this->form->renderField('district'); ?>
                        <?php echo $this->form->renderField('cep'); ?>
                        <?php echo $this->form->renderField('city'); ?>
                        <?php echo $this->form->renderField('logo'); ?>
                        <?php echo $this->form->renderField('description'); ?>
                        <?php echo $this->form->renderField('map'); ?>
                        <?php echo $this->form->renderField('folder'); ?>
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
