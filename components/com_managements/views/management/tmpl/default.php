<jdoc:include type="message"/>
<?php JHtml::_('behavior.keepalive'); ?>
<?php

if ($this->check === false) :

    echo '<input type="button" class="btn btn-return" value="voltar" onclick="history.back();" />';
    echo "<script>setTimeout('history.go(-1)', 5000)</script>";

else :

    $id_management = '';
    $id_clients = '';
    $id_consultants = '';
    $date_in = '';
    $date_out = '';
    $car = '';
    $author = '';
    $cash = '';

    if (!empty($this->management)) :
        foreach ($this->management as $management) :
            $id_management = $management->id;
            $id_clients = $management->id_client;
            $id_consultants = $management->id_consultants;
            $date_in = $management->date_in;
            $date_out = $management->date_out;
            $car = $management->id_car;
            $author = $management->created_by;
            $cash = $management->cash;
            $description1 = $management->description1;
        endforeach;
    endif;

    ?>

    <div id="management" class="row-fluid">
        <?= (!empty($id_management))? '<small>nº <b>' . str_pad($id_management, 10, 0, STR_PAD_LEFT) . '</b></small>' : '' ?>
        <h1>CADASTRO – RELATÓRIO DE DESPESAS DE VIAGEM</h1>

        <?php if ($cash != '0.00' && !empty($cash)): ?>
            <h4>O valor adiantado foi <b>R$ <?= number_format($cash, 2, ',', '.') ?></b></h4><br>
        <?php endif; ?>

        <div class="msgs">
            <span><i class="fa fa-times-circle" aria-hidden="true"></i></span>
            <p></p>
        </div>
        <form id="insert-management"
              action="<?php echo JRoute::_('index.php?option=com_managements&task=managements.save'); ?>" method="post"
              class="form-validate form-horizontal" enctype="multipart/form-data">

            <?php
            $user = JFactory::getUser();
            $id_user = $user->get('id');

            $arrayclient = [];
            $arrayclientid = [];
            $arrayconsultant = [];
            $arrayconsultantid = [];
            $this_date_in = '';
            $this_date_out = '';
            $thiscar = '';

            if (!empty($this->management)) :
                $clients = ManagementsModelManagement::getClients($id_clients, false);
                if (!empty($clients)) {
                    foreach ($clients as $cli) {
                        array_push($arrayclient, $cli->name);
                        array_push($arrayclientid, $cli->id);
                    }
                }
                $consultants = ManagementsModelManagement::getConsultants($id_consultants, false);
                if (!empty($consultants)) {
                    foreach ($consultants as $consultant) {
                        array_push($arrayconsultant, $consultant->nome);
                        array_push($arrayconsultantid, $consultant->id);
                    }
                }

                if (!empty($date_in) && $date_in != '0000-00-00') {
                    $date_in = explode('-', $date_in);
                    $this_date_in = $date_in[2] . '/' . $date_in[1] . '/' . $date_in[0];
                }

                if (!empty($date_out) && $date_out != '0000-00-00') {
                    $date_out = explode('-', $date_out);
                    $this_date_out = $date_out[2] . '/' . $date_out[1] . '/' . $date_out[0];
                }

                if (!empty($car)) {
                    $thiscar = ManagementsModelManagement::getCars($car);
                }

            endif;

            if (!empty($author) && $author != $id_user) :?>
                <div id="client-box" class="well">
                    <h4>Clientes:</h4>
                    <p><?= implode(', ', $arrayclient); ?></p>
                </div>
                <?php
            else:
            ?>

                <div class="box-clients">
                    <label class="label-management">Cliente(s):<span class="required">*</span></label>
                    <div class="control-group">
                        <select id="clients" name="clients[]" multiple="multiple" class="width100" require>
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
                <?php
            endif;
            ?>
                <div class="span12">
                    <div class="span3">
                        <div class="label-date">
                            <label class="label-management">Ida:<span class="required">*</span></label>
                        </div>
                        <div class="input-date">
                            <input type="text" id="date_in" name="date_in" class="input-width100"
                                   value="<?= $this_date_in ?>" require/>
                        </div>
                    </div>
                    <div class="span3">
                        <div class="label-date">
                            <label class="label-management">Volta:</label>
                        </div>
                        <div class="input-date">
                            <input type="text" id="date_out" name="date_out" class="input-width100"
                                   value="<?= $this_date_out ?>"/>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="label-car-solicit">
                            <label class="label-management">Selecione o Carro:</label>
                        </div>
                        <div class="input-car-solicit">
                            <select id="car" name="car" class="width100">
                                <option value="0">Sem Carro</option>
                                <?php foreach ($this->cars as $carro) : ?>
                                    <option
                                        value="<?= $carro->id ?>" <?php echo ($car == $carro->id) ? 'selected' : ''; ?>>
                                        <?= $carro->model . ' - ' . $carro->plate ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="box-consultants">
                        <label class="label-management">Consultor(es):</label>
                        <div class="control-group">
                            <select id="consultants" name="consultants[]" multiple="multiple" class="width100">
                                <?php foreach ($this->consultants as $consultant) : ?>
                                    <?php
                                    $selected_consultant = '';
                                    if (!empty($arrayconsultantid)) {
                                        $consultant_sel = array_search($consultant->id, $arrayconsultantid);
                                        if ($consultant_sel !== false) {
                                            $selected_consultant = 'selected';
                                        }
                                    }
                                    ?>
                                    <option value="<?= $consultant->id ?>" <?= $selected_consultant ?>><?= $consultant->nome ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="box-description width100">
                    <label class="label-desc">Descrição:</label>
                    <textarea id="description1" class="description1" name="description1"><?= $description1 ?></textarea>
                </div>

            <h4 class="account">Contas</h4>

            <div class="accounts well">
                <div class="box-account">
                    <label class="label-account label-cash">Valor:</label>
                    <input type="text" name="cash[]" class="cash"/>
                </div>
                <div class="box-account">
                    <select id="category" name="category[]" required>
                        <?php foreach ($this->categories as $category) : ?>
                            <option value="<?= $category->id ?>"><?= $category->descricao ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="box-account">
                    <select id="payment" name="payment[]" required>
                        <?php foreach ($this->payments as $payment) : ?>
                            <option value="<?= $payment->id ?>"><?= $payment->descricao ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="box-account">
                    <input type="text" name="nf[]" class="nf" placeholder="Nota Fiscal"/>
                </div>
                <div class="box-account">
                    <input type="text" name="expense_date[]" class="date-expenses" placeholder="Data"/>
                </div>
                <div class="box-description width100">
                    <label class="label-account label-desc">Descrição:</label>
                    <textarea class="description" name="description[]"></textarea>
                </div>
            </div>
            <button type="button" id="add" class="btn btn-success right">Adicionar conta</button>

            <div class="cancel">
                <button class="btn btn-primary right saveComp" type="submit" id="submit">Salvar</button>
                <span class="right">&nbsp;</span>
                <a href="<?php echo JRoute::_('index.php?option=com_managements'); ?>" class="btn btn-danger right">Cancelar</a>
            </div>

            <?php if (!empty($this->advanceds_money)): ?>
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
                    foreach ($this->advanceds_money as $advanceds_money):
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

        <?php if(!empty($this->management)): ?>
            <a href="expenses.php?option=com_managements&view=pdfreport&format=pdf&id=<?= $id_management ?>" class="btn btn-warning btn-pdf" target="_blank" title="Fazer Download">
                <i class="fas fa-print"></i> Imprimir
            </a>
        <?php endif; ?>

    </div>
<?php endif; ?>