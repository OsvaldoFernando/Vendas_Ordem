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
				<li class="breadcrumb-item"><a href="<?php echo base_url('usuarios'); ?>">Usuários</a></li>

				<!-- **** Título -->
				<li class="breadcrumb-item active" aria-current="page"><?php echo $titulo; ?></li>
			</ol>
		</nav>

		<!-- Fim, Tabela de usuário - MENUS BREADCRUMB -->

		<!-- DataTales Example -->
		<div class="card shadow mb-4">
			<div class="card-header py-3">

				<!-- Botão -->
				<a title="Voltar" href="<?php echo base_url('usuarios'); ?>" class="btn btn-success btn-sm float-right"><i
							class="fas fa-arrow-left"></i>&nbsp;Voltar</a>

				<!-- Botão -->

			</div>

			<div class="card-body">

				<!-- Formulário -->
				<form method="POST" name="form_edit">

					<div class="form-group row">

						<!-- Nome -->
						<div class="col-md-4">
							<label>Nome</label>
							<input type="text" class="form-control" name="first_name" placeholder="Seu nome"
								   value="<?php echo set_value('first_name'); ?>">
							<?php echo form_error('first_name', '<small class="form-text text-danger">', '</small>'); ?>
						</div>

						<!-- Sobrenome -->
						<div class="col-md-4">
							<label>Sobrenome</label>
							<input type="text" class="form-control" name="last_name" placeholder="Seu sobrenome"
								   value="<?php echo set_value('last_name'); ?>">
							<?php echo form_error('last_name', '<small class="form-text text-danger">', '</small>'); ?>

						</div>

						<!-- E-mail -->
						<div class="col-md-4">
							<label>E-mail &nbsp;(Login)</label>
							<input type="email" class="form-control" name="email" placeholder="Seu email"
								   value="<?php echo set_value('email'); ?>">
							<?php echo form_error('email', '<small class="form-text text-danger">', '</small>'); ?>

						</div>

					</div>


					<div class="form-group row">

						<!-- Username -->
						<div class="col-md-4">
							<label>Usuário</label>
							<input type="text" class="form-control" name="username" placeholder="Seu usuário"
								   value="<?php echo set_value('username'); ?>">
							<?php echo form_error('username', '<small class="form-text text-danger">', '</small>'); ?>

						</div>


						<!-- Perfil se esta ativo ou não -->
						<div class="col-md-4">
							<label>Ativo</label>

							<select class="form-control" name="active">

								<option value="0">Não</option>
								<option value="1">Sim</option>

							</select>

						</div>


						<!-- Perfil de acesso -->
						<div class="col-md-4">
							<label>Perfil de acesso</label>

							<select class="form-control" name="perfil_usuario">

								<option value="2">Vendedor</option>
								<option value="1">Administrador</option>

							</select>

						</div>

					</div>


					<div class="form-group row">

						<!-- Senha do usuário -->
						<div class="col-md-6">
							<label>Senha</label>
							<input type="password" class="form-control" name="password" placeholder="Sua senha">
							<?php echo form_error('password', '<small class="form-text text-danger">', '</small>'); ?>

						</div>


						<!-- Confirmação da senha do usuário -->
						<div class="col-md-6">
							<label>Confirme</label>
							<input type="password" class="form-control" name="confirm_password"
								   placeholder="Confirme sua senha">
							<?php echo form_error('confirm_password', '<small class="form-text text-danger">', '</small>'); ?>

						</div>

					</div>

					<button type="submit" class="btn btn-primary btn-sm">Salvar</button>
				</form>

				<!-- Fim, Formulário -->
			</div>
		</div>
		<!-- /.container-fluid -->

	</div>
	<!-- End of Main Content -->
</div>
