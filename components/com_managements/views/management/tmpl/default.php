<jdoc:include type="message" />
<?php JHtml::_('behavior.keepalive'); ?>
<?php

if ($this->check === false) :

    echo '<h1 class="nopertence">Este imóvel não existe ou não pertence a esse usuário.</h1>';
    echo '<input type="button" class="btn btn-return" value="voltar" onclick="history.back();" />';
    echo "<script>setTimeout('history.go(-1)', 5000)</script>";

else :

    $id_management = '';
    if (!empty($this->management)) :

        foreach ($this->management as $management) :

            $id_management = $management->id;

        endforeach;

    endif;

?>

    <div id="management" class="row-fluid">
        <h1><?php echo (!empty($id_management)) ? 'Prestação de Conta' : 'Solicitação de Reembolso'; ?></h1>
        <div class="msgs">
            <span><i class="fa fa-times-circle" aria-hidden="true"></i></span>
            <p></p>
        </div>
        <form id="insert-management" action="<?php echo JRoute::_('index.php?option=com_managements&task=managements.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
            <div class="span1">
                <label class="label-management">Cliente(s):</label>
            </div>
            <div class="span11">
                <div class="control-group">
                    <select id="clients" name="clients[]" multiple="multiple" class="width100">
                        <?php foreach ($this->clients as $client) : ?>
                            <option value="<?= $client->id ?>"><?= $client->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <h4>
                Contas
            </h4>
            <div class="accounts well">
                <div class="box-account">
                    <label class="label-account label-cash">Valor:</label>
                    <input type="text" class="cash" />
                </div>
                <div class="box-account">
                    <select id="category" name="category">
                        <option value="">Selecione uma Categoria</option>
                        <?php foreach ($this->categories as $category) : ?>
                            <option value="<?= $category->id ?>"><?= $category->descricao ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="box-account">
                    <input type="text" class="nf" placeholder="Nota Fiscal" />
                </div>
                <div class="box-description width100">
                    <label class="label-account label-desc">Descrição:</label>
                    <textarea class="description"></textarea>
                </div>
            </div>
            <button type="button" id="add" class="btn btn-success right">Adicionar conta</button>

            <div class="cancel">
                <button class="btn btn-primary right saveComp" type="submit" id="submit">Salvar</button>
                <span class="right">&nbsp;</span>
                <a href="<?php echo JRoute::_('index.php?option=com_managements'); ?>" class="btn btn-danger right">Cancelar</a>
            </div>
        </form>
    </div>
<?php endif; ?>