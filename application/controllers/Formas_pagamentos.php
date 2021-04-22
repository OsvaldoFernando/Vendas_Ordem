<?php
defined('BASEPATH') or exit('Ação não permitida');

class Formas_pagamentos extends CI_Controller
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
	}

	public function index()
	{
		//*** Variável data do tipo array (informação da documentação do plugin --- Usuário --
		$data = array(

			//*** Título da página de usuários
			'titulo' => 'Formas de pagamento cadastradas',

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
			'formas_pagamentos' => $this->core_model->get_all('formas_pagamentos'),

		);

//		echo '<pre>';
//		print_r($data['formas_pagamentos']);
//		exit();

		//*******Carregar a minha view
		//*** Carregar a Views
		$this->load->view('layout/header', $data);
		$this->load->view('formas_pagamentos/index');
		$this->load->view('layout/footer');
	}

	public function edit($forma_pagamento_id = NULL)
	{
//		Se ele foi passado
		if (!$forma_pagamento_id || !$this->core_model->get_by_id('formas_pagamentos', array('forma_pagamento_id' => $forma_pagamento_id))) {
			$this->session->set_flashdata('error', 'Forma de pagamento não encontrado');
			redirect('modulo');

		} else {

			$this->form_validation->set_rules('forma_pagamento_nome', 'Nome da forma de pagamento', 'trim|required|min_length[4]|max_length[45]|callback_check_pagamento_nome');

			if ($this->form_validation->run()) {

//			Criar impedimento de dasativação

				$forma_pagamento_ativa = $this->input->post('forma_pagamento_ativa');

//			Para vendas

				if ($this->db->table_exists('vendas')) {

					if ($forma_pagamento_ativa == 0 && $this->core_model('vendas', array('vendas_forma_pagamento_id' => $forma_pagamento_id, 'venda_status' => 0))) {

						$this->session->set_flashdata('info', 'Forma de pagamento não pode ser desativada, pois está sendo utilizada em Vendas');
						redirect('modulo');
					}
				}

//			Para ordem de serviços

				if ($this->db->table_exists('ordem_servicos')) {

					if ($forma_pagamento_ativa == 0 && $this->core_model('ordem_servicos', array('ordem_servico_forma_pagamento_id' => $forma_pagamento_id, 'ordem_servico_status' => 0))) {

						$this->session->set_flashdata('info', 'Forma de pagamento não pode ser desativada, pois está sendo utilizada em Ordem de Serviços');
						redirect('modulo');
					}
				}

//				Método que salva:
				$data = elements(
					array(
						'forma_pagamento_nome',
						'forma_pagamento_ativa',
						'forma_pagamento_aceita_parc',
					), $this->input->post()
				);

				$data = html_escape($data);

				//************* Salvando no banco de dado
				$this->core_model->update('formas_pagamentos', $data, array('forma_pagamento_id' => $forma_pagamento_id));
				redirect('modulo');

			} else {
//				Erro de validação

				$data = array(
					'titulo' => 'Editar forma de pagamento',
					'forma_pagamento' => $this->core_model->get_by_id('formas_pagamentos', array('forma_pagamento_id' => $forma_pagamento_id)),
				);

//			echo '<pre>';
//			print_r($data['Servico']);
//			exit();

				//*** Carregar a Views
				$this->load->view('layout/header', $data);
				$this->load->view('formas_pagamentos/edit');
				$this->load->view('layout/footer');
			}


		}
	}

	public function add()
	{
		$this->form_validation->set_rules('forma_pagamento_nome', 'Nome da forma de pagamento', 'trim|required|min_length[4]|max_length[45]|is_unique[formas_pagamentos.forma_pagamento_nome]');

		if ($this->form_validation->run()) {

//				Método que salva:
			$data = elements(
				array(
					'forma_pagamento_nome',
					'forma_pagamento_ativa',
					'forma_pagamento_aceita_parc',
				), $this->input->post()
			);

			$data = html_escape($data);

			//************* Salvando no banco de dado
			$this->core_model->insert('formas_pagamentos', $data);
			redirect('modulo');

		} else {
//				Erro de validação

			$data = array(
				'titulo' => 'Cadastrar forma de pagamento',
			);

//			echo '<pre>';
//			print_r($data['Servico']);
//			exit();

			//*** Carregar a Views
			$this->load->view('layout/header', $data);
			$this->load->view('formas_pagamentos/add');
			$this->load->view('layout/footer');
		}

	}

	public function del($forma_pagamento_id = NULL)
	{
		//		Se ele foi passado
		if (!$forma_pagamento_id || !$this->core_model->get_by_id('formas_pagamentos', array('forma_pagamento_id' => $forma_pagamento_id))) {
			$this->session->set_flashdata('error', 'Forma de pagamento não encontrado');
			redirect('modulo');
		}

		if ($this->core_model->get_by_id('formas_pagamentos', array('forma_pagamento_id' => $forma_pagamento_id, 'forma_pagamento_ativa' => 1))) {
			$this->session->set_flashdata('info', 'Não é possível excluir uma forma de pagamento que está ativa');
			redirect('modulo');
		}

		$this->core_model->delete('formas_pagamentos', array('forma_pagamento_id' => $forma_pagamento_id));
		redirect('modulo');

	}

	public function check_pagamento_nome($forma_pagamento_nome)
	{
		/** Recuperar o id do Vendedor */
		$forma_pagamento_id = $this->input->post('forma_pagamento_id');

		if ($this->core_model->get_by_id('formas_pagamentos', array('forma_pagamento_nome' => $forma_pagamento_nome, 'forma_pagamento_id !=' => $forma_pagamento_id))) {

			$this->form_validation->set_message('check_pagamento_nome', 'Esta forma de pagamento já existe');

			return FALSE;
		} else {
			return TRUE;
		}

	}
}
