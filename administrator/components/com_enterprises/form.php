<form id="inserirAnexo" method="post" action="ajax/controle.php" enctype="multipart/form-data">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Arquivos</h4>
            </div>
            <div class="modal-body">
            	<div class="panel panel-default">
                    <div class="panel-body">
                        <div id="tab-descricao" class="tab-pane active">
                            <div class="row">
            	 				<div class="col-md-12 anexos" style="padding: 0px; padding-right: 40px;">
                                    <label for="anexo" class="control-label">Anexos</label>
                                    <br clear="all" />
                                    <table id="tblAnexos" class="table table-striped table-bordered table-condensed table-hover" style="">
                                        <thead>
                                        <tr>
                                            <th class="id">Arquivo</th>
                                            <th class="id">Descrição</th>
                                            <th class="acao" style="width: 100px;">&nbsp;</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        	<?php if(count($anexos) == 0): ?>
                                            <tr>
                                                <td colspan="3">Nenhum anexo encontrado.</td>
                                            </tr>
	                                        <?php endif; ?>
	                                        <?php foreach($anexos as $anexo): ?>
	                                            <tr id="arquivo_<?php echo $anexo['id_atividade']; ?>_<?php echo $anexo['id']; ?>">
	                                                <td><?php echo $anexo['arquivo']; ?></td>
	                                                <td><?php echo $anexo['descricao']; ?></td>
	                                                <td class="text-center">
	                                                    <button type="button" class="btn btn-info btn-xs visualizar" title="Visualizar Anexo" onclick="downloadAnexo('<?php echo $anexo['arquivo']; ?>');"><i class="fa fa-search" style="font-size: 12px;"></i></button>
	                                                    <button type="button" class="btn btn-danger btn-xs removerAnexoSalvo" title="Remover" onclick="removerAnexo('<?php echo $anexo['arquivo']; ?>', '<?php echo $anexo['id_atividade']; ?>', '<?php echo $anexo['id']; ?>', '<?php echo $identificacao; ?>');" style="margin-right: 3px;"><i class="fa fa-minus-circle" style="font-size: 12px;"></i></button>
	                                                </td>
	                                            </tr>
	                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <label for="anexo" class="control-label">Adicionar Anexos</label>
                                    <div class="anexo" style="padding-bottom: 5px; height: 48px; clear: none;">
                                        <div class="fileanexo" style="float: left; padding: 0px; margin-right: 5px;">
                                            <input type="file" name="anexo[]" class="filestyle" data-buttonText="Selecionar">
                                        </div>
                                        <div class="col-md-7">
											<input type="text" name="descricao[]" class="form-control" data-buttonText="Selecionar" placeholder="Descrição..." maxlength="128">
										</div>
                                        <div class="btn-group" style="float:right;">
                                            <button type="button" class="btn btn-danger btn-xs removerAnexo" title="Remover"><i class="fa fa-minus-circle" style="font-size: 22px;"></i></button>
                                            <button type="button" class="btn btn-primary btn-xs adicionarAnexo" title="Adicionar"><i class="fa fa-plus-circle" style="font-size: 22px;"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="margin-top: 0px;">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save" style="font-size: 1.5em"></i> Salvar</button>
            </div>
        </div>
    </div>
    <input type="hidden" name="acao" value="inserirAnexo" />
    <input type="hidden" name="id_atividade" value="<?php echo $_REQUEST['idAtividade']; ?>" />
    <input type="hidden" name="data_atividade" value="<?php echo $dataAtividade; ?>" />
    <input type="hidden" name="id_evento" value="<?php echo $identificacao; ?>" />
</form>