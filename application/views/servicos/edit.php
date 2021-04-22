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
				<li class="breadcrumb-item"><a href="<?php echo base_url('servicos'); ?>">Serviços</a></li>

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
						</strong><?php echo formata_data_banco_com_hora($servico->servico_data_alteracao); ?></p>
					<!-- Fim, Evento da alteração da data -->

					<fieldset class="mt-4 border p-2">

						<legend class="font-small"><i class="fas fa-laptop">&nbsp; Dados do serviço</i></legend>

						<div class="form-group row mb-3">

							<div class="col-md-6">
								<label>Nome do serviço</label>
								<input type="text" class="form-control form-control-user" name="servico_nome"
									   value="<?php echo $servico->servico_nome; ?>">
								<?php echo form_error('servico_nome', '<small class="form-text text-danger">', '</small>'); ?>
							</div>


							<div class="col-md-3">
								<label>Preço</label>
								<input type="text" class="form-control form-control-user money" name="servico_preco"
									   value="<?php echo $servico->servico_preco; ?>">
								<?php echo form_error('servico_preco', '<small class="form-text text-danger">', '</small>'); ?>
							</div>

							<div class="col-md-3">
								<label>Serviço ativo</label>
								<select name="servico_ativo" class="custom-select">
									<option value="0" <?php echo($servico->servico_ativo == 0 ? 'selected' : ''); ?>>
										Não
									</option>
									<option value="1" <?php echo($servico->servico_ativo == 1 ? 'selected' : ''); ?>>
										Sim
									</option>
								</select>

							</div>

						</div>

						<div class="form-group row mb-3">
							<div class="col-md-12">
								<label>Descrição do serviço</label>
								<textarea class="form-control form-control-user"
										  name="servico_descricao" style="min-height: 100px!important;"><?php echo $servico->servico_descricao; ?></textarea>
								<?php echo form_error('servico_descricao', '<small class="form-text text-danger">', '</small>'); ?>

							</div>
						</div>

					</fieldset>


					<div class="form-group row mb-3">
						<input type="hidden" name="servico_id" value="<?php echo $servico->servico_id; ?>"/>
					</div>

					<button type="submit" class="btn btn-primary btn-sm">Enviar</button>
					<a title="Voltar" href="<?php echo base_url('servicos'); ?>"
					   class="btn btn-success btn-sm ml-3">&nbsp;Voltar</a>
				</form>

				<!-- Fim, Formulário -->
			</div>
		</div>
		<!-- /.container-fluid -->

	</div>
	<!-- End of Main Content -->

</div>
