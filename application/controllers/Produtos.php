<?php
defined('BASEPATH') or exit('Ação não permitida');

class Produtos extends CI_Controller
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

		$this->load->model('produtos_model');
	}

	public function index()
	{
		//*** Variável data do tipo array (informação da documentação do plugin --- Usuário --
		$data = array(

			//*** Título da página de usuários
			'titulo' => 'Produtos cadastrados',

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
			'produtos' => $this->produtos_model->get_all(),

		);


		//*******Carregar a minha view
		//*** Carregar a Views
		$this->load->view('layout/header', $data);
		$this->load->view('produtos/index');
		$this->load->view('layout/footer');
	}

	public function edit($produto_id = NULL)
	{

		if (!$produto_id || !$this->core_model->get_by_id('produtos', array('produto_id' => $produto_id))) {
			$this->session->set_flashdata('error', 'Produto não encontrado');
			redirect('produtos');
		} else {

			/*
				 *  		[produto_id] => 1
							[produto_codigo] => 72495380
							[produto_data_cadastro] =>
							[produto_categoria_id] => 1
							[produto_marca_id] => 1
							[produto_fornecedor_id] => 1
							[produto_descricao] => Notebook gamer
							[produto_unidade] => UN
							[produto_codigo_barras] => 4545
							[produto_ncm] => 5656
							[produto_preco_custo] => 1.800,00
							[produto_preco_venda] => 15.031,00
							[produto_estoque_minimo] => 2
							[produto_qtde_estoque] => 2
							[produto_ativo] => 1
							[produto_obs] =>
				 */

			$this->form_validation->set_rules('produto_descricao', '', 'trim|required|min_length[5]|max_length[145]|callback_check_produto_descricao');
			$this->form_validation->set_rules('produto_unidade', 'Produto unidade', 'trim|required|min_length[2]|max_length[5]');
			$this->form_validation->set_rules('produto_preco_custo', 'Preço de custo', 'trim|required|max_length[45]');
			$this->form_validation->set_rules('produto_preco_venda', 'Preço de venda', 'trim|required|max_length[145]|callback_check_produto_preco_venda');
			$this->form_validation->set_rules('produto_estoque_minimo', 'Estoque minímo', 'trim|required|greater_than_equal_to[0]');
			$this->form_validation->set_rules('produto_qtde_estoque', 'Quantidade em estoque', 'trim|required');
			$this->form_validation->set_rules('produto_obs', 'Observação', 'trim|max_length[200]');

			if ($this->form_validation->run()) {

//				echo '<pre>';
//				print_r($this->input->post());
//				exit();

				$data = elements(

					array(
						'produto_id',
						'produto_categoria_id',
						'produto_marca_id',
						'produto_fornecedor_id',
						'produto_descricao',
						'produto_unidade',
						'produto_preco_custo',
						'produto_preco_venda',
						'produto_estoque_minimo',
						'produto_qtde_estoque',
						'produto_ativo',
						'produto_obs',
					), $this->input->post()

				);

				$data = html_escape($data);


//				echo '<pre>';
//				print_r($data);
//				exit();

				$this->core_model->update('produtos', $data, array('produto_id' => $produto_id));
				redirect('produtos');

			} else {

				//Erro de validação

				$data = array(
					'titulo' => 'Atualizar produto',
					'styles' => array(
						'vendor/datatables/dataTables.bootstrap4.min.css',
					),

					'scripts' => array(
//					'vendor/datatables/jquery.dataTables.min.js',
//					'vendor/datatables/dataTables.bootstrap4.min.js',
//					'vendor/datatables/app.js',
						'vendor/mask/jquery.mask.min.js',
						'vendor/mask/app.js',
					),
					'produto' => $this->core_model->get_by_id('produtos', array('produto_id' =>$produto_id)),

					'produto_codigo' => $this->core_model->generate_unique_code('produtos', 'numeric', 8, 'produto_codigo'),

					'marcas' => $this->core_model->get_all('marcas', array('marca_ativa' => 1)),

					'categorias' => $this->core_model->get_all('categorias', array('categoria_ativa' => 1)),

					'fornecedores' => $this->core_model->get_all('fornecedores', array('fornecedor_ativo' => 1)),
				);


				//*** Carregar a Views
				$this->load->view('layout/header', $data);
				$this->load->view('produtos/edit');
				$this->load->view('layout/footer');

			}

		}

	}

	public function add()
	{
		$this->form_validation->set_rules('produto_descricao', '', 'trim|required|min_length[5]|max_length[145]|is_unique[produtos.produto_descricao]');
		$this->form_validation->set_rules('produto_unidade', 'Produto unidade', 'trim|required|min_length[2]|max_length[5]');
		$this->form_validation->set_rules('produto_preco_custo', 'Preço de custo', 'trim|required|max_length[45]');
		$this->form_validation->set_rules('produto_preco_venda', 'Preço de venda', 'trim|required|max_length[145]|callback_check_produto_preco_venda');
		$this->form_validation->set_rules('produto_estoque_minimo', 'Estoque minímo', 'trim|required|greater_than_equal_to[0]');
		$this->form_validation->set_rules('produto_qtde_estoque', 'Quantidade em estoque', 'trim|required');
		$this->form_validation->set_rules('produto_obs', 'Observação', 'trim|max_length[200]');

		if ($this->form_validation->run()) {

			$data = elements(

				array(
					'produto_codigo',
					'produto_categoria_id',
					'produto_marca_id',
					'produto_fornecedor_id',
					'produto_descricao',
					'produto_unidade',
					'produto_preco_custo',
					'produto_preco_venda',
					'produto_estoque_minimo',
					'produto_qtde_estoque',
					'produto_ativo',
					'produto_obs',
				), $this->input->post()

			);

			$data = html_escape($data);


			$this->core_model->insert('produtos', $data);
			redirect('produtos');

		} else {

			//Erro de validação

			$data = array(
				'titulo' => 'Cadastrar produto',
				'styles' => array(
					'vendor/datatables/dataTables.bootstrap4.min.css',
				),

				'scripts' => array(
					'vendor/mask/jquery.mask.min.js',
					'vendor/mask/app.js',
				),

				'produto_codigo' => $this->core_model->generate_unique_code('produtos', 'numeric', 8, 'produto_codigo'),

				'marcas' => $this->core_model->get_all('marcas', array('marca_ativa' => 1)),

				'categorias' => $this->core_model->get_all('categorias', array('categoria_ativa' => 1)),

				'fornecedores' => $this->core_model->get_all('fornecedores', array('fornecedor_ativo' => 1)),
			);


			//*** Carregar a Views
			$this->load->view('layout/header', $data);
			$this->load->view('produtos/add');
			$this->load->view('layout/footer');

		}


	}

	public function check_produto_descricao($produto_descricao)
	{
		/** Recuperar o id do fornecedor */
		$produto_id = $this->input->post('produto_id');

		if ($this->core_model->get_by_id('produtos', array('produto_descricao' => $produto_descricao, 'produto_id !=' => $produto_id))) {
			$this->form_validation->set_message('check_produto_descricao', 'Esse produto ja existe');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function check_produto_preco_venda($produto_preco_venda)
	{
		/** Recuperar o id do fornecedor */
		$produto_preco_custo = $this->input->post('produto_preco_custo');

		$produto_preco_custo = str_replace('.', '', $produto_preco_custo);
		$produto_preco_venda = str_replace('.', '', $produto_preco_venda);

		$produto_preco_custo = str_replace(',', '', $produto_preco_custo);
		$produto_preco_venda = str_replace(',', '', $produto_preco_venda);


		if ($produto_preco_custo > $produto_preco_venda) {
			$this->form_validation->set_message('check_produto_preco_venda', 'Preço de venda deve ser igual ou maior que o preço de custo');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function del($produto_id = NULL)
	{
		if (!$produto_id || !$this->core_model->get_by_id('produtos', array('produto_id ' => $produto_id))) {
			$this->session->set_flashdata('error', 'Produto não encontrado');
			redirect('produtos');
		} else {
			$this->core_model->delete('produtos', array('produto_id' => $produto_id));
			redirect('produtos');
		}
	}
}
