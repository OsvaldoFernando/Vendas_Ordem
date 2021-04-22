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
				<li class="breadcrumb-item"><a href="<?php echo base_url('/'); ?>">Home</a></li>

				<!-- **** Título -->
				<li class="breadcrumb-item active" aria-current="page"><?php echo $titulo; ?></li>
			</ol>
		</nav>
		<!-- Fim, Tabela de usuário - MENUS BREADCRUMB -->

		<!-- Printar mensagem de sucesso -->
		<?php if ($message = $this->session->flashdata('sucesso')): ?>

			<!-- Div para limitar uma determinada linha -->
			<div class="row">

				<!-- Div para preencher a informação em toda tela -->
				<div class="col-md-12">

					<!-- Alert -->
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<strong><i class="far fa-smile-wink"></i>&nbsp;&nbsp;<?php echo $message ?></strong>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

				</div>

			</div>

		<?php endif; ?>
		<!-- Fim, Printar mensagem de sucesso-->


		<!-- Printar mensagem de erro -->
		<?php if ($message = $this->session->flashdata('error')): ?>

			<!-- Div para limitar uma determinada linha -->
			<div class="row">

				<!-- Div para preencher a informação em toda tela -->
				<div class="col-md-12">

					<!-- Alert -->
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<strong><i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;<?php echo $message ?></strong>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

				</div>

			</div>

		<?php endif; ?>

		<!-- Fim, Printar mensagem de erro -->

		<!-- DataTales Example -->
		<div class="card shadow mb-4">
			<div class="card-header py-3">
			</div>

			<div class="card-body">
				<!-- Formulário -->
				<form class="user" name="form_edit" method="post">

					<!-- PRIMEIRA LINHA COM 4 COLUNA -->
					<div class="form-group row mb-3">

						<!-- Razão social -->
						<div class="col-md-3">
							<label>Razão social (*)</label>
							<input type="text" class="form-control form-control-user"  name="sistema_razao_social"
								   placeholder="Informe a razão social"
								   value="<?php echo $sistema->sistema_razao_social; ?>">
							<?php echo form_error('sistema_razao_social', '<small class="form-text text-danger">', '</small>'); ?>
						</div>

						<!-- Nome fantasia -->
						<div class="col-md-3">
							<label>Nome fantasia (*)</label>
							<input type="text" class="form-control form-control-user" name="sistema_nome_fantasia"
								   placeholder="Nome fantasia"
								   value="<?php echo $sistema->sistema_nome_fantasia; ?>">
							<?php echo form_error('sistema_nome_fantasia', '<small class="form-text text-danger">', '</small>'); ?>
						</div>

						<!-- NIF ou BI-->
						<div class="col-md-3">
							<label>NIF ou Bilhete (*)</label>
							<input type="text" class="form-control form-control-user" name="sistema_nif" placeholder="NIF"
								   value="<?php echo $sistema->sistema_nif; ?>">
							<?php echo form_error('sistema_nif', '<small class="form-text text-danger">', '</small>'); ?>
						</div>

						<!-- sistema_num_agt-->
						<div class="col-md-3">
							<label>Número AGT (*)</label>
							<input type="text" class="form-control form-control-user" name="sistema_num_agt" placeholder="Número AGT"
								   value="<?php echo $sistema->sistema_num_agt; ?>">
							<?php echo form_error('sistema_num_agt', '<small class="form-text text-danger">', '</small>'); ?>
						</div>
					</div>

					<!-- FIM, PRIMEIRA LINHA COM 4 COLUNA -->

					<!-- SEGUNDA LINHA COM 4 COLUNA -->
					<div class="form-group row mb-3">

						<!-- Telefone fixo-->
						<div class="col-md-3">
							<label>Telefone fixo</label>
							<input type="text" class="form-control form-control-user" name="sistema_telefone_fixo"
								   placeholder="Telefone fixo"
								   value="<?php echo $sistema->sistema_telefone_fixo; ?>">
							<?php echo form_error('sistema_telefone_fixo', '<small class="form-text text-danger">', '</small>'); ?>
						</div>

						<!-- Telefone móvel -->
						<div class="col-md-3">
							<label>Telefone móvel (*)</label>
							<input type="text" class="form-control form-control-user" name="sistema_telefone_movel"
								   placeholder="Telefone móvel"
								   value="<?php echo $sistema->sistema_telefone_movel; ?>">
							<?php echo form_error('sistema_telefone_movel', '<small class="form-text text-danger">', '</small>'); ?>
						</div>

						<!-- URL do Site-->
						<div class="col-md-3">
							<label>URL do site (*)</label>
							<input type="text" class="form-control form-control-user" name="sistema_site_url" placeholder="URL do site"
								   value="<?php echo $sistema->sistema_site_url; ?>">
							<?php echo form_error('sistema_site_url', '<small class="form-text text-danger">', '</small>'); ?>
						</div>

						<!-- E-mail-->
						<div class="col-md-3">
							<label>E-mail de contato (*)</label>
							<input type="text" class="form-control form-control-user" name="sistema_email" placeholder="E-mail de contato"
								   value="<?php echo $sistema->sistema_email; ?>">
							<?php echo form_error('sistema_email', '<small class="form-text text-danger">', '</small>'); ?>
						</div>
					</div>

					<!-- FIM, SEGUNDA LINHA COM 4 COLUNA -->

					<!-- TERCEIRA LINHA COM 5 COLUNA -->
					<div class="form-group row mb-3">

						<!-- Endereço-->
						<div class="col-md-3">
							<label>Endereço</label>
							<input type="text" class="form-control form-control-user" name="sistema_endereco"
								   placeholder="Sistema endereço"
								   value="<?php echo $sistema->sistema_endereco; ?>">
							<?php echo form_error('sistema_endereco', '<small class="form-text text-danger">', '</small>'); ?>
						</div>

						<!-- CEP -->
						<div class="col-md-2">
							<label>CEP</label>
							<input type="text" class="form-control form-control-user cep" name="sistema_cep" placeholder="CEP"
								   value="<?php echo $sistema->sistema_cep; ?>">
							<?php echo form_error('sistema_cep', '<small class="form-text text-danger">', '</small>'); ?>
						</div>

						<!-- Número-->
						<div class="col-md-2">
							<label>Número</label>
							<input type="text" class="form-control form-control-user" name="sistema_numero" placeholder="Número"
								   value="<?php echo $sistema->sistema_numero; ?>">
							<?php echo form_error('sistema_numero', '<small class="form-text text-danger">', '</small>'); ?>
						</div>

						<!-- Cidade-->
						<div class="col-md-2">
							<label>Cidade (*) </label>
							<input type="text" class="form-control form-control-user" name="sistema_cidade" placeholder="Cidade"
								   value="<?php echo $sistema->sistema_cidade; ?>">
							<?php echo form_error('sistema_cidade', '<small class="form-text text-danger">', '</small>'); ?>
						</div>

						<!-- Sigla-->
						<div class="col-md-2">
							<label>Siga (*)</label>
							<input type="text" class="form-control form-control-user Sigla" name="sistema_estado" placeholder="Estado"
								   value="<?php echo $sistema->sistema_estado; ?>">
							<?php echo form_error('sistema_estado', '<small class="form-text text-danger">', '</small>'); ?>
						</div>

					</div>

					<!-- FIM, TERCEIRA LINHA COM 5 COLUNA -->


					<!-- QUINTA LINHA COM 1 COLUNA -->
					<div class="form-group row mb-3">

						<!-- Texto da ordem de serviço e venda-->
						<div class="col-md-12">
							<label>Texto da ordem de serviço e venda</label>
							<textarea class="form-control form-control-user" name="sistema_txt_ordem_servico"
									  placeholder="Texto da ordem de serviço e venda"><?php echo $sistema->sistema_txt_ordem_servico; ?></textarea>
							<?php echo form_error('sistema_txt_ordem_servico', '<small class="form-text text-danger">', '</small>'); ?>
						</div>

					</div>

					<!-- FIM, QUINTA LINHA COM 1 COLUNA -->
					<button type="submit" class="btn btn-primary btn-sm">Salvar</button>
				</form>
				<!-- Fim, Formulário -->
			</div>
		</div>
		<!-- /.container-fluid -->
	</div>
	<!-- End of Main Content -->
</div>
