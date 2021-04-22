<!-- header  -->

<!-- Fim header  -->

<!-- Sidebar -->
<?php
$this->load->view('layout/sidebar');
?>
<!-- Fim, Sidebar -->


<!-- Main Content -->
<div id="content">

	<!-- Topbar -->
	<?php
	$this->load->view('layout/navbar');
	?>
	<!-- Fim, Topbar -->


	<!-- Begin Page Content -->
	<div class="container-fluid">

		<!-- *** Tabela de usuário - MENUS BREADCRUMB -->

		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">

				<!-- **** Permite voltar para página Home -->
				<li class="breadcrumb-item"><a href="<?php echo base_url('pagar'); ?>">Pagar</a></li>

				<!-- **** Título -->
				<li class="breadcrumb-item active" aria-current="page"><?php echo $titulo; ?></li>
			</ol>
		</nav>

		<!-- Fim, Tabela de usuário - MENUS BREADCRUMB -->

		<!-- DataTales Example -->
		<div class="card shadow mb-4">

			<div class="card-body">

				<!-- Formulário -->
				<form class="user" method="POST" name="form_edit">

					<!-- Evento da alteração da data -->
					<p><strong><i class="fas fa-clock"></i>&nbsp;&nbsp;Última
							alteração:&nbsp;
						</strong><?php echo formata_data_banco_com_hora($conta_pagar->conta_pagar_data_alteracao); ?>
					</p>
					<!-- Fim, Evento da alteração da data -->

					<fieldset class="mt-4 border p-2">

						<legend class="font-small"><i class="fas fa-money-bill-alt">&nbsp; Dados da conta</i></legend>

						<div class="form-group row mb-3">

							<div class="col-md-4 mb-3">
								<label>Fornecedor</label>

								<select class="custom-select contas_pagar" name="conta_pagar_fornecedor_id">

									<?php foreach ($fornecedores as $fornecedor): ?>

										<option value="<?php echo $fornecedor->fornecedor_id ?>"<?php echo($fornecedor->fornecedor_id == $conta_pagar->conta_pagar_fornecedor_id ? 'selected' : '') ?> <?php echo($fornecedor->fornecedor_ativo == 0 ? 'disabled' : ''); ?>><?php echo $fornecedor->fornecedor_nome_fantasia; ?></option>

									<?php endforeach; ?>

								</select>
								<?php echo form_error('conta_pagar_fornecedor_id', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

							<div class="col-md-3 mb-3">
								<label>Data de vencimento</label>

								<input type="date" class="form-control form-control-user-date"
									   name="conta_pagar_data_vencimento"
									   value="<?php echo $conta_pagar->conta_pagar_data_vencimento; ?>">
								<?php echo form_error('conta_pagar_data_vencimento', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

							<div class="col-md-2 mb-3">
								<label>Valor da conta</label>

								<input type="text" class="form-control form-control-user money2"
									   name="conta_pagar_valor"
									   value="<?php echo $conta_pagar->conta_pagar_valor; ?>">
								<?php echo form_error('conta_pagar_valor', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

							<div class="col-md-3 mb-3">
								<label>Situação</label>

								<select class="custom-select" name="conta_pagar_status">

									<option value="1"<?php echo ($conta_pagar->conta_pagar_status == 1 ? 'selected' : '') ?>>Paga</option>
									<option value="0"<?php echo ($conta_pagar->conta_pagar_status == 0 ? 'selected' : '') ?>>Pendente</option>

								</select>

							</div>

						</div>

						<div class="form-group row mb-3">

							<div class="col-md-12 mb-3">
								<label>Observações da conta</label>

							<textarea class="form-control" name="conta_pagar_obs"><?php echo $conta_pagar->conta_pagar_obs ;?></textarea>
								<?php echo form_error('conta_pagar_obs', '<small class="form-text text-danger">', '</small>'); ?>

							</div>



						</div>

					</fieldset>


					<div class="form-group row mb-3">
						<input type="hidden" name="conta_pagar_id" value="<?php echo $conta_pagar->conta_pagar_id; ?>"/>
					</div>

					<button type="submit" class="btn btn-primary btn-sm" <?php echo ($conta_pagar->conta_pagar_status == 1 ? 'disabled' : '') ?>><?php echo ($conta_pagar->conta_pagar_status == 1 ? 'Conta paga' : 'Enviar') ?></button>

					<a title="Voltar" href="<?php echo base_url('pagar'); ?>"
					   class="btn btn-success btn-sm ml-3">&nbsp;Voltar</a>
				</form>

				<!-- Fim, Formulário -->
			</div>
		</div>
		<!-- /.container-fluid -->

	</div>
	<!-- End of Main Content -->

</div>
