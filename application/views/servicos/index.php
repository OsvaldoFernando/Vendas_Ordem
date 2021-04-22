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

				<!-- Botão -->
				<a title="Cadastrar novo serviço" href="<?php echo base_url('servicos/add'); ?>" class="btn btn-success btn-sm float-right"><i
							class="fas fa-plus"></i>&nbsp;Novo</a>

				<!-- Botão -->
<!--==========================================================================================================-->
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered dataTable " width="100%" cellspacing="0">
						<thead>
						<tr>
							<th >#</th>
							<th>Título do serviço</th>
							<th>Preço</th>
							<th>Descrição</th>
							<th class="text-center">Ativo</th>
							<th class="text-right no-sort pr-2">Operações</th>
						</tr>
						</thead>

						<tbody>
						<!-- Percorrer um ciclo -->
						<?php foreach ($servicos as $servico): ?>

							<tr>

								<!-- Mostrando informações de cada usuários-->

								<td> <?php echo $servico->servico_id ?></td>
								<td> <?php echo $servico->servico_nome ?></td>
								<td> <?php echo 'AKZ&nbsp;'.$servico->servico_preco ?></td>
								<td> <?php echo word_limiter($servico->servico_descricao, 10); ?></td>
								<td class="text-center pr-4"> <?php echo ($servico->servico_ativo == 1 ? '<span class="badge badge-info btn-sm">Sim</span>' : '<span class="badge badge-warning btn-sm">Não</span>') ?></td>

<!--***********************************************************************************************************************************-->
								<!-- Operações -->
								<td class="text-right">
									<a title="Editar" href="<?php echo base_url('servicos/edit/' . $servico->servico_id); ?>"
									   class="btn btn-sm btn-primary"><i class="fas fa-user-edit"></i></a>
									<a title="Excluir " href="javascript(void)" data-toggle="modal" data-target="#servico-<?php echo $servico->servico_id;?>" class="btn btn-sm btn-danger"><i
												class="fas fa-user-times"></i></a>
								</td>

							</tr>

							<!-- Logout Modal-->
							<div class="modal fade" id="servico-<?php echo $servico->servico_id;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
								 aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalLabel">Tem certeza da deleção</h5>
											<button class="close" type="button" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
										<div class="modal-body">Para excluir o registro clique em <strong>Sim</strong></div>
										<div class="modal-footer">
											<button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Não</button>
											<a class="btn btn-danger btn-sm" href="<?php echo base_url('servicos/del/' . $servico->servico_id) ;?>">Sim</a>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>


						</tbody>
					</table>
				</div>
			</div>
		</div>

	</div>

	<!-- Fim, tabela de usuário -->

</div>
<!-- /.container-fluid -->
