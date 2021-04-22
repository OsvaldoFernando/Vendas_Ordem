<?php
defined('BASEPATH') or exit('Ação não permitida');

class Vendas extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		//*******Verificar se o Servico esta logado
		if (!$this->ion_auth->logged_in()) {
			//**** Mensagem de sessão
			$this->session->set_flashdata('info', 'Sua sessão expirou');
			redirect('login');
		}
		$this->load->model('vendas_model');
	}

	public function index()
	{
		//*** Variável data do tipo array (informação da documentação do plugin --- Usuário --
		$data = array(

			//*** Título da página de usuários
			'titulo' => 'Vendas cadastradas',

			//*** Chave para pesquisar dinamicamente os usuários(Bootstrap css)
			'styles' => array(
				'vendor/datatables/dataTables.bootstrap4.min.css',
			),
			'scripts' => array(
				'vendor/datatables/jquery.dataTables.min.js',
				'vendor/datatables/dataTables.bootstrap4.min.js',
				'vendor/datatables/app.js'
			),
			//*** Fim, Chave para pesquisar dinamicamente os usuários

			//****** Trazer toda informação da tabela
			'vendas' => $this->vendas_model->get_all(),

		);

//		echo '<pre>';
//		print_r($data['vendas']);
//		exit();


		//*******Carregar a minha view
		//*** Carregar a Views
		$this->load->view('layout/header', $data);
		$this->load->view('vendas/index');
		$this->load->view('layout/footer');
	}

	public function edit($venda_id = NULL)
	{
//		Se ele foi passado
		if (!$venda_id || !$this->core_model->get_by_id('vendas', array('venda_id' => $venda_id))) {
			$this->session->set_flashdata('error', 'Vendas não encontrada');
			redirect('vendas');

		} else {

			$venda_produtos = $this->vendas_model->get_all_produtos_by_venda($venda_id); //será utilizado na quantidade de produtos

			$this->form_validation->set_rules('venda_cliente_id', '', 'required');
			$this->form_validation->set_rules('venda_tipo', '', 'required');
			$this->form_validation->set_rules('venda_forma_pagamento_id', '', 'required');

			if ($this->form_validation->run()) {

				$venda_valor_total = str_replace('AKZ', "", trim($this->input->post('venda_valor_total')));

				$data = elements(
					array(
						'venda_cliente_id',
						'venda_forma_pagamento_id',
						'venda_tipo',
						'venda_vendedor_id',
						'venda_valor_desconto',
						'venda_valor_total',
					), $this->input->post()

				);

				$data['venda_valor_total'] = trim(preg_replace('/\$/', '', $venda_valor_total));

				$data = html_escape($data);

				//************* Salvando no banco de dado
				$this->core_model->update('vendas', $data, array('venda_id' => $venda_id));

				/*Deleteando da venda  os produtos antigos da venda editada*/
				$this->vendas_model->delete_old_products($venda_id);

				$produto_id = $this->input->post('produto_id');
				$produto_quantidade = $this->input->post('produto_quantidade');
				$produto_desconto = str_replace('%', '', $this->input->post('produto_desconto'));

				$produto_preco_venda = str_replace('AKZ', '', $this->input->post('produto_preco'));
				$produto_item_total = str_replace('AKZ', '', $this->input->post('produto_item_total'));

				$produto_preco = str_replace(',', '', $produto_preco_venda);
				$produto_item_total = str_replace(',', '', $produto_item_total);


				$qty_produto = count($produto_id);

//				$venda_id = $this->input->post('venda_id');

				for ($i = 0; $i < $qty_produto; $i++) {

					$data = array(
						'venda_produto_id_venda' => $venda_id,
						'venda_produto_id_produto' => $produto_id[$i],
						'venda_produto_quantidade' => $produto_quantidade[$i],
						'venda_produto_valor_unitario' => $produto_preco_venda[$i],
						'venda_produto_desconto' => $produto_desconto[$i],
						'venda_produto_valor_total' => $produto_item_total[$i],
					);

					$data = html_escape($data);

					//Para cada dado do for vou inserir os meus dados
					$this->core_model->insert('venda_produtos', $data);

				}

				//Criar recurso PDF

//				redirect('vendas/imprimir/' . $venda_id); comentamos porque ainda não existe o venda.
				redirect('vendas');


//				echo '<pre>';
//				print_r($this->input->post());
//				exit();

			} else {

				$data = array(
					'titulo' => 'Atualizar venda',
					'styles' => array(
						'vendor/select2/select2.min.css',
						'vendor/autocomplete/jquery-ui.css',
						'vendor/autocomplete/estilo.css',
					),

					'scripts' => array(
						'vendor/autocomplete/jquery-migrate.js', //Vem primeiro
						'vendor/calcx/jquery-calx-sample-2.2.8.js',
						'vendor/calcx/venda.js',
						'vendor/select2/select2.min.js',
						'vendor/select2/app.js',
						'vendor/sweetalert2/sweetalert2.js',
						'vendor/autocomplete/jquery-ui.js', //Vem último

					),
					'clientes' => $this->core_model->get_all('clientes', array('cliente_ativo' => 1)),

					'formas_pagamentos' => $this->core_model->get_all('formas_pagamentos', array('forma_pagamento_ativa' => 1)),

					'vendedores' => $this->core_model->get_all('vendedores', array('vendedor_ativo' => 1)),

				);

				$venda = $data['venda'] = $this->vendas_model->get_by_id($venda_id);


//				echo '<pre>';
//				print_r($data['os_tem_servicos']);
//				exit();

//				echo '<pre>';
//				print_r($ordem_servico);
//				exit();


				//*** Carregar a Views
				$this->load->view('layout/header', $data);
				$this->load->view('vendas/edit');
				$this->load->view('layout/footer');
			}
		}
	}
}
