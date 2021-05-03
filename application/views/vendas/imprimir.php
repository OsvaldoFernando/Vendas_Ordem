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
				<li class="breadcrumb-item"><a href="<?php echo base_url('os'); ?>">Vendas</a></li>

				<!-- **** Título -->
				<li class="breadcrumb-item active" aria-current="page"><?php echo $titulo; ?></li>
			</ol>
		</nav>

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

		<!-- Fim, Tabela de usuário - MENUS BREADCRUMB -->

		<!-- DataTales Example -->
		<div class="card shadow mb-4">

			<div class="card-body">

				<div class="text-center">

					<a title="Imprimir venda"
					   href="<?php echo base_url('vendas/pdf/' . $venda->venda_id); ?>"
					   class="btn btn-dark btn-icon-split btn-lg">
                    <span class="icon text-white-50">
                      <i class="fas fa-print"></i>
                    </span>
						<span class="text">Imprimir venda</span>
					</a>&nbsp;&nbsp;&nbsp;


					<a title="Cadastrar venda" href="<?php echo base_url('vendas/add'); ?>"
					   class="btn btn-success btn-icon-split btn-lg">
                    <span class="icon text-white-50">
                      <i class="fas fa-plus"></i>
                    </span>
						<span class="text">Nova venda</span>
					</a>&nbsp;&nbsp;&nbsp;


					<a title="Listar venda" href="<?php echo base_url('vendas'); ?>"
					   class="btn btn-info btn-icon-split btn-lg">
                    <span class="icon text-white-50">
                      <i class="fas fa-list-ol"></i>
                    </span>
						<span class="text">Listar vendas</span>
					</a>&nbsp;&nbsp;&nbsp;


				</div>
			</div>
			<!-- /.container-fluid -->

		</div>
		<!-- End of Main Content -->

	</div>
