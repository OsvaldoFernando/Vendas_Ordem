<?php
defined('BASEPATH') or exit('Ação não permitida');

class Receber extends CI_Controller
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
			'titulo' => 'Contas a receber cadastrados',


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
			'contas_receber' => $this->financeiro_model->get_all_receber(),

		);

//		echo '<pre>';
//		print_r($data['contas_receber']);
//		exit();


		//*******Carregar a minha view
		//*** Carregar a Views
		$this->load->view('layout/header', $data);
		$this->load->view('receber/index');
		$this->load->view('layout/footer');
	}

	public function edit($conta_receber_id = NULL)
	{
		if (!$conta_receber_id || !$this->core_model->get_by_id('contas_receber', array('conta_receber_id' => $conta_receber_id))) {
			$this->session->set_flashdata('error', 'Conta não encontrada');
			redirect('receber');
		} else {

			$this->form_validation->set_rules('conta_receber_cliente_id', '', 'trim|required');
			$this->form_validation->set_rules('conta_receber_data_vencimento', '', 'trim|required');
			$this->form_validation->set_rules('conta_receber_valor', '', 'trim|required');
			$this->form_validation->set_rules('conta_receber_obs', '', 'max_length[100]');

			if ($this->form_validation->run()) {

				//Cerificar se foi paga
				$data = elements(
					array(
						'conta_receber_cliente_id',
						'conta_receber_data_vencimento',
						'conta_receber_valor',
						'conta_receber_status',
						'conta_receber_obs',
					), $this->input->post()
				);

				$conta_receber_status = $this->input->post('conta_receber_status');

				if ($conta_receber_status == 1) {

					$data['conta_receber_data_pagamento'] = date('Y-m-d h:i:s');
				}

				$data = html_escape($data);

				//************* Salvando no banco de dado
				$this->core_model->update('contas_receber', $data, array('conta_receber_id' => $conta_receber_id));

				redirect('receber');


			} else {
				$data = array(

					//*** Título da página de usuários
					'titulo' => 'Editar contas',

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
					'conta_receber' => $this->core_model->get_by_id('contas_receber', array('conta_receber_id' => $conta_receber_id)),
					'clientes' => $this->core_model->get_all('clientes', array('cliente_ativo' => $cliente_ativo = 1)),

				);

//		echo '<pre>';
//		print_r($data['contas_receber']);
//		exit();

				//*** Carregar a Views
				$this->load->view('layout/header', $data);
				$this->load->view('receber/edit');
				$this->load->view('layout/footer');
			}
		}
	}

	public function add()
	{
		$this->form_validation->set_rules('conta_receber_cliente_id', '', 'trim|required');
		$this->form_validation->set_rules('conta_receber_data_vencimento', '', 'trim|required');
		$this->form_validation->set_rules('conta_receber_valor', '', 'trim|required');
		$this->form_validation->set_rules('conta_receber_obs', '', 'max_length[100]');

		if ($this->form_validation->run()) {

			//Cerificar se foi paga
			$data = elements(
				array(
					'conta_receber_cliente_id',
					'conta_receber_data_vencimento',
					'conta_receber_valor',
					'conta_receber_status',
					'conta_receber_obs',
				), $this->input->post()
			);

			$conta_receber_status = $this->input->post('conta_receber_status');

			if ($conta_receber_status == 1) {

				$data['conta_receber_data_pagamento'] = date('Y-m-d h:i:s');
			}

			$data = html_escape($data);

			//************* Salvando no banco de dado
			$this->core_model->insert('contas_receber', $data);

			redirect('receber');


		} else {
			$data = array(

				//*** Título da página de usuários
				'titulo' => 'Cadastar conta',

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
				'clientes' => $this->core_model->get_all('clientes', array('cliente_ativo' => $cliente_ativo = 1)),
			);

//		echo '<pre>';
//		print_r($data['contas_receber']);
//		exit();

			//*** Carregar a Views
			$this->load->view('layout/header', $data);
			$this->load->view('receber/add');
			$this->load->view('layout/footer');
		}
	}

	public function del($conta_receber_id = NULL)
	{
		if (!$conta_receber_id || !$this->core_model->get_by_id('contas_receber', array('conta_receber_id' => $conta_receber_id))) {
			$this->session->set_flashdata('error', 'Conta não encontrado');
			redirect('receber');
		}

		if ($this->core_model->get_by_id('contas_receber', array('conta_receber_id' => $conta_receber_id, 'conta_receber_status' => 0))) {
			$this->session->set_flashdata('info', 'Esta conta não pode ser paga pois ainda está em aberto');
			redirect('receber');
		}

		$this->core_model->delete('contas_receber', array('conta_receber_id' => $conta_receber_id));
		redirect('receber');
	}
}
