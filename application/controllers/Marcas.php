<?php
defined('BASEPATH') or exit('Ação não permitida');

class Marcas extends CI_Controller
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
			'titulo' => 'Marcas cadastrados',

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
			'marcas' => $this->core_model->get_all('marcas'),

		);

//		echo '<pre>';
//		print_r($data['marcas']);
//		exit();


		//*******Carregar a minha view
		//*** Carregar a Views
		$this->load->view('layout/header', $data);
		$this->load->view('marcas/index');
		$this->load->view('layout/footer');
	}

	public function edit($marca_id = NULL)
	{
//		Se ele foi passado
		if (!$marca_id || !$this->core_model->get_by_id('marcas', array('marca_id' => $marca_id))) {
			$this->session->set_flashdata('error', 'Marcas não encontrado');
			redirect('marcas');

		} else {


			$this->form_validation->set_rules('marca_nome', '', 'trim|required|min_length[2]|max_length[45]|callback_check_marca_nome');

			if ($this->form_validation->run()) {

				$marca_ativa = $this->input->post('marca_ativa');  //Recuperar o valor que está vindo do POST se for zero (desativado) ou 1 (ativo)

				if ($this->db->table_exists('marcas')) { //Verificando se a tabela produto existe, esta verificação porque o controlado produto foi criado antes de criamos a tabela produto

					if ($marca_ativa == 0 && $this->core_model->get_by_id('produtos', array('produto_marca_id' => $marca_id, 'produto_ativo' => 1))) {

						$this->session->set_flashdata('info', 'Esta marca não pode ser desativada, pois está sendo utilizada em <i class="fas fa-cubes"></i>&nbsp; PRODUTOS');
						redirect('marcas');
					}
				}

//				Método que salva:
				$data = elements(
					array(
						'marca_nome',
						'marca_ativa',
					), $this->input->post()
				);

				$data = html_escape($data);

				//************* Salvando no banco de dado
				$this->core_model->update('marcas', $data, array('marca_id' => $marca_id));

				redirect('marcas');

			} else {

				//Erro de validação

				$data = array(
					'titulo' => 'Atualizar Marcas',
					'styles' => array(
						'vendor/datatables/dataTables.bootstrap4.min.css',
					),

					'scripts' => array(
						'vendor/datatables/jquery.dataTables.min.js',
						'vendor/datatables/dataTables.bootstrap4.min.js',
						'vendor/datatables/app.js'
					),
					'marca' => $this->core_model->get_by_id('marcas', array('marca_id' => $marca_id)),
				);

//			echo '<pre>';
//			print_r($data['Servico']);
//			exit();

				//*** Carregar a Views
				$this->load->view('layout/header', $data);
				$this->load->view('marcas/edit');
				$this->load->view('layout/footer');
			}
		}
	}

	public function add()
	{
		$this->form_validation->set_rules('marca_nome', '', 'trim|required|min_length[2]|max_length[45]|is_unique[marcas.marca_nome]');

		if ($this->form_validation->run()) {

//				Método que salva:
			$data = elements(
				array(
					'marca_nome',
					'marca_ativa',
				), $this->input->post()
			);

			$data = html_escape($data);

			//************* Salvando no banco de dado
			$this->core_model->insert('marcas', $data);

			redirect('marcas');

		} else {

			//Erro de validação

			$data = array(
				'titulo' => 'Cadastrar Marcas',
				'styles' => array(
					'vendor/datatables/dataTables.bootstrap4.min.css',
				),

				'scripts' => array(
					'vendor/datatables/jquery.dataTables.min.js',
					'vendor/datatables/dataTables.bootstrap4.min.js',
					'vendor/datatables/app.js'
				),
			);

//			echo '<pre>';
//			print_r($data['Servico']);
//			exit();

			//*** Carregar a Views
			$this->load->view('layout/header', $data);
			$this->load->view('marcas/add');
			$this->load->view('layout/footer');
		}
	}

	public function del($marca_id = NULL)
	{
		if (!$marca_id || !$this->core_model->get_by_id('marcas', array('marca_id' => $marca_id))) {
			$this->session->set_flashdata('error', 'Marca não encontrado');
			redirect('marcas');
		} else {
			$this->core_model->delete('marcas', array('marca_id' => $marca_id));
			redirect('marcas');
		}
	}

	public function check_marca_nome($marca_nome)
	{
		/** Recuperar o id do Vendedor */
		$marca_id = $this->input->post('marca_id');

		if ($this->core_model->get_by_id('marcas', array('marca_nome' => $marca_nome, 'marca_id !=' => $marca_id))) {

			$this->form_validation->set_message('check_marca_nome', 'Esta Marca já existe');

			return FALSE;
		} else {
			return TRUE;
		}

	}

}
