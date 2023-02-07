<jdoc:include type="message" />
<?php JHtml::_('behavior.keepalive');?>



<div id="report_delivery" class="row-fluid">
    <h1>RELATÓRIO DE ENTREGAS FISCAIS</h1>

    <div class="msgs">
        <span><i class="fa fa-times-circle" aria-hidden="true"></i></span>
        <p></p>
    </div>

    <form id="report-form" action="<?php echo JRoute::_('index.php?option=com_managements&view=report_deliveries'); ?>" method="post" class="form-validate form-horizontal form-inline">

        <div class="box-tasks">
            <label class="label-management">Tarefa(s):</label>
            <div class="control-group">
                <select id="tasks" name="tasks[]" multiple="multiple" class="width100" require>
                    <?php foreach ($this->tasks as $task) :
                        $deadline = '';
                        if ($task->deadline != '0000-00-00') {
                            $deadline = ' (' . $task->deadline . ')';
                        }
                    ?>
                        <option value="<?= $task->id ?>"><?= $task->id . ' - ' . $task->task . $deadline ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="box-clients">
            <label class="label-management">Cliente(s):</label>
            <div class="control-group">
                <select id="clients" name="clients[]" multiple="multiple" class="width100" require>
                    <?php foreach ($this->clients as $client) : ?>
                        <option value="<?= $client->id ?>"><?= $client->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="box-consultants">
            <label class="label-management">Consultor:</label>
            <div class="control-group">
                <select id="consultants" name="consultant" class="width100" require>
                    <option value="">Todos</option>
                    <?php foreach ($this->consultants as $consultant) : ?>
                        <option value="<?= $consultant->id ?>"><?= $consultant->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="span12">
            <div class="span6">
                <div class="label-date">
                    <label class="label-management">Data Entrega Início:</label>
                </div>
                <div class="input-date">
                    <input type="text" id="date_in" name="date_in" class="input-width100" value="" require />
                </div>
            </div>
            <div class="span6">
                <div class="label-date">
                    <label class="label-management">Data Entrega Fim:</label>
                </div>
                <div class="input-date">
                    <input type="text" id="date_out" name="date_out" class="input-width100" value="" />
                </div>
            </div>
        </div>


        <div class="span12">
            <div class="span6 control-group">
                <div class="label-obs">
                    <label class="label-management">Observação:</label>
                </div>
                <div class="radio-obs">
                    <div class="inline">
                        <input type="radio" id="todos" name="observation" value="2" checked>
                        <label for="todos">Todos</label>
                    </div>
                    <div class="inline">
                        <input type="radio" id="sim" name="observation" value="1">
                        <label for="sim">Sim</label>
                    </div>
                    <div class="inline">
                        <input type="radio" id="nao" name="observation" value="0">
                        <label for="nao">Não</label>
                    </div>
                </div>
            </div>
            <div class="span6 control-group">
                <div class="label-order">
                    <label class="label-management">Em ordem:</label>
                </div>
                <div class="radio-order">
                    <div class="inline">
                        <input type="radio" id="crescente" name="order" value="ASC" checked>
                        <label for="crescente">Crescente</label>
                    </div>
                    <div class="inline">
                        <input type="radio" id="decrescente" name="order" value="DESC">
                        <label for="decrescente">Decrescente</label>
                    </div>
                </div>
            </div>
        </div>

        <input type="submit" name="report" class="btn btn-simple middle" value="Gerar Relatório" />
        <input type="hidden" name="task" value="gerarRelatorio" />
        <input type="hidden" name="img" value="<?= JUri::root() . "images/cigma_consultoria_logo.png" ?>" />
    </form>

    <div id="results-reports">
    </div>

</div>