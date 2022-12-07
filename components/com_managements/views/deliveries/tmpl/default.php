<jdoc:include type="message" />
<div id="managements" class="row-fluid">

    <div class="control-group span12" id="head-management">
        <?php if ($this->check) : ?>
            <a href="index.php?view=delivery" class="btn-simple btn-white btn-create">
                <i class="fa fa-plus-circle" aria-hidden="true"></i><span>Entrega</span>
            </a>
        <?php endif; ?>
        <h1>Entregas Fiscais</h1>
    </div>


    <?php if (!empty($this->deliveries)) : ?>

        <table class="table table-list table-services_input">
            <thead>
                <tr>
                    <th width="50%">Tarefa</th>
                    <th width="15%">Cliente</th>
                    <th width="10%">Data de entrega</th>
                    <th width="10%">Editar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->deliveries as $delivery) :

                    if ($delivery->date_delivery != '0000-00-00') {
                        $date_delivery = date("d/m/Y", strtotime($delivery->date_delivery));
                    } else {
                        $date_delivery = ' - ';
                    }

                ?>
                    <tr>
                        <td class="center" width="50%">
                            <?php if ($this->check) : ?>
                                <a href="<?php echo JRoute::_('index.php?option=com_managements&view=delivery&id=' . $delivery->id); ?>">
                                    <?= $delivery->task ?>
                                </a>
                            <?php else : ?>
                                <?= $delivery->task ?>
                            <?php endif; ?>
                        </td>
                        <td class="center" width="15%">
                            <?= $delivery->client ?>
                        </td>
                        <td class="center" width="10%">
                            <?= $date_delivery; ?>
                        </td>
                        <td class="edit center" width="10%">
                            <?php if ($this->check) : ?>
                                <a href="<?php echo JRoute::_('index.php?option=com_managements&view=delivery&id=' . $delivery->id); ?>" class="btn-simple btn-white btn-edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else : ?>
        <h4>Não existem entregas</h4>
    <?php endif; ?>
</div>