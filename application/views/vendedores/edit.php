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
				<li class="breadcrumb-item"><a href="<?php echo base_url('fornecedores'); ?>">Vendedores</a></li>

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
						</strong><?php echo formata_data_banco_com_hora($vendedor->vendedor_data_alteracao); ?></p>
					<!-- Fim, Evento da alteração da data -->

					<fieldset class="mt-4 border p-2">

						<legend class="font-small"><i class="fas fa-user-secret">&nbsp; Dados pessoais</i></legend>

						<div class="form-group row mb-3">

							<div class="col-md-6 mb-3">
								<label>Nome completo</label>
								<input type="text" class="form-control form-control-user" name="vendedor_nome_completo"
									   value="<?php echo $vendedor->vendedor_nome_completo; ?>">
								<?php echo form_error('vendedor_nome_completo', '<small class="form-text text-danger">', '</small>'); ?>
							</div>


							<div class="col-md-3">
								<label>Telefone</label>
								<input type="text" class="form-control form-control-user" name="vendedor_telefone"
									   value="<?php echo $vendedor->vendedor_telefone; ?>">
								<?php echo form_error('vendedor_telefone', '<small class="form-text text-danger">', '</small>'); ?>
							</div>

							
							<div class="col-md-3">
								<label>E-mail</label>
								<input type="text" class="form-control form-control-user" name="vendedor_email"
									   value="<?php echo $vendedor->vendedor_email; ?>">
								<?php echo form_error('vendedor_email', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

						</div>

					</fieldset>

					<fieldset class="mt-4 border p-2">
						<legend class="font-small"><i class="fas fa-map-marker-alt">&nbsp; Outros Contato</i></legend>

						<div class="form-group row mb-3">

							<div class="col-md-3">
								<label>Endereço</label>
								<input type="text" class="form-control form-control-user" name="vendedor_endereco"
									   value="<?php echo $vendedor->vendedor_endereco; ?>">
								<?php echo form_error('vendedor_endereco', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

							<div class="col-md-3">
								<label>Bairro</label>
								<input type="text" class="form-control form-control-user" name="vendedor_bairro"
									   value="<?php echo $vendedor->vendedor_bairro; ?>">
								<?php echo form_error('vendedor_bairro', '<small class="form-text text-danger">', '</small>'); ?>
							</div>

							<div class="col-md-3">
								<label>Complemento</label>
								<input type="text" class="form-control form-control-user" name="vendedor_complemento"
									   value="<?php echo $vendedor->vendedor_complemento; ?>">
								<?php echo form_error('vendedor_complemento', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

							<div class="col-md-3">
								<label>Cidade</label>
								<input type="text" class="form-control form-control-user" name="vendedor_cidade"
									   value="<?php echo $vendedor->vendedor_cidade; ?>">
								<?php echo form_error('vendedor_cidade', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

						</div>

					</fieldset>


					<fieldset class="mt-4 border p-2">
						<legend class="font-small"><i class="fas fa-tools">&nbsp; Configurações</i></legend>

						<div class="form-group row">
							<!-- Estado do cliente -->
							<div class="col-md-3">
								<label>Vendedor ativo</label>
								<select name="vendedor_ativo" class="custom-select">
									<option value="0" <?php echo($vendedor->vendedor_ativo == 0 ? 'selected' : ''); ?>>
										Não
									</option>
									<option value="1" <?php echo($vendedor->vendedor_ativo == 1 ? 'selected' : ''); ?>>
										Sim
									</option>
								</select>

							</div>

							<div class="col-md-3">
								<label>Código</label>
								<input type="text" class="form-control form-control-user" name="vendedor_codigo"
									   value="<?php echo $vendedor->vendedor_codigo; ?>" readonly="">
								<?php echo form_error('vendedor_codigo', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

							<!-- Obs -->
							<div class="col-md-6">
								<label>Observações</label>
								<textarea class="form-control form-control-user"
										  name="vendedor_obs"><?php echo $vendedor->vendedor_obs; ?></textarea>
								<?php echo form_error('vendedor_obs', '<small class="form-text text-danger">', '</small>'); ?>

							</div>
						</div>

					</fieldset>



					<div class="form-group row mb-3">
						<input type="hidden" name="vendedor_id" value="<?php echo $vendedor->vendedor_id; ?>"/>
					</div>

					<button type="submit" class="btn btn-primary btn-sm">Enviar</button>
					<a title="Voltar" href="<?php echo base_url('vendedores'); ?>"
					   class="btn btn-success btn-sm ml-3">&nbsp;Voltar</a>
				</form>

				<!-- Fim, Formulário -->
			</div>
		</div>
		<!-- /.container-fluid -->

	</div>
	<!-- End of Main Content -->

</div>
