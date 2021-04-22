<?php
defined('BASEPATH') or exit('Ação não permitida');

class Vendedores extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		//*******Verificar se o Vendedor esta logado
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
			'titulo' => 'Vendedores cadastrados',

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
			'vendedores' => $this->core_model->get_all('vendedores'),

		);

//		echo '<pre>';
//		print_r($data['vendedores']);
//		exit();


		//*******Carregar a minha view
		//*** Carregar a Views
		$this->load->view('layout/header', $data);
		$this->load->view('vendedores/index');
		$this->load->view('layout/footer');
	}

	public function add()
	{
		/*
 * Array
(
[0] => stdClass Object
(
[vendedor_id] => 1
[vendedor_codigo] => 09842571
[vendedor_data_cadastro] => 2020-01-28 02:24:17
[vendedor_nome_completo] => Lucio Antonio de Souza
[vendedor_telefone] =>
[vendedor_email] => vendedor@gmail.com
[vendedor_endereco] => Rua das vendas
[vendedor_numero_endereco] => 45
[vendedor_complemento] =>
[vendedor_bairro] => Centro
[vendedor_cidade] => Curitiba
[vendedor_ativo] => 1
[vendedor_obs] =>
[vendedor_data_alteracao] => 2020-01-28 02:24:17
)

 */

		$this->form_validation->set_rules('vendedor_nome_completo', '', 'trim|required|min_length[4]|max_length[200]');
		$this->form_validation->set_rules('vendedor_telefone', 'Telefone', 'trim|min_length[4]|max_length[20]|is_unique[vendedores.vendedor_telefone]');
		$this->form_validation->set_rules('vendedor_email', 'E-mail', 'trim|valid_email|max_length[100]|is_unique[vendedores.vendedor_email]');
		$this->form_validation->set_rules('vendedor_endereco', 'Endereço', 'trim|min_length[4]|max_length[145]');
		$this->form_validation->set_rules('vendedor_bairro', 'Bairro', 'trim|min_length[4]|max_length[45]');
		$this->form_validation->set_rules('vendedor_complemento', 'Vendedor', 'trim|min_length[4]|max_length[45]');
		$this->form_validation->set_rules('vendedor_cidade', 'Cidade', 'trim|min_length[4]|max_length[45]');
		$this->form_validation->set_rules('vendedor_ativo', 'Vendedor ativo', 'trim|required');
		$this->form_validation->set_rules('vendedor_obs', '', 'trim|max_length[500]');


		if ($this->form_validation->run()) {

//				Método que salva:
			$data = elements(
				array(
					'vendedor_codigo',
					'vendedor_nome_completo',
					'vendedor_telefone',
					'vendedor_email',
					'vendedor_endereco',
					'vendedor_bairro',
					'vendedor_complemento',
					'vendedor_cidade',
					'vendedor_ativo',
					'vendedor_obs',
				), $this->input->post()
			);

			//***** Para salvar no banco de dado maiúsculo
			$data['vendedor_ativo'] = strtoupper($this->input->post('vendedor_ativo'));

			$data = html_escape($data);

			//************* Salvando no banco de dado
			$this->core_model->insert('vendedores', $data);

			redirect('vendedores');

		} else {

			//Erro de validação

			$data = array(
				'titulo' => 'Cadastrar Vendedor',
				'styles' => array(
					'vendor/datatables/dataTables.bootstrap4.min.css',
				),

				'scripts' => array(
					'vendor/datatables/jquery.dataTables.min.js',
					'vendor/datatables/dataTables.bootstrap4.min.js',
					'vendor/datatables/app.js'
				),
				'vendedor_codigo' => $this->core_model->generate_unique_code('vendedores','numeric',8,'vendedor_codigo'),
			);

//			echo '<pre>';
//			print_r($data['Vendedor']);
//			exit();

			//*** Carregar a Views
			$this->load->view('layout/header', $data);
			$this->load->view('vendedores/add');
			$this->load->view('layout/footer');
		}
	}

	public function edit($vendedor_id = NULL)
	{
//		Se ele foi passado
		if (!$vendedor_id || !$this->core_model->get_by_id('vendedores', array('vendedor_id' => $vendedor_id))) {
			$this->session->set_flashdata('error', 'Vendedor não encontrado');
			redirect('vendedores');

		} else {
			/*
			 * Array
(
    [0] => stdClass Object
        (
            [vendedor_id] => 1
            [vendedor_codigo] => 09842571
            [vendedor_data_cadastro] => 2020-01-28 02:24:17
            [vendedor_nome_completo] => Lucio Antonio de Souza
            [vendedor_telefone] =>
            [vendedor_email] => vendedor@gmail.com
            [vendedor_endereco] => Rua das vendas
            [vendedor_numero_endereco] => 45
            [vendedor_complemento] =>
            [vendedor_bairro] => Centro
            [vendedor_cidade] => Curitiba
            [vendedor_ativo] => 1
            [vendedor_obs] =>
            [vendedor_data_alteracao] => 2020-01-28 02:24:17
        )

			 */

			$this->form_validation->set_rules('vendedor_nome_completo', '', 'trim|required|min_length[4]|max_length[200]');
			$this->form_validation->set_rules('vendedor_telefone', 'Telefone', 'trim|min_length[4]|max_length[20]|callback_check_telefone');
			$this->form_validation->set_rules('vendedor_email', 'E-mail', 'trim|valid_email|max_length[100]|callback_check_email');
			$this->form_validation->set_rules('vendedor_endereco', 'Endereço', 'trim|min_length[4]|max_length[145]');
			$this->form_validation->set_rules('vendedor_bairro', 'Bairro', 'trim|min_length[4]|max_length[45]');
			$this->form_validation->set_rules('vendedor_complemento', 'Vendedor', 'trim|min_length[4]|max_length[45]');
			$this->form_validation->set_rules('vendedor_cidade', 'Cidade', 'trim|min_length[4]|max_length[45]');
			$this->form_validation->set_rules('vendedor_ativo', 'Vendedor ativo', 'trim|required');
			$this->form_validation->set_rules('vendedor_obs', '', 'trim|max_length[500]');


			if ($this->form_validation->run()) {

//				Método que salva:
				$data = elements(
					array(
						'vendedor_codigo',
						'vendedor_nome_completo',
						'vendedor_telefone',
						'vendedor_email',
						'vendedor_endereco',
						'vendedor_bairro',
						'vendedor_complemento',
						'vendedor_cidade',
						'vendedor_ativo',
						'vendedor_obs',
					), $this->input->post()
				);

				//***** Para salvar no banco de dado maiúsculo
				$data['vendedor_ativo'] = strtoupper($this->input->post('vendedor_ativo'));

				$data = html_escape($data);

				//************* Salvando no banco de dado
				$this->core_model->update('vendedores', $data, array('vendedor_id' => $vendedor_id));

				redirect('vendedores');

			} else {

				//Erro de validação

				$data = array(
					'titulo' => 'Atualizar Vendedor',
					'styles' => array(
						'vendor/datatables/dataTables.bootstrap4.min.css',
					),

					'scripts' => array(
						'vendor/datatables/jquery.dataTables.min.js',
						'vendor/datatables/dataTables.bootstrap4.min.js',
						'vendor/datatables/app.js'
					),
					'vendedor' => $this->core_model->get_by_id('vendedores', array('vendedor_id' => $vendedor_id)),
				);

//			echo '<pre>';
//			print_r($data['Vendedor']);
//			exit();

				//*** Carregar a Views
				$this->load->view('layout/header', $data);
				$this->load->view('vendedores/edit');
				$this->load->view('layout/footer');
			}
		}
	}

	public function del($vendedor_id = NULL)
	{
		if (!$vendedor_id || !$this->core_model->get_by_id('vendedores', array('Vendedor_id' => $vendedor_id))) {
			$this->session->set_flashdata('error', 'Vendedor não encontrado');
			redirect('vendedores');
		} else {
			$this->core_model->delete('vendedores', array('Vendedor_id' => $vendedor_id));
			redirect('vendedores');
		}
	}

	/********** Metódo cheque email**/
	function check_email($vendedor_email)
	{
		/** Recuperar o id do Vendedor */
		$vendedor_id = $this->input->post('vendedor_id');

		if ($this->core_model->get_by_id('vendedores', array('vendedor_email' => $vendedor_email, 'vendedor_id !=' => $vendedor_id))) {
			$this->form_validation->set_message('check_email', 'Esse e-mail ja existe');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/********** Metódo Vendedor telefone**/
	function check_telefone($vendedor_telefone)
	{
		/** Recuperar o id do Vendedor */
		$vendedor_id = $this->input->post('vendedor_id');

		if ($this->core_model->get_by_id('vendedores', array('vendedor_telefone' => $vendedor_telefone, 'vendedor_id !=' => $vendedor_id))) {
			$this->form_validation->set_message('check_telefone', 'Esse telefone ja existe');
			return FALSE;
		} else {
			return TRUE;
		}

	}
}

