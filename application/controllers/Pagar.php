<?php
defined('BASEPATH') or exit('Ação não permitida');

class Pagar extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		//*******Verificar se o fornecedor esta logado
		if (!$this->ion_auth->logged_in()) {
			//**** Mensagem de sessão
			$this->session->set_flashdata('info', 'Sua sessão expirou');
			redirect('login');
		}
		$this->load->model('financeiro_model');
	}

	public function index()
	{
		//*** Variável data do tipo array (informação da documentação do plugin --- Usuário --
		$data = array(

			//*** Título da página de usuários
			'titulo' => 'Contas a pagar cadastrados',

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
			'contas_pagar' => $this->financeiro_model->get_all_pagar(),

		);

//		echo '<pre>';
//		print_r($data['contas_pagar']);
//		exit();


		//*******Carregar a minha view
		//*** Carregar a Views
		$this->load->view('layout/header', $data);
		$this->load->view('pagar/index');
		$this->load->view('layout/footer');
	}

	public function edit($conta_pagar_id = NULL)
	{
		if (!$conta_pagar_id || !$this->core_model->get_by_id('contas_pagar', array('conta_pagar_id' => $conta_pagar_id))) {
			$this->session->set_flashdata('error', 'Conta não encontrada');
			redirect('pagar');
		} else {

			$this->form_validation->set_rules('conta_pagar_fornecedor_id', '', 'trim|required');
			$this->form_validation->set_rules('conta_pagar_data_vencimento', '', 'trim|required');
			$this->form_validation->set_rules('conta_pagar_valor', '', 'trim|required');
			$this->form_validation->set_rules('conta_pagar_obs', '', 'max_length[100]');

			if ($this->form_validation->run()) {

				//Cerificar se foi paga
				$data = elements(
					array(
						'conta_pagar_fornecedor_id',
						'conta_pagar_data_vencimento',
						'conta_pagar_valor',
						'conta_pagar_status',
						'conta_pagar_obs',
					), $this->input->post()
				);

				$conta_pagar_status = $this->input->post('conta_pagar_status');

				if ($conta_pagar_status == 1) {

					$data['conta_pagar_data_pagamento'] = date('Y-m-d h:i:s');
				}

				$data = html_escape($data);

				//************* Salvando no banco de dado
				$this->core_model->update('contas_pagar', $data, array('conta_pagar_id' => $conta_pagar_id));

				redirect('pagar');


			} else {
				$data = array(

					//*** Título da página de usuários
					'titulo' => 'Contas a pagar cadastrados',

					'styles' => array(
						'vendor/select2/select2.min.css',
					),
					'scripts' => array(
						'vendor/select2/select2.min.js',
						'vendor/select2/app.js',
						'vendor/mask/jquery.mask.min.js',
						'vendor/mask/app.js',
					),

					//****** Trazer toda informação da tabela
					'conta_pagar' => $this->core_model->get_by_id('contas_pagar', array('conta_pagar_id' => $conta_pagar_id)),
					'fornecedores' => $this->core_model->get_all('fornecedores'),

				);

//		echo '<pre>';
//		print_r($data['contas_pagar']);
//		exit();

				//*** Carregar a Views
				$this->load->view('layout/header', $data);
				$this->load->view('pagar/edit');
				$this->load->view('layout/footer');
			}
		}
	}

	public function add()
	{
		$this->form_validation->set_rules('conta_pagar_fornecedor_id', '', 'trim|required');
		$this->form_validation->set_rules('conta_pagar_data_vencimento', '', 'trim|required');
		$this->form_validation->set_rules('conta_pagar_valor', '', 'trim|required');
		$this->form_validation->set_rules('conta_pagar_obs', '', 'max_length[100]');

		if ($this->form_validation->run()) {

			//Cerificar se foi paga
			$data = elements(
				array(
					'conta_pagar_fornecedor_id',
					'conta_pagar_data_vencimento',
					'conta_pagar_valor',
					'conta_pagar_status',
					'conta_pagar_obs',
				), $this->input->post()
			);

			$conta_pagar_status = $this->input->post('conta_pagar_status');

			if ($conta_pagar_status == 1) {

				$data['conta_pagar_data_pagamento'] = date('Y-m-d h:i:s');
			}

			$data = html_escape($data);

			//************* Salvando no banco de dado
			$this->core_model->insert('contas_pagar', $data);

			redirect('pagar');


		} else {
			$data = array(

				//*** Título da página de usuários
				'titulo' => 'Contas a pagar cadastrados',

				'styles' => array(
					'vendor/select2/select2.min.css',
				),
				'scripts' => array(
					'vendor/select2/select2.min.js',
					'vendor/select2/app.js',
					'vendor/mask/jquery.mask.min.js',
					'vendor/mask/app.js',
				),

				//****** Trazer toda informação da tabela
				'fornecedores' => $this->core_model->get_all('fornecedores'),

			);

//		echo '<pre>';
//		print_r($data['contas_pagar']);
//		exit();

			//*** Carregar a Views
			$this->load->view('layout/header', $data);
			$this->load->view('pagar/add');
			$this->load->view('layout/footer');
		}
	}

	public function del($conta_pagar_id = NULL)
	{
		if (!$conta_pagar_id || !$this->core_model->get_by_id('contas_pagar', array('conta_pagar_id' => $conta_pagar_id))) {
			$this->session->set_flashdata('error', 'Conta não encontrado');
			redirect('pagar');
		}

		if ($this->core_model->get_by_id('contas_pagar', array('conta_pagar_id' => $conta_pagar_id, 'conta_pagar_status' => 0))) {
			$this->session->set_flashdata('info', 'Esta conta não pode ser paga pois ainda está em aberto');
			redirect('pagar');
		}

		$this->core_model->delete('contas_pagar', array('conta_pagar_id' => $conta_pagar_id));
		redirect('pagar');
	}
}
