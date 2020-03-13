<jdoc:include type="message"/>
<?php JHtml::_('behavior.keepalive'); ?>
<?php

if ($this->check === false) :

    echo '<h1 class="nopertence">Este imóvel não existe ou não pertence a esse usuário.</h1>';
    echo '<input type="button" class="btn btn-return" value="voltar" onclick="history.back();" />';
    echo "<script>setTimeout('history.go(-1)', 5000)</script>";

else :

    $id_management = '';
    $id_clients = '';
    $date_in = '';
    $date_out = '';
    $car = '';
    $author = '';
    $cash = '';

    if (!empty($this->management)) :
        foreach ($this->management as $management) :
            $id_management = $management->id;
            $id_clients = $management->id_client;
            $date_in = $management->date_in;
            $date_out = $management->date_out;
            $car = $management->id_car;
            $author = $management->created_by;
            $cash = $management->cash;
        endforeach;
    endif;

    ?>

    <div id="management" class="row-fluid">
        <h1><?php echo (!empty($id_management)) ? 'Prestação de Conta' : 'Solicitação de Reembolso'; ?></h1>

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

                if (!empty($date_in)) {
                    $date_in = explode('-', $date_in);
                    $this_date_in = $date_in[2] . '/' . $date_in[1] . '/' . $date_in[0];
                }

                if (!empty($date_out)) {
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
                <div class="span12">
                    <div class="span3">
                        <p><b>Data de ida:</b> <?= $this_date_in ?></p>
                    </div>
                    <div class="span3">
                        <p><b>Data de volta:</b> <?= $this_date_out ?></p>
                    </div>
                    <div class="span6">
                        <p><b>Carro:</b> <?= $thiscar[0]->model . ' - ' . $thiscar[0]->plate ?></p>
                    </div>
                </div>

                <?php
            else :
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
                                <option value="">Sem Carro</option>
                                <?php foreach ($this->cars as $carro) : ?>
                                    <option
                                        value="<?= $carro->id ?>" <?php echo ($car == $carro->id) ? 'selected' : ''; ?>>
                                        <?= $carro->model . ' - ' . $carro->plate ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <?php
            endif;
            ?>

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
                    <input type="text" name="nf[]" class="nf" placeholder="Nota Fiscal"/>
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
                    <h3>Contas prestadas</h3>
                    <div class="line-head">
                        <div class="head head-cash">Valor</div>
                        <div class="head head-category">Categoria</div>
                        <div class="head head-nf">Nota Fiscal</div>
                        <div class="head head-description">Anotações</div>
                        <div class="head head-delete">Remover</div>
                    </div>
                    <?php
                    $totalCash = 0;
                    foreach ($this->advanceds_money as $advanceds_money):
                        $totalCash += $advanceds_money->cash;
                        ?>
                        <div class="line">
                            <div class="value value-cash">R$ <?= number_format($advanceds_money->cash, 2, ',', '.') ?></div>
                            <div class="value value-category"><?= $advanceds_money->category ?></div>
                            <div class="value value-nf"><?= $advanceds_money->note ?></div>
                            <div class="value value-description"><?= $advanceds_money->description ?></div>
                            <div class="value value-delete">
                                <button type="button" value="<?= $advanceds_money->id ?>" class="delete-account">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                        <?php
                    endforeach;
                    ?>
                    <div class="sum-cash">Total: <b>R$ <?= number_format($totalCash, 2, ',', '.') ?></b></div>
                </div>
            <?php endif; ?>
        </form>
    </div>
<?php endif; ?>