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
				<li class="breadcrumb-item"><a href="<?php echo base_url('produtos'); ?>">Produtos</a></li>

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

					<fieldset class="mt-4 border p-2">

						<legend class="font-small"><i class="fas fa-product-hunt">&nbsp; Dados principais</i></legend>

						<div class="form-group row mb-3">

							<div class="col-md-2">
								<label>Código interno do produto</label>
								<input type="text" class="form-control form-control-user" name="produto_codigo"
									   value="<?php echo $produto_codigo; ?>" readonly="">
							</div>


							<div class="col-md-10">
								<label>Descrição do produto</label>
								<input type="text" class="form-control form-control-user" name="produto_descricao"
									   value="<?php echo set_value('produto_descricao'); ?>">
								<?php echo form_error('produto_preco', '<small class="form-text text-danger">', '</small>'); ?>
							</div>


						</div>

						<div class="form-group row mb-3">

							<div class="col-md-3">
								<label>Marca</label>

								<select class="custom-select" name="produto_marca_id">

									<?php foreach ($marcas as $marca): ?>
										<option value="<?php echo $marca->marca_id ?>"><?php echo $marca->marca_nome; ?></option>
									<?php endforeach; ?>

								</select>

							</div>

							<div class="col-md-3">
								<label>Categoria</label>

								<select class="custom-select" name="produto_categoria_id">

									<?php foreach ($categorias as $categoria): ?>
										<option value="<?php echo $categoria->categoria_id ?>" <?php echo($categoria->categoria_ativa == 0 ? 'disabled' : ''); ?>><?php echo $categoria->categoria_nome; ?></option>
									<?php endforeach; ?>

								</select>

							</div>

							<div class="col-md-3">
								<label>Fornecedor</label>

								<select class="custom-select" name="produto_fornecedor_id">

									<?php foreach ($fornecedores as $fornecedor): ?>

										<option value="<?php echo $fornecedor->fornecedor_id ?>"<?php echo($fornecedor->fornecedor_ativo == 0 ? 'disabled' : ''); ?>><?php echo $fornecedor->fornecedor_nome_fantasia; ?></option>

									<?php endforeach; ?>

								</select>

							</div>

							<div class="col-md-3">
								<label>Produto unidade</label>

								<input type="text" class="form-control form-control-user" name="produto_unidade"
									   value="<?php echo set_value('produto_unidade'); ?>">
								<?php echo form_error('produto_unidade', '<small class="form-text text-danger">', '</small>'); ?>

							</div>

						</div>

					</fieldset>

					<fieldset class="mt-4 border p-2">

						<legend class="font-small"><i class="fas fa-funnel-dollar">&nbsp; Precificação e estoque</i>
						</legend>

						<div class="form-group row mb-3">

							<div class="col-md-3">
								<label>Preço de custo</label>
								<input type="text" class="form-control form-control-user money"
									   name="produto_preco_custo"
									   value="<?php echo set_value('produto_preco_custo'); ?>">
								<?php echo form_error('produto_preco_custo', '<small class="form-text text-danger">', '</small>'); ?>
							</div>

							<div class="col-md-3">
								<label>Preço venda</label>
								<input type="text" class="form-control form-control-user money"
									   name="produto_preco_venda"
									   value="<?php echo set_value('produto_preco_venda'); ?>">
								<?php echo form_error('produto_preco_venda', '<small class="form-text text-danger">', '</small>'); ?>
							</div>

							<div class="col-md-3">
								<label>Estoque mínimo</label>
								<input type="number" class="form-control form-control-user"
									   name="produto_estoque_minimo"
									   value="<?php echo set_value('produto_estoque_minimo'); ?>">
								<?php echo form_error('produto_estoque_minimo', '<small class="form-text text-danger">', '</small>'); ?>
							</div>

							<div class="col-md-3">
								<label>Quantidade em estoque</label>
								<input type="number" class="form-control form-control-user" name="produto_qtde_estoque"
									   value="<?php echo set_value('produto_qtde_estoque'); ?>">
								<?php echo form_error('produto_qtde_estoque', '<small class="form-text text-danger">', '</small>'); ?>
							</div>

						</div>

						<div class="form-group row mb-3">
							<div class="col-md-3">
								<label>Produto ativo</label>
								<select name="produto_ativo" class="custom-select">
									<option value="0">
										Não
									</option>
									<option value="1">
										Sim
									</option>
								</select>

							</div>

							<div class="col-md-9">
								<label>Observação do produto</label>
								<textarea class="form-control" name="produto_obs"><?php echo set_value('produto_obs'); ?></textarea>
							</div>
						</div>

					</fieldset>

					<button type="submit" class="btn btn-primary btn-sm">Enviar</button>
					<a title="Voltar" href="<?php echo base_url('produtos'); ?>"
					   class="btn btn-success btn-sm ml-3">&nbsp;Voltar</a>
				</form>

				<!-- Fim, Formulário -->
			</div>
		</div>
		<!-- /.container-fluid -->

	</div>
	<!-- End of Main Content -->

</div>
