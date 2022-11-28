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

$document = JFactory::getDocument();
$document->addScript('components/com_managements/assets/js/jquery.maskedinput.js');
$document->addScript('components/com_managements/assets/js/jquery.maskMoney.js');
$document->addScript('components/com_managements/assets/js/script.js');
$document->addScript('components/com_managements/assets/js/jspdf.min.js');
$document->addScript('components/com_managements/assets/js/jspdf.plugin.autotable.js');

$user = JFactory::getUser();
$userId = $user->get('id');

$route = 'index.php?option=com_managements';
$script = "
jQuery(document).ready(function ($){
    var frm = $('#report-form');
    frm.submit(function (ev) {
        var data = new FormData(this);
        ev.preventDefault();

        //TRATANDO DATAS
        var date_in = '';
        if(data.get('jform[date_in]')){
            fctValidaData('#jform_date_in',data.get('jform[date_in]'));
            date_in = FormataStringData(data.get('jform[date_in]'));
        }

        var date_out = '';
        if(data.get('jform[date_out]')){
            fctValidaData('#jform_date_out',data.get('jform[date_out]'));
            date_out = FormataStringData(data.get('jform[date_out]'));
        }

        if((date_in && date_out) && (date_in > date_out)){
            alert('A data limite deve ser maior que a data de início!');
            $('#jform_date_out').focus();
        }

        //TRATANDO VALORES
        var min_cash = '0.00';
        if(data.get('jform[min_cash]')){
            min_cash = data.get('jform[min_cash]').substr(3);
            min_cash = moeda2float(min_cash);
        }

        var max_cash = '0.00';
        if(data.get('jform[max_cash]')){
            max_cash = data.get('jform[max_cash]').substr(3);
            max_cash = moeda2float(max_cash);
        }

        if((max_cash != '0.00') && (min_cash > max_cash)){
            alert('O valor máximo deve ser maior que o valor mínimo');
            $('#jform_max_cash').focus();
        }

        var url = '" . $route . "'+ '&task=' + data.get('task');
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (response) {

                    var tabela = 'Nenhum resultado encontrado!';
                    if (response) {
                        tabela = '<div id=\"relatorio\">';
                        tabela += '<h2>Relatório</h2>';
                        tabela += '<table class=\"table table-striped\" id=\"articleList\">';
                        tabela += '<thead>';
                        tabela += '<tr>';
                        tabela += '<th>Consultor</th>';
                        tabela += '<th>Carro</th>';
                        tabela += '<th>Adiantamento</th>';
                        tabela += '<th>Pendência</th>';
                        tabela += '<th>Ida</th>';
                        tabela += '<th>Volta</th>';
                        tabela += '<th>Cliente(s)</th>';
                        tabela += '</tr>';
                        tabela += '</thead>';
                        tabela += '<tbody>';
                        $.each(response, function(){
                            tabela += '<tr>';
                            tabela += '<td>' + this.name + '</td>';
                            tabela += '<td>' + this.car + '</td>';
                            tabela += '<td>' + this.cash + '</td>';
                            tabela += '<td class=\"center\">' + this.pendency + '</td>';
                            tabela += '<td class=\"center\">' + this.date_in + '</td>';
                            tabela += '<td class=\"center\">' + this.date_out + '</td>';
                            tabela += '<td width=\"30%\">' + this.clients + '</td>';
                            tabela += '</tr>';
                            console.log();
                        });
                        tabela += '</tbody>';
                        tabela += '</table>';
                        tabela += '</div>';
                        tabela += '<div><button type=\"button\" class=\"btn btn-danger btn-pdf\"><span class=\"icon-download\" aria-hidden=\"true\"></span>Gerar PDF</button></div>';
                        
                    }
                    
                    $('#resultado').empty();
                    $('#resultado').append(tabela);

                    $('#resultado .btn-pdf').click(function(){
                        var img = new Image();
                        img.src = '" . JUri::root() . "images/cigma_consultoria_logo.png';
                        var doc = new jsPDF('l');
                        doc.addImage(img, 'png', 105, 10, 73, 15);
                        doc.autoTable({ 
                            startY: 35,
                            headStyles: { fillColor: [47, 76, 18] },
                            html: '#articleList' 
                        });
                        doc.setProperties({
                            title: 'Relatório Cigma'
                        });
                        doc.output(\"dataurlnewwindow\");
                    });

                }

            });
    });


    function FormataStringData(data) {
        var dia  = data.split('-')[0];
        var mes  = data.split('-')[1];
        var ano  = data.split('-')[2];
      
        return ano + '-' + ('0'+mes).slice(-2) + '-' + ('0'+dia).slice(-2);
    }

    function moeda2float(moeda){
        moeda = moeda.replace('.','');
        moeda = moeda.replace(',','.');
        return parseFloat(moeda);
      
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
            alert('Data informada é inválida!');   
            campo.focus();    
            return false;
        }  
        return true;
    }

});
";

JFactory::getDocument()->addScriptDeclaration($script);
?>

<form action="<?php echo JRoute::_('index.php?option=com_managements&view=reports'); ?>" method="post" name="adminForm" id="report-form">
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">

            <?php echo $this->form->renderField('id_consultant'); ?>
            <?php echo $this->form->renderField('id_car'); ?>
            <?php echo $this->form->renderField('min_cash'); ?>
            <?php echo $this->form->renderField('max_cash'); ?>
            <?php echo $this->form->renderField('pendency'); ?>
            <?php echo $this->form->renderField('date_in'); ?>
            <?php echo $this->form->renderField('date_out'); ?>
            <?php echo $this->form->renderField('id_client'); ?>
            <?php echo $this->form->renderField('order'); ?>
            <?php echo $this->form->renderField('order_to'); ?>

        <input type="submit" name="report" class="btn btn-primary" value="Gerar Relatório" />
        <input type="hidden" name="task" value="reports.gerarRelatorio" />
        <?php echo JHtml::_('form.token'); ?>

</form>
<div id="resultado"></div>