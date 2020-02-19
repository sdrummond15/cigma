<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_enterprise
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
$document->addStyleSheet('components/com_enterprises/assets/css/font-awesome.min.css');
$document->addStyleSheet('components/com_enterprises/assets/css/enterprise.css');
$document->addScript('components/com_enterprises/assets/js/jquery.maskcpfcnpj.js');
$document->addScript('components/com_enterprises/assets/js/jquery.maskMoney.js');
$document->addScript('components/com_enterprises/assets/js/jquery.noty.packaged.min.js');
$document->addScript('components/com_enterprises/assets/js/jquery.form.js');
$document->addScript('components/com_enterprises/assets/js/photos.js');
$document->addScript('components/com_enterprises/assets/js/script.js');
?>
<script type="text/javascript">
    Joomla.submitbutton = function (task) {
        if (task == 'enterprise.cancel' || document.formvalidator.isValid(document.id('enterprise-form'))) {
            Joomla.submitform(task, document.getElementById('enterprise-form'));
        }
    }
</script>
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(function (cep) {
        jQuery("#jform_cep").mask("99.999-999");
    });
</script>

<form action="<?php echo JRoute::_('index.php?option=com_enterprises&layout=edit&id=' . (int)$this->item->id); ?>"
      method="post" name="adminForm" id="enterprise-form" class="form-validate" enctype="multipart/form-data">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_ENTERPRISE_NEW_ENTERPRISE', true) : JText::_('COM_ENTERPRISE_EDIT_ENTERPRISE', true)); ?>
        <div class="row-fluid">
            <div class="span9">
                <div class="row-fluid form-horizontal-desktop">
                    <div class="span6">
                        <?php echo $this->form->renderField('owner_id'); ?>
                        <?php echo $this->form->renderField('type'); ?>
                        <?php echo $this->form->renderField('animal'); ?>
                        <?php echo $this->form->renderField('description'); ?>
                        <?php echo $this->form->renderField('keywords'); ?>
                    </div>
                </div>
            </div>
            <div class="span3">
                <?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'midia', JText::_('JGLOBAL_FIELDSET_MIDIA', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">
                <?php echo $this->form->renderField('video'); ?>
                <div class="control-group">
                    <div class="control-label">
                        <label for="cover-image">Imagem Principal</label>
                    </div>
                    <div class="controls">
                        <?php
                        $requiredImage = '';
                        if(empty($this->item->cover_image)){
                            $requiredImage = 'required="true"';
                        }?>
                        <input type="file" name="cover_image" id="cover-image" accept="image/*" class="photo" <?php echo $requiredImage; ?>/>
                        <?php if(!empty($this->item->cover_image)): ?>
                        <div class="capa">
                            <img src="<?php echo '..'.DIRECTORY_SEPARATOR.$this->item->cover_image; ?>" />
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label for="photo">Carregar Imagens</label>
                    </div>
                    <div class="controls">
                        <input type="file" name="photo[]" id="photo" accept="image/*" class="photo" multiple/>
                    </div>
                </div>
                <div class="control-group">
                    <input type="button" id="add" value="Adicionar Imagem"/>
                </div>

                <?php if (!empty($this->form->photo)): ?>
                    <table class="table">
                        <thead>
                        <tr>
                            <td>Imagem</td>
                            <td>Nome da Imagem</td>
                            <td>Excluir</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($this->form->photo as $photo):
                            $imgname = basename($photo['photo']);
                            ?>
                            <tr>
                                <td><img class="thumbimg" src="../<?php echo $photo['photo']; ?>"/></td>
                                <td><?php echo $imgname; ?></td>
                                <td><input type="button" class="deleteImg" value="&#xf1f8;" name="<?php echo $photo['id']; ?>"/></td>
                            </tr>
                        <?php endforeach; ?>

                        </tbody>
                    </table>
                <?php endif; ?>

            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'address', JText::_('JGLOBAL_FIELDSET_ADDRESS', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">
                <?php echo $this->form->renderField('state'); ?>
                <?php echo $this->form->renderField('city'); ?>
                <?php echo $this->form->renderField('street'); ?>
                <?php echo $this->form->renderField('number'); ?>
                <?php echo $this->form->renderField('complement'); ?>
                <?php echo $this->form->renderField('district'); ?>
                <?php echo $this->form->renderField('region'); ?>
                <?php echo $this->form->renderField('cep'); ?>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'characteristics', JText::_('JGLOBAL_FIELDSET_CHARACTERISTICS', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">
                <?php echo $this->form->renderField('suites'); ?>
                <?php echo $this->form->renderField('bedrooms'); ?>
                <?php echo $this->form->renderField('bathrooms'); ?>
                <?php echo $this->form->renderField('rooms'); ?>
                <?php echo $this->form->renderField('garage'); ?>
                <?php echo $this->form->renderField('garage_desc'); ?>
                <?php echo $this->form->renderField('total_area'); ?>
                <?php echo $this->form->renderField('building_area'); ?>
                <?php echo $this->form->renderField('gate_keys'); ?>
                <?php echo $this->form->renderField('gate_controls'); ?>
                <?php echo $this->form->renderField('security_alarm'); ?>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>


        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'prices', JText::_('JGLOBAL_FIELDSET_PRICES', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">
                <?php echo $this->form->renderField('business'); ?>
                <?php echo $this->form->renderField('sale_price'); ?>
                <?php echo $this->form->renderField('rent_price'); ?>
                <?php echo $this->form->renderField('sharing_price'); ?>
                <?php echo $this->form->renderField('rented'); ?>
                <?php echo $this->form->renderField('date_rented'); ?>
                <?php echo $this->form->renderField('condominium'); ?>
                <?php echo $this->form->renderField('iptu'); ?>
                <?php echo $this->form->renderField('safe'); ?>
                <?php echo $this->form->renderField('market_value'); ?>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'additional_data', JText::_('JGLOBAL_FIELDSET_ADDITIONAL_DATA', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">
                <?php echo $this->form->renderField('registry'); ?>
                <?php echo $this->form->renderField('registry_office'); ?>
                <?php echo $this->form->renderField('iptu_register'); ?>
                <?php echo $this->form->renderField('energy'); ?>
                <?php echo $this->form->renderField('number_energy'); ?>
                <?php echo $this->form->renderField('clock_energy'); ?>
                <?php echo $this->form->renderField('water'); ?>
                <?php echo $this->form->renderField('number_water'); ?>
                <?php echo $this->form->renderField('clock_water'); ?>
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
