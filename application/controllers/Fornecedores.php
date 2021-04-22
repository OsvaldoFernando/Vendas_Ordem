<?php
defined('BASEPATH') or exit('Ação não permitida');

class Fornecedores extends CI_Controller
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
			'titulo' => 'Fornecedores cadastrados',

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
			'fornecedores' => $this->core_model->get_all('fornecedores'),

		);

//		echo '<pre>';
//		print_r($data['fornecedores']);
//		exit();


		//*******Carregar a minha view
		//*** Carregar a Views
		$this->load->view('layout/header', $data);
		$this->load->view('fornecedores/index');
		$this->load->view('layout/footer');
	}

	public function edit($fornecedor_id = NULL)
	{
//		Se ele foi passado
		if (!$fornecedor_id || !$this->core_model->get_by_id('fornecedores', array('fornecedor_id' => $fornecedor_id))) {
			$this->session->set_flashdata('error', 'Fornecedor não encontrado');
			redirect('fornecedores');

		} else {

			$this->form_validation->set_rules('fornecedor_razao', '', 'trim|required|min_length[4]|max_length[200]|callback_check_razao_social');
			$this->form_validation->set_rules('fornecedor_nome_fantasia', '', 'trim|required|min_length[4]|max_length[145]|callback_check_nome_fantasia');
			$this->form_validation->set_rules('fornecedor_telefone', '', 'trim|min_length[4]|max_length[20]|callback_check_telefone');
			$this->form_validation->set_rules('fornecedor_email', '', 'trim|valid_email|max_length[100]|callback_check_email');
			$this->form_validation->set_rules('fornecedor_endereco', '', 'trim|min_length[4]|max_length[145]');
			$this->form_validation->set_rules('fornecedor_bairro', '', 'trim|min_length[4]|max_length[45]');
			$this->form_validation->set_rules('fornecedor_complemento', '', 'trim|min_length[4]|max_length[45]');
			$this->form_validation->set_rules('fornecedor_cidade', '', 'trim|min_length[4]|max_length[45]');
			$this->form_validation->set_rules('fornecedor_ativo', '', 'trim|required');
			$this->form_validation->set_rules('fornecedor_obs', '', 'trim|max_length[500]');


			if ($this->form_validation->run()) {

				$fornecedor_ativo = $this->input->post('fornecedor_ativo');  //Recuperar o valor que está vindo do POST se for zero (desativado) ou 1 (ativo)

				if ($this->db->table_exists('produtos')) { //Verificando se a tabela produto existe, esta verificação porque o controlado produto foi criado antes de criamos a tabela produto

					if ($fornecedor_ativo == 0 && $this->core_model->get_by_id('produtos', array('produto_fornecedor_id' => $fornecedor_id, 'produto_ativo' => 1))) {

						$this->session->set_flashdata('info', 'Esta fornecedor não pode ser desativada, pois está sendo utilizada em <i class="fab fa-product-hunt"></i>&nbsp; PRODUTOS');
						redirect('fornecedores');
					}
				}


//				Método que salva:
				$data = elements(
					array(
						'fornecedor_razao',
						'fornecedor_nome_fantasia',
						'fornecedor_telefone',
						'fornecedor_email',
						'fornecedor_endereco',
						'fornecedor_bairro',
						'fornecedor_complemento',
						'fornecedor_cidade',
						'fornecedor_ativo',
						'fornecedor_obs',
					), $this->input->post()
				);

				//***** Para salvar no banco de dado maiúsculo
				$data['fornecedor_ativo'] = strtoupper($this->input->post('fornecedor_ativo'));

				$data = html_escape($data);

				//************* Salvando no banco de dado
				$this->core_model->update('fornecedores', $data, array('fornecedor_id' => $fornecedor_id));

				redirect('fornecedores');

			} else {

				//Erro de validação

				$data = array(
					'titulo' => 'Atualizar fornecedor',
					'styles' => array(
						'vendor/datatables/dataTables.bootstrap4.min.css',
					),

					'scripts' => array(
						'vendor/datatables/jquery.dataTables.min.js',
						'vendor/datatables/dataTables.bootstrap4.min.js',
						'vendor/datatables/app.js'
					),
					'fornecedor' => $this->core_model->get_by_id('fornecedores', array('fornecedor_id' => $fornecedor_id)),
				);

//			echo '<pre>';
//			print_r($data['fornecedor']);
//			exit();

				//*** Carregar a Views
				$this->load->view('layout/header', $data);
				$this->load->view('fornecedores/edit');
				$this->load->view('layout/footer');
			}
		}
	}

	public function add()
	{
//
		$this->form_validation->set_rules('fornecedor_razao', 'Razão social', 'trim|required|min_length[4]|max_length[200]|is_unique[fornecedores.fornecedor_razao]');
		$this->form_validation->set_rules('fornecedor_nome_fantasia', 'Nome fantasia', 'trim|required|min_length[4]|max_length[145]|is_unique[fornecedores.fornecedor_nome_fantasia]');
		$this->form_validation->set_rules('fornecedor_telefone', 'Telefone', 'trim|min_length[4]|max_length[20]|is_unique[fornecedores.fornecedor_telefone]');
		$this->form_validation->set_rules('fornecedor_email', 'E-mail', 'trim|valid_email|max_length[100]|is_unique[fornecedores.fornecedor_email]');
		$this->form_validation->set_rules('fornecedor_endereco', 'Endereço', 'trim|min_length[4]|max_length[145]');
		$this->form_validation->set_rules('fornecedor_bairro', 'Bairro', 'trim|min_length[4]|max_length[45]');
		$this->form_validation->set_rules('fornecedor_complemento', 'fornecedor', 'trim|min_length[4]|max_length[45]');
		$this->form_validation->set_rules('fornecedor_cidade', 'Cidade', 'trim|min_length[4]|max_length[45]');
		$this->form_validation->set_rules('fornecedor_ativo', 'Fornecedor ativo', 'trim|required');
		$this->form_validation->set_rules('fornecedor_obs', '', 'trim|max_length[500]');


		if ($this->form_validation->run()) {

//				Método que salva:
			$data = elements(
				array(
					'fornecedor_razao',
					'fornecedor_nome_fantasia',
					'fornecedor_telefone',
					'fornecedor_email',
					'fornecedor_endereco',
					'fornecedor_bairro',
					'fornecedor_complemento',
					'fornecedor_cidade',
					'fornecedor_ativo',
					'fornecedor_obs',
				), $this->input->post()
			);

			//***** Para salvar no banco de dado maiúsculo
			$data['fornecedor_ativo'] = strtoupper($this->input->post('fornecedor_ativo'));

			$data = html_escape($data);

			//************* Salvando no banco de dado
			$this->core_model->insert('fornecedores', $data);

			redirect('fornecedores');

		} else {

			//Erro de validação

			$data = array(
				'titulo' => 'Registrar fornecedor',
				'styles' => array(
					'vendor/datatables/dataTables.bootstrap4.min.css',
				),

				'scripts' => array(
					'vendor/datatables/jquery.dataTables.min.js',
					'vendor/datatables/dataTables.bootstrap4.min.js',
					'vendor/datatables/app.js'
				),
			);

			//*** Carregar a Views
			$this->load->view('layout/header', $data);
			$this->load->view('fornecedores/add');
			$this->load->view('layout/footer');
		}
	}

	public function del($fornecedor_id = NULL)
	{
		if (!$fornecedor_id || !$this->core_model->get_by_id('fornecedores', array('fornecedor_id' => $fornecedor_id))) {
			$this->session->set_flashdata('error', 'Fornecedor não encontrado');
			redirect('fornecedores');
		} else {
			$this->core_model->delete('fornecedores', array('fornecedor_id' => $fornecedor_id));
			redirect('fornecedores');
		}
	}

	/********** Metódo cheque fornecedor**/
	public function check_razao_social($fornecedor_razao)
	{
		/** Recuperar o id do fornecedor */
		$fornecedor_id = $this->input->post('fornecedor_id');

		if ($this->core_model->get_by_id('fornecedores', array('fornecedor_razao' => $fornecedor_razao, 'fornecedor_id !=' => $fornecedor_id))) {
			$this->form_validation->set_message('check_razao_social', 'Esta razão social já existe');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/********** Metódo cheque fornecedor**/
  	public function check_nome_fantasia($fornecedor_nome_fantasia)
	{
		/** Recuperar o id do fornecedor */
		$fornecedor_id = $this->input->post('fornecedor_id');

		if ($this->core_model->get_by_id('fornecedores', array('fornecedor_razao' => $fornecedor_nome_fantasia, 'fornecedor_id !=' => $fornecedor_id))) {
			$this->form_validation->set_message('check_nome_fantasia', 'Nome fantasia já existe');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/********** Metódo cheque email**/
	public function check_email($fornecedor_email)
	{
		/** Recuperar o id do fornecedor */
		$fornecedor_id = $this->input->post('fornecedor_id');

		if ($this->core_model->get_by_id('fornecedores', array('fornecedor_email' => $fornecedor_email, 'fornecedor_id !=' => $fornecedor_id))) {
			$this->form_validation->set_message('check_email', 'Esse e-mail ja existe');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/********** Metódo fornecedor telefone**/
	public function check_telefone($fornecedor_telefone)
	{
		/** Recuperar o id do fornecedor */
		$fornecedor_id = $this->input->post('fornecedor_id');

		if ($this->core_model->get_by_id('fornecedores', array('fornecedor_telefone' => $fornecedor_telefone, 'fornecedor_id !=' => $fornecedor_id))) {
			$this->form_validation->set_message('check_telefone', 'Esse telefone ja existe');
			return FALSE;
		} else {
			return TRUE;
		}

	}

}
