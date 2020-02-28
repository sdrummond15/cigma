<jdoc:include type="message" />
<div id="managements" class="row-fluid">

    <div class="control-group span12" id="head-management">
        <a href="index.php?view=management" class="btn-simple btn-white btn-create">
            <i class="fa fa-plus-circle" aria-hidden="true"></i><span>Prestar Conta</span>
        </a>
        <h1>Prestação de Contas</h1>
    </div>


    <?php if (!empty($this->managements)) : ?>

        <table class="table table-list table-services_input">
            <thead>
                <tr>
                    <th width="60%">Cliente(s)</th>
                    <th width="15%">Data de Ida</th>
                    <th width="15%">Data de Volta</th>
                    <th width="10%">Aguardando Acerto</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->managements as $management) :

                    $date_in = date("d/m/Y", strtotime($management->date_in));
                    $date_out = date("d/m/Y", strtotime($management->date_out));

                    $clients = ManagementsModelManagements::getClients($management->id_client);
                    $arrayclient = [];
                    foreach ($clients as $cli) {
                        array_push($arrayclient, $cli->client);
                    }
                ?>
                    <tr>
                        <td class="center" width="60%">
                            <a href="<?php echo JRoute::_('index.php?option=com_managements&view=management&id=' . $management->id); ?>">
                                <?php echo implode(', ', $arrayclient); ?>
                            </a>
                        </td>
                        <td class="center" width="15%">
                            <?php echo $date_in; ?>
                        </td>
                        <td class="center" width="15%">
                            <?php echo $date_out; ?>
                        </td>
                        <td class="edit center" width="10%">
                            <a href="<?php echo JRoute::_('index.php?option=com_managements&view=management&id=' . $management->id); ?>" class="btn-simple btn-white btn-edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else : ?>
        <h4>Não existem pendências</h4>
    <?php endif; ?>
</div>