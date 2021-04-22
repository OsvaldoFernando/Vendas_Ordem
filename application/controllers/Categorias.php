<?php
defined('BASEPATH') or exit('Ação não permitida');

class categorias extends CI_Controller
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
			'titulo' => 'Categorias cadastrados',

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
			'categorias' => $this->core_model->get_all('categorias'),

		);

//		echo '<pre>';
//		print_r($data['categorias']);
//		exit();


		//*******Carregar a minha view
		//*** Carregar a Views
		$this->load->view('layout/header', $data);
		$this->load->view('categorias/index');
		$this->load->view('layout/footer');
	}

	public function add()
	{
		$this->form_validation->set_rules('categoria_nome', '', 'trim|required|min_length[2]|max_length[45]|is_unique[categorias.categoria_nome]');

		if ($this->form_validation->run()) {

//				Método que salva:
			$data = elements(
				array(
					'categoria_nome',
					'categoria_ativa',
				), $this->input->post()
			);

			$data = html_escape($data);

			//************* Salvando no banco de dado
			$this->core_model->insert('categorias', $data);

			redirect('categorias');

		} else {

			//Erro de validação

			$data = array(
				'titulo' => 'Cadastrar categoria',
				'scripts' => array(
					'vendor/datatables/jquery.dataTables.min.js',
					'vendor/datatables/dataTables.bootstrap4.min.js',
					'vendor/datatables/app.js'
				),
			);

			//*** Carregar a Views
			$this->load->view('layout/header', $data);
			$this->load->view('categorias/add');
			$this->load->view('layout/footer');
		}
	}

	public function edit($categoria_id = NULL)
	{
//		Se ele foi passado
		if (!$categoria_id || !$this->core_model->get_by_id('categorias', array('categoria_id' => $categoria_id))) {
			$this->session->set_flashdata('error', 'Categorias não encontrado');
			redirect('categorias');

		} else {


			$this->form_validation->set_rules('categoria_nome', '', 'trim|required|min_length[2]|max_length[45]|callback_check_categoria_nome');

			if ($this->form_validation->run()) {

				$categoria_ativa = $this->input->post('categoria_ativa');  //Recuperar o valor que está vindo do POST se for zero (desativado) ou 1 (ativo)

				if ($this->db->table_exists('produtos')) { //Verificando se a tabela produto existe, esta verificação porque o controlado produto foi criado antes de criamos a tabela produto

					if ($categoria_ativa == 0 && $this->core_model->get_by_id('produtos', array('produto_categoria_id' => $categoria_id, 'produto_ativo' => 1))) {

						$this->session->set_flashdata('info', 'Esta categoria não pode ser desativada, pois está sendo utilizada em <i class="fab fa-buffer"></i>&nbsp; PRODUTOS');
						redirect('categorias');
					}
				}

//				Método que salva:
				$data = elements(
					array(
						'categoria_nome',
						'categoria_ativa',
					), $this->input->post()
				);

				$data = html_escape($data);

				//************* Salvando no banco de dado
				$this->core_model->update('categorias', $data, array('categoria_id' => $categoria_id));

				redirect('categorias');

			} else {

				//Erro de validação

				$data = array(
					'titulo' => 'Atualizar categorias',
					'styles' => array(
						'vendor/datatables/dataTables.bootstrap4.min.css',
					),

					'scripts' => array(
						'vendor/datatables/jquery.dataTables.min.js',
						'vendor/datatables/dataTables.bootstrap4.min.js',
						'vendor/datatables/app.js'
					),
					'categoria' => $this->core_model->get_by_id('categorias', array('categoria_id' => $categoria_id)),
				);

//			echo '<pre>';
//			print_r($data['Servico']);
//			exit();

				//*** Carregar a Views
				$this->load->view('layout/header', $data);
				$this->load->view('categorias/edit');
				$this->load->view('layout/footer');
			}
		}
	}


	public function del($categoria_id = NULL)
	{
		if (!$categoria_id || !$this->core_model->get_by_id('categorias', array('categoria_id' => $categoria_id))) {
			$this->session->set_flashdata('error', 'Categoria não encontrado');
			redirect('categorias');
		} else {
			$this->core_model->delete('categorias', array('categoria_id' => $categoria_id));
			redirect('categorias');
		}
	}

	public function check_categoria_nome($categoria_nome)
	{
		/** Recuperar o id do Vendedor */
		$categoria_id = $this->input->post('categoria_id');

		if ($this->core_model->get_by_id('categorias', array('categoria_nome' => $categoria_nome, 'categoria_id !=' => $categoria_id))) {

			$this->form_validation->set_message('check_categoria_nome', 'Esta Categoria já existe');

			return FALSE;
		} else {
			return TRUE;
		}

	}

}
