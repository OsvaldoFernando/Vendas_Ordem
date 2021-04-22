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
				<li class="breadcrumb-item"><a href="<?php echo base_url('categorias'); ?>">Categorias</a></li>

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
						</strong><?php echo formata_data_banco_com_hora($categoria->categoria_data_alteracao); ?></p>
					<!-- Fim, Evento da alteração da data -->

					<fieldset class="mt-4 border p-2">

						<legend class="font-small"><i class="fab fa-buffer">&nbsp; Dados da categoria</i></legend>

						<div class="form-group row mb-3">

							<div class="col-md-8">
								<label>Nome da categoria</label>
								<input type="text" class="form-control form-control-user" name="categoria_nome"
									   value="<?php echo $categoria->categoria_nome; ?>">
								<?php echo form_error('categoria_nome', '<small class="form-text text-danger">', '</small>'); ?>
							</div>

							<div class="col-md-4">
								<label>Categoria ativa</label>
								<select name="categoria_ativa" class="custom-select">
									<option value="0" <?php echo($categoria->categoria_ativa == 0 ? 'selected' : ''); ?>>
										Não
									</option>
									<option value="1" <?php echo($categoria->categoria_ativa == 1 ? 'selected' : ''); ?>>
										Sim
									</option>
								</select>

							</div>

						</div>

					</fieldset>


					<div class="form-group row mb-3">
						<input type="hidden" name="categoria_id" value="<?php echo $categoria->categoria_id; ?>"/>
					</div>

					<button type="submit" class="btn btn-primary btn-sm">Enviar</button>
					<a title="Voltar" href="<?php echo base_url('categorias'); ?>"
					   class="btn btn-success btn-sm ml-3">&nbsp;Voltar</a>
				</form>

				<!-- Fim, Formulário -->
			</div>
		</div>
		<!-- /.container-fluid -->

	</div>
	<!-- End of Main Content -->

</div>
