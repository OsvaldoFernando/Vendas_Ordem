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
				<li class="breadcrumb-item"><a href="<?php echo base_url('fornecedores'); ?>">Fornecedores</a></li>

				<!-- **** Título -->
				<li class="breadcrumb-item active" aria-current="page"><?php echo $titulo; ?></li>
			</ol>
		</nav>

		<!-- Fim, Tabela de usuário - MENUS BREADCRUMB -->

		<!-- DataTales Example -->
		<div class="card shadow mb-4">

			<div class="card-body">

				<!-- Formulário -->
				<form class="user" method="POST" name="form_add">

					<?php if (isset($fornecedor)): ?>

						<!-- Evento da alteração da data -->
						<p><strong><i class="fas fa-clock"></i>&nbsp;&nbsp;Última
								alteração:&nbsp;
							</strong><?php echo formata_data_banco_com_hora($fornecedor->fornecedor_data_alteracao); ?>
						</p>
						<!-- Fim, Evento da alteração da data -->

					<?php endif; ?>


					<fieldset class="mt-4 border p-2">

						<legend class="font-small"><i class="fas fa-user-tie">&nbsp; Dados</i></legend>

						<div class="form-group row mb-3">

							<div class="col-md-3">
								<label>Razão social</label>
								<input type="text" class="form-control form-control-user" name="fornecedor_razao" value="<?php echo set_value('fornecedor_razao'); ?>">

								<?php echo form_error('fornecedor_razao', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

							<div class="col-md-3">
								<label>Nome fantasia</label>
								<input type="text" class="form-control form-control-user"
									   name="fornecedor_nome_fantasia"
									   value="<?php echo set_value('fornecedor_nome_fantasia'); ?>">
								<?php echo form_error('fornecedor_nome_fantasia', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

							<div class="col-md-3">
								<label>Telefone</label>
								<input type="text" class="form-control form-control-user" name="fornecedor_telefone"
									   value="<?php echo set_value('fornecedor_telefone'); ?>">
								<?php echo form_error('fornecedor_telefone', '<small class="form-text text-danger">', '</small>'); ?>
							</div>


							<div class="col-md-3">
								<label>E-mail</label>
								<input type="text" class="form-control form-control-user" name="fornecedor_email"
									   value="<?php echo set_value('fornecedor_email'); ?>">
								<?php echo form_error('fornecedor_email', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

						</div>

					</fieldset>

					<fieldset class="mt-4 border p-2">
						<legend class="font-small"><i class="fas fa-map-marker-alt">&nbsp; Outros Contato</i></legend>

						<div class="form-group row mb-3">

							<div class="col-md-3">
								<label>Endereço</label>
								<input type="text" class="form-control form-control-user" name="fornecedor_endereco"
									   value="<?php echo set_value('fornecedor_endereco'); ?>">
								<?php echo form_error('fornecedor_endereco', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

							<div class="col-md-3">
								<label>Bairro</label>
								<input type="text" class="form-control form-control-user" name="fornecedor_bairro"
									   value="<?php echo set_value('fornecedor_bairro'); ?>">
								<?php echo form_error('fornecedor_bairro', '<small class="form-text text-danger">', '</small>'); ?>
							</div>

							<div class="col-md-3">
								<label>Complemento</label>
								<input type="text" class="form-control form-control-user" name="fornecedor_complemento"
									   value="<?php echo set_value('fornecedor_complemento'); ?>">
								<?php echo form_error('fornecedor_complemento', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

							<div class="col-md-3">
								<label>Cidade</label>
								<input type="text" class="form-control form-control-user" name="fornecedor_cidade"
									   value="<?php echo set_value('fornecedor_cidade'); ?>">
								<?php echo form_error('fornecedor_cidade', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

						</div>

					</fieldset>


					<fieldset class="mt-4 border p-2">
						<legend class="font-small"><i class="fas fa-tools">&nbsp; Configurações</i></legend>

						<div class="form-group row">
							<!-- Estado do cliente -->
							<div class="col-md-4">
								<label>Fornecedor ativo</label>
								<select name="fornecedor_ativo" class="custom-select">
									<option value="0">Não</option>
									<option value="1">Sim</option>
								</select>

							</div>

							<!-- Obs -->
							<div class="col-md-8">
								<label>Observações</label>
								<textarea class="form-control form-control-user"
										  name="fornecedor_obs"><?php echo set_value('fornecedor_obs'); ?></textarea>
								<?php echo form_error('fornecedor_obs', '<small class="form-text text-danger">', '</small>'); ?>

							</div>
						</div>

					</fieldset>

					<button type="submit" class="btn btn-primary btn-sm">Enviar</button>
					<a title="Voltar" href="<?php echo base_url('fornecedores'); ?>"
					   class="btn btn-success btn-sm ml-3">&nbsp;Voltar</a>
				</form>

				<!-- Fim, Formulário -->
			</div>
		</div>
		<!-- /.container-fluid -->

	</div>
	<!-- End of Main Content -->

</div>
