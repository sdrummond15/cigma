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
JHtml::_('jquery.framework');
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', 'select');

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "advanceds_money.cancel" || document.formvalidator.isValid(document.getElementById("advanceds_money-form")))
		{
			Joomla.submitform(task, document.getElementById("advanceds_money-form"));
		}
	};
	jQuery(document).ready(function ($){
		$("#jform_type").on("change", function (a, params) {

			var v = typeof(params) !== "object" ? $("#jform_type").val() : params.selected;

			var img_url = $("#image, #url");
			var custom  = $("#custom");

			switch (v) {
				case "0":
					// Image
					img_url.show();
					custom.hide();
					break;
				case "1":
					// Custom
					img_url.hide();
					custom.show();
					break;
			}
		}).trigger("change");

        var frm = $("#advanceds_money-form");
        frm.submit(function (ev) {
            var data = new FormData(this);
            ev.preventDefault();

            //TRATANDO DATAS
            var date_in = "";
            if(data.get("jform[date_in]")){
                fctValidaData("#jform_date_in",data.get("jform[date_in]"));
                date_in = FormataStringData(data.get("jform[date_in]"));
            }

            var date_out = "";
            if(data.get("jform[date_out]")){
                fctValidaData("#jform_date_out",data.get("jform[date_out]"));
                date_out = FormataStringData(data.get("jform[date_out]"));
            }

            if((date_in && date_out) && (date_in > date_out)){
                alert("Favor revisar a data de volta, pois ela deve ser maior ou igual a data de ida!");
                $("#jform_date_out").focus();
                return false;
            }
            this.submit();
        });

        function FormataStringData(data) {
            var dia  = data.split("/")[0];
            var mes  = data.split("/")[1];
            var ano  = data.split("/")[2];
          
            return ano + "-" + ("0"+mes).slice(-2) + "-" + ("0"+dia).slice(-2);
        }

        function fctValidaData(campo, data)
        {
            var dia = data.substring(0,2)
            var mes = data.substring(3,5)
            var ano = data.substring(6,10)

            //Criando um objeto Date usando os valores ano, mes e dia.
            var novaData = new Date(ano,(mes-1),dia);

            var mesmoDia = parseInt(dia,10) == parseInt(novaData.getDate());
            var mesmoMes = parseInt(mes,10) == parseInt(novaData.getMonth())+1;
            var mesmoAno = parseInt(ano) == parseInt(novaData.getFullYear());

            if (!((mesmoDia) && (mesmoMes) && (mesmoAno)))
            {
                alert("Data informada é inválida!");   
                campo.focus();    
                return false;
            }  
            return true;
        }

	});
');

$document = JFactory::getDocument();
$document->addScript('components/com_managements/assets/js/jquery.maskMoney.js');
$document->addScript('components/com_managements/assets/js/script.js');
?>

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
                        <?php echo $this->form->renderField('id_car'); ?>
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

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'accounts', JText::_('JGLOBAL_FIELDSET_ACCOUNTS', true)); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span12">
                <?php
                $accounts = ManagementsModelAdvanceds_Money::getAccounts($this->item->id);
                if (!empty($accounts)) :
                ?>
                    <table class="table table-striped">
                        <thead>
                            <tr width="100%">
                                <th width="30%" class="center">Descrição</th>
                                <th width="20%" class="center">Categoria</th>
                                <th width="20%" class="center">Nota</th>
                                <th width="15%" class="center">Data</th>
                                <th width="15%" class="center">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalCash = 0;
                            foreach ($accounts as $account) :
                                $totalCash += $account->cash;
                                $dateExpenses = '-';
                                if (!empty($account->expense_date) && $account->expense_date != '0000-00-00') {
                                    $dateExpenses = explode('-', $account->expense_date);
                                    $dateExpenses = $dateExpenses[2] . '/' . $dateExpenses[1] . '/' . $dateExpenses[0];
                                }
                            ?>
                                <tr>
                                    <td><?= $account->description ?></td>
                                    <td class="center"><?= $account->category ?></td>
                                    <td class="center"><?= $account->note ?></td>
                                    <td class="center"><?= $dateExpenses ?></td>
                                    <td class="center">R$ <?= number_format($account->cash, 2, ',', '.') ?></td>
                                </tr>
                            <?php
                            endforeach;
                            ?>
                            <tr width="100%">
                                <td class="total" colspan="5">
                                    Total: <b>R$ <?= number_format($totalCash, 2, ',', '.') ?></b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php
                else :
                ?>
                    <p>Nenhuma despesa informada!</p>
                <?php
                endif;
                ?>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING')); ?>
        <div class="row-fluid form-horizontal-desktop">
            <div class="span6">
                <?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
            </div>
            <div class="span6">
                <?php echo $this->form->renderFieldset('metadata'); ?>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
    </div>

    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>