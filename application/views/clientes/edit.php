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
				<li class="breadcrumb-item"><a href="<?php echo base_url('clientes'); ?>">Clientes</a></li>

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
						</strong><?php echo formata_data_banco_com_hora($cliente->cliente_data_alteracao); ?></p>
					<!-- Fim, Evento da alteração da data -->

					<fieldset class="mt-4 border p-2">

						<legend class="font-small"><i class="fas fa-user-tie">&nbsp; Dados pessoais</i></legend>

						<div class="form-group row mb-3">

							<!-- Nome -->
							<div class="col-md-3">
								<label>Nome (*)</label>
								<input type="text" class="form-control form-control-user" name="cliente_nome"
									   placeholder="Nome do cliente"
									   value="<?php echo $cliente->cliente_nome; ?>">
								<?php echo form_error('cliente_nome', '<small class="form-text text-danger">', '</small>'); ?>
							</div>

							<!-- Sobrenome -->
							<div class="col-md-2">
								<label>Sobrenome (*)</label>
								<input type="text" class="form-control form-control-user" name="cliente_sobrenome"
									   placeholder="Sobrenome do cliente"
									   value="<?php echo $cliente->cliente_sobrenome; ?>">
								<?php echo form_error('cliente_sobrenome', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

							<!-- E-mail -->
							<div class="col-md-2">
								<label>E-mail (*)</label>
								<input type="email" class="form-control form-control-user" name="cliente_email"
									   value="<?php echo $cliente->cliente_email; ?>">
								<?php echo form_error('cliente_email', '<small class="form-text text-danger">', '</small>'); ?>
							</div>

							<!-- Data de nascimento -->
							<div class="col-md-3">
								<label>Data de nascimento (*)</label>
								<input type="date" class="form-control form-control-user-date"
									   name="cliente_data_nascimento"
									   value="<?php echo $cliente->cliente_data_nascimento; ?>">
								<?php echo form_error('cliente_data_nascimento', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

							<!-- Telefone fixo -->
							<div class="col-md-2">
								<label>Telefone fixo</label>
								<input type="text" class="form-control form-control-user" name="cliente_telefone"
									   placeholder="Telefone fixo"
									   value="<?php echo $cliente->cliente_telefone; ?>">
								<?php echo form_error('cliente_telefone', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

						</div>

						<div class="form-group row mb-3">

<!--							<!-- Telefone móvel -->
<!--							<div class="col-md-3">-->
<!--								<label>Telefone móvel</label>-->
<!--								<input type="text" class="form-control form-control-user phone_with_ddd" name="cliente_celular"-->
<!--									   placeholder="Telefone móvel"-->
<!--									   value="--><?php //echo $cliente->cliente_celular; ?><!--">-->
<!--								--><?php //echo form_error('cliente_celular', '<small class="form-text text-danger">', '</small>'); ?>
<!---->
<!--							</div>-->



						</div>


					</fieldset>


					<fieldset class="mt-4 border p-2">
						<legend class="font-small"><i class="fas fa-map-marker-alt">&nbsp; Dados de endereço</i>
						</legend>

						<div class="form-group row mb-3">

							<!-- Endereço -->
							<div class="col-md-4">
								<label>Endereço (*)</label>
								<input type="text" class="form-control form-control-user" name="cliente_endereco"
									   placeholder="Cliente endereço"
									   value="<?php echo $cliente->cliente_endereco; ?>">
								<?php echo form_error('cliente_endereco', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

							<!-- cliente_numero_endereco -->
							<div class="col-md-4">
								<label>Número</label>
								<input type="text" class="form-control form-control-user"
									   name="cliente_numero_endereco"
									   value="<?php echo $cliente->cliente_numero_endereco; ?>">
								<?php echo form_error('cliente_numero_endereco', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

							<!-- Complemento -->
							<div class="col-md-4">
								<label>Complemento</label>
								<input type="text" class="form-control form-control-user"
									   name="cliente_complemento"
									   value="<?php echo $cliente->cliente_complemento; ?>">
								<?php echo form_error('cliente_complemento', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

						</div>

						<div class="form-group row mb-3">

							<!-- Bairro -->
							<div class="col-md-4">
								<label>Bairro (*)</label>
								<input type="text" class="form-control form-control-user" name="cliente_bairro"
									   value="<?php echo $cliente->cliente_bairro; ?>">
								<?php echo form_error('cliente_bairro', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

							<!-- CEP -->
<!--							<div class="col-md-2">-->
<!--								<label>CEP</label>-->
<!--								<input type="text" class="form-control form-control-user" name="cliente_cep"-->
<!--									   value="--><?php //echo $cliente->cliente_cep; ?><!--">-->
<!--								--><?php //echo form_error('cliente_cep', '<small class="form-text text-danger">', '</small>'); ?>
<!--							</div>-->

							<!-- CPF OU CNPJ -->
<!--							<div class="col-md-2">-->
<!---->
<!--								--><?php //if ($cliente->cliente_tipo == 1): ?>
<!--									<label>CPF</label>-->
<!--									<input type="text" class="form-control form-control-user" name="cliente_cpf"-->
<!--										   placeholder="--><?php //echo ($cliente->cliente_tipo == 1 ? 'CPF do cliente' : 'CNPJ do cliente');?><!--"-->
<!--										   value="--><?php //echo $cliente->cliente_cpf_cnpj; ?><!--">-->
<!--									--><?php //echo form_error('cliente_cpf', '<small class="form-text text-danger">', '</small>'); ?>
<!---->
<!--								--><?php //else: ?>
<!--									<label>CNPJ</label>-->
<!---->
<!--									<input type="text" class="form-control form-control-user" name="cliente_cnpj"-->
<!--										   placeholder="--><?php //echo ($cliente->cliente_tipo == 1 ? 'CPF do cliente' : 'CNPJ do cliente');?><!--"-->
<!--										   value="--><?php //echo $cliente->cliente_cpf_cnpj; ?><!--">-->
<!--									--><?php //echo form_error('cliente_cnpj', '<small class="form-text text-danger">', '</small>'); ?>
<!--								--><?php //endif; ?>
<!--							</div>-->

							<!-- Cidade -->
							<div class="col-md-4">
								<label>Cidade (*)</label>
								<input type="text" class="form-control form-control-user" name="cliente_cidade"
									   value="<?php echo $cliente->cliente_cidade; ?>">
								<?php echo form_error('cliente_cidade', '<small class="form-text text-danger">', '</small>'); ?>
							</div>

							<!-- Sigla -->
							<div class="col-md-4">
								<label>Sigla (*)</label>
								<input type="text" class="form-control form-control-user Sigla" name="cliente_estado"
									   value="<?php echo $cliente->cliente_estado; ?>">
								<?php echo form_error('cliente_estado', '<small class="form-text text-danger">', '</small>'); ?>

							</div>


						</div>

					</fieldset>


					<fieldset class="mt-4 border p-2">
						<legend class="font-small"><i class="fas fa-tools">&nbsp; Configurações</i></legend>

						<div class="form-group row">
							<!-- Estado do cliente -->
							<div class="col-md-4">
								<label>Cliente ativo</label>
								<select name="cliente_ativo" class="custom-select">
									<option value="0" <?php echo($cliente->cliente_ativo == 0 ? 'selected' : ''); ?>>
										Não
									</option>
									<option value="1" <?php echo($cliente->cliente_ativo == 1 ? 'selected' : ''); ?>>
										Sim
									</option>
								</select>

							</div>

							<!-- Obs -->
							<div class="col-md-8">
								<label>Observações</label>
								<textarea class="form-control form-control-user"
										  name="cliente_obs"><?php echo $cliente->cliente_obs; ?></textarea>
								<?php echo form_error('cliente_obs', '<small class="form-text text-danger">', '</small>'); ?>

							</div>
						</div>
					</fieldset>


					<div class="form-group row mb-3">

						<input type="hidden" name="cliente_tipo" value="<?php echo $cliente->cliente_ativo; ?>"/>
						<input type="hidden" name="cliente_id" value="<?php echo $cliente->cliente_id; ?>"/>

					</div>

					<button type="submit" class="btn btn-primary btn-sm">Salvar</button>
					<a title="Voltar" href="<?php echo base_url('clientes'); ?>"
					   class="btn btn-success btn-sm ml-3">&nbsp;Voltar</a>
				</form>

				<!-- Fim, Formulário -->
			</div>
		</div>
		<!-- /.container-fluid -->

	</div>
	<!-- End of Main Content -->

</div>
