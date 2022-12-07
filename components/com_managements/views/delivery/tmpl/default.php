<jdoc:include type="message" />
<?php JHtml::_('behavior.keepalive'); ?>
<?php

if ($this->check === false) :

    echo '<input type="button" class="btn btn-return" value="voltar" onclick="history.back();" />';
    echo "<script>setTimeout('history.go(-1)', 5000)</script>";

else :

    $taxDeliveryId = '';
    $idTask = '';
    $idClient = '';
    $consultant = '';
    $date_delivery = '';
    $observation = '';

    if (!empty($this->delivery)) :
        foreach ($this->delivery as $delivery) :
            $taxDeliveryId = $delivery->taxDeliveryId;
            $idTask = $delivery->task;
            $idClient = $delivery->client;
            $consultant = $delivery->consultant;
            $date_delivery = $delivery->date_delivery;
            $observation = $delivery->observation;
        endforeach;
    endif;

?>

    <div id="management" class="row-fluid">
        <?= (!empty($taxDeliveryId)) ? '<small>nº <b>' . str_pad($taxDeliveryId, 10, 0, STR_PAD_LEFT) . '</b></small>' : '' ?>
        <h1>CADASTRO – ENTREGA FISCAL</h1>

        <div class="msgs">
            <span><i class="fa fa-times-circle" aria-hidden="true"></i></span>
            <p></p>
        </div>
        <form id="insert-tax-delivery" action="<?php echo JRoute::_('index.php?option=com_managements&task=deliveries.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">

            <?php
            $user = JFactory::getUser();
            $id_user = $user->get('id');

            $arrayclient = [];
            $arrayclientid = [];
            $arraytask = [];
            $arraytaskid = [];

            if (!empty($this->delivery)) :
                $clients = ManagementsModelDelivery::getClients($idClient, false);
                if (!empty($clients)) {
                    foreach ($clients as $cli) {
                        array_push($arrayclient, $cli->name);
                        array_push($arrayclientid, $cli->id);
                    }
                }
                $tasks = ManagementsModelDelivery::getTasks($idTask, false);
                if (!empty($tasks)) {
                    foreach ($tasks as $tsk) {
                        array_push($arraytask, $tsk->task);
                        array_push($arraytaskid, $tsk->id);
                    }
                }

                if (!empty($date_delivery) && $date_delivery != '0000-00-00') {
                    $date_delivery = explode('-', $date_delivery);
                    $date_delivery = $date_delivery[2] . '/' . $date_delivery[1] . '/' . $date_delivery[0];
                }else{
                    $date_delivery = null;
                }

            endif;
            ?>

            <div class="box-tasks">
                <label class="label-management">Tarefa:<span class="required">*</span></label>
                <div class="control-group">
                    <select id="tasks" name="id_task" class="width100" require>
                        <?php foreach ($this->tasks as $task) : ?>
                            <?php
                            $selected_task = '';
                            if (!empty($arraytaskid)) {
                                $task_sel = array_search($task->id, $arraytaskid);
                                if ($task_sel !== false) {
                                    $selected_task = 'selected';
                                }
                            }
                            ?>
                            <option value="<?= $task->id ?>" <?= $selected_task ?>><?= $task->id . ' - ' . $task->task . ' (' . $task->period . ')'  ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="span12">
                <div class="box-clients span6">
                    <label class="label-management">Cliente:<span class="required">*</span></label>
                    <div class="control-group">
                        <select id="clients" name="client" class="width100" require>
                            <?php foreach ($this->clients as $client) : ?>
                                <?php
                                $selected_client = '';
                                if (!empty($arrayclientid)) {
                                    $client_sel = array_search($client->id, $arrayclientid);
                                    if ($client_sel !== false) {
                                        $selected_client = 'selected';
                                    }
                                }
                                ?>
                                <option value="<?= $client->id ?>" <?= $selected_client ?>><?= $client->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="span6">
                    <div class="label-date">
                        <label class="label-management">Data da entrega:</label>
                    </div>
                    <div class="input-date date_delivery">
                        <input type="text" id="date_delivery" name="date_delivery" class="input-width100" value="<?= $date_delivery ?>" require />
                    </div>
                </div>
            </div>

            <div class="box-description">
                <label class="label-management">Observação:</label>
                <textarea id="observation" rows="6" class="observation" name="observation"><?= $observation ?></textarea>
            </div>

            <div class="cancel">
                <button class="btn btn-primary right saveComp" type="submit" id="submit-tax-delivery">Salvar</button>
                <span class="right">&nbsp;</span>
                <a href="<?php echo JRoute::_('index.php?option=com_managements'); ?>" class="btn btn-danger right">Cancelar</a>
            </div>

            <?php if (!empty($this->advanceds_money)) : ?>
                <div id="list-accounts">
                    <h3>Despesas</h3>
                    <div class="line-head">
                        <div class="head head-cash">Valor</div>
                        <div class="head head-category">Categoria</div>
                        <div class="head head-payment">F. Pagamento</div>
                        <div class="head head-nf">Nota Fiscal</div>
                        <div class="head head-expense-date">Data</div>
                        <div class="head head-description">Anotações</div>
                        <div class="head head-delete">Remover</div>
                    </div>
                    <?php
                    $totalCash = 0;
                    foreach ($this->advanceds_money as $advanceds_money) :
                        $totalCash += $advanceds_money->cash;
                        $dateExpenses = '-';
                        if (!empty($advanceds_money->expense_date) && $advanceds_money->expense_date != '0000-00-00') {
                            $dateExpenses = explode('-', $advanceds_money->expense_date);
                            $dateExpenses = $dateExpenses[2] . '/' . $dateExpenses[1] . '/' . $dateExpenses[0];
                        }
                    ?>
                        <div class="line">
                            <div class="value value-cash">R$ <?= number_format($advanceds_money->cash, 2, ',', '.') ?></div>
                            <div class="value value-category"><?= $advanceds_money->category ?></div>
                            <div class="value value-payment"><?= $advanceds_money->payment ?></div>
                            <div class="value value-nf"><?= $advanceds_money->note ?></div>
                            <div class="value value-expense-date"><?= $dateExpenses ?></div>
                            <div class="value value-description"><?= $advanceds_money->description ?></div>
                            <div class="value value-delete">
                                <button type="button" value="<?= $advanceds_money->id . '_' . $id_management ?>" class="delete-account">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    <?php
                    endforeach;
                    ?>
                    <div class="sum-cash">Adiantamento: <b>R$ <?= number_format($cash, 2, ',', '.') ?></b></div>
                    <div class="sum-cash">Total: <b>R$ <?= number_format($totalCash, 2, ',', '.') ?></b></div>
                </div>
            <?php endif; ?>
        </form>

        <?php if (!empty($this->management)) : ?>
            <a href="expenses.php?option=com_managements&view=pdfreport&format=pdf&id=<?= $id_management ?>" class="btn btn-warning btn-pdf" target="_blank" title="Fazer Download">
                <i class="fas fa-print"></i> Imprimir
            </a>
        <?php endif; ?>

    </div>
<?php endif; ?>