<?php
defined('BASEPATH') or exit('Ação não permitida');

class Servicos extends CI_Controller
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
	}

	public function index()
	{
		//*** Variável data do tipo array (informação da documentação do plugin --- Usuário --
		$data = array(

			//*** Título da página de usuários
			'titulo' => 'Serviços cadastrados',

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
			'servicos' => $this->core_model->get_all('servicos'),

		);

//		echo '<pre>';
//		print_r($data['servicos']);
//		exit();


		//*******Carregar a minha view
		//*** Carregar a Views
		$this->load->view('layout/header', $data);
		$this->load->view('servicos/index');
		$this->load->view('layout/footer');
	}

	public function add()
	{
		$this->form_validation->set_rules('servico_nome', '', 'trim|required|min_length[10]|max_length[145]|is_unique[servicos.servico_nome]');
		$this->form_validation->set_rules('servico_preco', '', 'trim|required');
		$this->form_validation->set_rules('servico_descricao', '', 'trim|required|max_length[700]');


		if ($this->form_validation->run()) {

//				Método que salva:
			$data = elements(
				array(
					'servico_nome',
					'servico_preco',
					'servico_descricao',
					'servico_ativo',
				), $this->input->post()
			);

			$data = html_escape($data);

			//************* Salvando no banco de dado
			$this->core_model->insert('servicos', $data);

			redirect('servicos');

		} else {

			//Erro de validação

			$data = array(
				'titulo' => 'Cadastrar Serviço',
				'styles' => array(
					'vendor/datatables/dataTables.bootstrap4.min.css',
				),

				'scripts' => array(
					'vendor/datatables/jquery.dataTables.min.js',
					'vendor/datatables/dataTables.bootstrap4.min.js',
					'vendor/datatables/app.js',
					'vendor/mask/jquery.mask.min.js',
					'vendor/mask/app.js',
				),
			);


			//*** Carregar a Views
			$this->load->view('layout/header', $data);
			$this->load->view('servicos/add');
			$this->load->view('layout/footer');
		}
	}

	public function edit($servico_id = NULL)
	{
//		Se ele foi passado
		if (!$servico_id || !$this->core_model->get_by_id('servicos', array('servico_id' => $servico_id))) {
			$this->session->set_flashdata('error', 'Serviço não encontrado');
			redirect('servicos');

		} else {


			$this->form_validation->set_rules('servico_nome', '', 'trim|required|min_length[10]|max_length[145]|callback_check_nome_servico');
			$this->form_validation->set_rules('servico_preco', 'Telefone', 'trim|required');
			$this->form_validation->set_rules('servico_descricao', 'E-mail', 'trim|required|max_length[700]');


			if ($this->form_validation->run()) {

//				Método que salva:
				$data = elements(
					array(
						'servico_nome',
						'servico_preco',
						'servico_descricao',
						'servico_ativo',
					), $this->input->post()
				);

				$data = html_escape($data);

				//************* Salvando no banco de dado
				$this->core_model->update('servicos', $data, array('servico_id' => $servico_id));

				redirect('servicos');

			} else {

				//Erro de validação

				$data = array(
					'titulo' => 'Atualizar Serviço',
					'styles' => array(
						'vendor/datatables/dataTables.bootstrap4.min.css',
					),

					'scripts' => array(
						'vendor/datatables/jquery.dataTables.min.js',
						'vendor/datatables/dataTables.bootstrap4.min.js',
						'vendor/datatables/app.js'
					),
					'servico' => $this->core_model->get_by_id('servicos', array('servico_id' => $servico_id)),
				);

//			echo '<pre>';
//			print_r($data['Servico']);
//			exit();

				//*** Carregar a Views
				$this->load->view('layout/header', $data);
				$this->load->view('servicos/edit');
				$this->load->view('layout/footer');
			}
		}
	}

	public function del($servico_id = NULL)
	{
		if (!$servico_id || !$this->core_model->get_by_id('servicos', array('servico_id' => $servico_id))) {
			$this->session->set_flashdata('error', 'Serviço não encontrado');
			redirect('servicos');
		} else {
			$this->core_model->delete('servicos', array('servico_id' => $servico_id));
			redirect('servicos');
		}
	}

	public function check_nome_servico($servico_nome)
	{
		/** Recuperar o id do Vendedor */
		$servico_id = $this->input->post('servico_id');

		if ($this->core_model->get_by_id('servicos', array('servico_nome' => $servico_nome, 'servico_id !=' => $servico_id))) {

			$this->form_validation->set_message('check_nome_servico', 'Este serviço já existe');

			return FALSE;
		} else {
			return TRUE;
		}

	}
}
