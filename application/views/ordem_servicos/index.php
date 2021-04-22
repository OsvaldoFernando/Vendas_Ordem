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
				<a title="Cadastrar nova ordem de serviço" href="<?php echo base_url('os/add'); ?>" class="btn btn-success btn-sm float-right"><i
							class="fas fa-shopping-basket"></i>&nbsp;Nova</a>

				<!-- Botão -->
<!--==========================================================================================================-->
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered dataTable " width="100%" cellspacing="0">
						<thead>
						<tr>
							<th >#</th>
							<th>Data emissão</th>
							<th>Cliente</th>
							<th>Forma de pagamento</th>
							<th>Valor total</th>
							<th class="text-center">Situação</th>
							<th class="text-right no-sort pr-2">Operações</th>
						</tr>
						</thead>

						<tbody>
						<!-- Percorrer um ciclo -->
						<?php foreach ($ordens_servicos as $os): ?>

							<tr>

								<!-- Mostrando informações de cada usuários-->

								<td> <?php echo $os->ordem_servico_id; ?></td>
								<td> <?php echo formata_data_banco_com_hora($os->ordem_servico_data_emissao); ?></td>
								<td> <?php echo $os->cliente_nome; ?></td>
								<td> <?php echo ($os->ordem_servico_status == 1 ? $os->forma_pagamento : 'Em aberto'); ?></td>
								<td> <?php echo 'AKZ&nbsp;'.$os->ordem_servico_valor_total; ?></td>
								<td class="text-center pr-4"> <?php echo ($os->ordem_servico_status == 1 ? '<span class="badge badge-info btn-sm">Paga</span>' : '<span class="badge badge-warning btn-sm">Em aberto</span>') ?></td>

<!--***********************************************************************************************************************************-->
								<!-- Operações -->
								<td class="text-right">
									<a title="Imprimir" href="<?php echo base_url('os/pdf/' . $os->ordem_servico_id); ?>"class="btn btn-sm btn-dark"><i class="fas fa-print"></i></a>
									<a title="Editar" href="<?php echo base_url('os/edit/' . $os->ordem_servico_id); ?>" class="btn btn-sm btn-primary"><i class="fas fa-user-edit"></i></a>
									<a title="Excluir " href="javascript(void)" data-toggle="modal" data-target="#os-<?php echo $os->ordem_servico_id;?>" class="btn btn-sm btn-danger"><i
												class="fas fa-user-times"></i></a>
								</td>

							</tr>

							<!-- Logout Modal-->
							<div class="modal fade" id="os-<?php echo $os->ordem_servico_id;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
											<a class="btn btn-danger btn-sm" href="<?php echo base_url('os/del/' . $os->ordem_servico_id) ;?>">Sim</a>
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
