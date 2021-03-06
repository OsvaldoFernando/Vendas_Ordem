<?php
defined('BASEPATH') or exit('Ação não permitida');

class Clientes extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		//*******Verificar se o cliente esta logado
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
			'titulo' => 'Clientes cadastrados',

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
			'clientes' => $this->core_model->get_all('clientes'),

		);

//		echo '<pre>';
//		print_r($data);
//		exit();

		//*******Carregar a minha view
		//*** Carregar a Views
		$this->load->view('layout/header', $data);
		$this->load->view('clientes/index');
		$this->load->view('layout/footer');
	}

	/******* Metódo para adicionar o cliente*/
	public function add()
	{
		$this->form_validation->set_rules('cliente_nome', '', 'trim|required|min_length[4]|max_length[45]');
		$this->form_validation->set_rules('cliente_sobrenome', '', 'trim|required|min_length[4]|max_length[150]');
		$this->form_validation->set_rules('cliente_data_nascimento', '', 'required');

		//***** Recuperar o tipo de cliente
		$cliente_tipo = $this->input->post('cliente_tipo');

		/*
		 * Caso em Angola venha a ter o CPF ou CNPJ
		 * if ($cliente_tipo == 1) {
			$this->form_validation->set_rules('cliente_cpf', '', 'trim|exact_length[18]|is_unique[clientes.cliente_cpf_cnpj]|callback_valida_cpf');
		} else {
			$this->form_validation->set_rules('cliente_cnpj', '', 'trim|exact_length[18]|is_unique[clientes.cliente_cpf_cnpj]|callback_valida_cnpj');
		}*/

//		$this->form_validation->set_rules('cliente_cpf', '', 'trim|exact_length[18]');
//
//		$this->form_validation->set_rules('cliente_cnpj', '', 'trim|exact_length[18]');

		$this->form_validation->set_rules('cliente_email', '', 'trim|required|valid_email|max_length[50]|is_unique[clientes.cliente_email]');

		if (!empty($this->input->post('cliente_telefone'))) {
			$this->form_validation->set_rules('cliente_telefone', '', 'trim|max_length[15]|is_unique[clientes.cliente_telefone]');
		}

//		if (!empty($this->input->post('cliente_celular'))) {
//			$this->form_validation->set_rules('cliente_celular', '', 'trim|max_length[15]|is_unique[clientes.cliente_celular]');
//		}
//		$this->form_validation->set_rules('cliente_cep', '', 'trim|exact_length[5]');
		$this->form_validation->set_rules('cliente_endereco', '', 'trim|required|max_length[155]');
		$this->form_validation->set_rules('cliente_numero_endereco', '', 'trim|max_length[20]');
		$this->form_validation->set_rules('cliente_bairro', '', 'trim|required|max_length[45]');
		$this->form_validation->set_rules('cliente_complemento', '', 'trim|max_length[145]');
		$this->form_validation->set_rules('cliente_cidade', '', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('cliente_estado', '', 'trim|required|exact_length[2]');
		$this->form_validation->set_rules('cliente_obs', '', 'max_length[500]');


		/*Confirmação*/
		if ($this->form_validation->run()) {
			$data = elements(
				array(
					'cliente_id',
					'cliente_data_cadastro',
					'cliente_tipo',
					'cliente_nome',
					'cliente_sobrenome',
					'cliente_data_nascimento',
					'cliente_email',
					'cliente_telefone',
					'cliente_endereco',
					'cliente_numero_endereco',
					'cliente_bairro',
					'cliente_complemento',
					'cliente_cidade',
					'cliente_estado',
					'cliente_ativo',
					'cliente_obs',
				), $this->input->post()
			);

			//******** Verificação do tipo cliente
//			if ($cliente_tipo == 1) {
//				$data['cliente_cpf_cnpj'] = $this->input->post('cliente_cpf');
//			} else {
//				$data['cliente_cpf_cnpj'] = $this->input->post('cliente_cnpj');
//			}

			//***** Para salvar no banco de dado maiúsculo
			$data['cliente_estado'] = strtoupper($this->input->post('cliente_estado'));
			$data = html_escape($data);

			//************* Cadastrar no banco de dado
			$this->core_model->insert('clientes', $data);
			redirect('clientes');

		} else {
			# erro de validação e carregamos a nossa view...
			$data = array(
				//*** Título da página de usuários
				'titulo' => 'Cadastrar clientes',
				'styles' => array(
					'vendor/datatables/dataTables.bootstrap4.min.css',
				),

				//********Carregamdo o script de Mask
				'scripts' => array(
					'vendor/mask/jquery.mask.min.js',
					'vendor/mask/app.js',
					'js/clientes.js',
				),
				//****** Trazer toda informação da tabela
				'clientes' => $this->core_model->get_all('clientes'),
			);
			//*******Carregar a minha view
			$this->load->view('layout/header', $data);
			$this->load->view('clientes/add');
			$this->load->view('layout/footer');
		}
	}

	/******* Metódo para editar o cliente*/
	function edit($cliente_id = NULL)
	{
		//***** Verificação para procurar na base de dado - caso não exista
		if (!$cliente_id || !$this->core_model->get_by_id('clientes', array('cliente_id' => $cliente_id))) {
			$this->session->set_flashdata('error', 'Cliente não encontrado');
			redirect('clientes');
		} else {

			$this->form_validation->set_rules('cliente_nome', '', 'trim|required|min_length[4]|max_length[45]');
			$this->form_validation->set_rules('cliente_sobrenome', '', 'trim|required|min_length[4]|max_length[150]');
			$this->form_validation->set_rules('cliente_data_nascimento', '', 'required');

			//***** Recuperar o tipo de cliente
			$cliente_tipo = $this->input->post('cliente_tipo');

			/*
			 * Caso em Angola venha a ter o CPF ou CNPJ
			 * if ($cliente_tipo == 1) {
				$this->form_validation->set_rules('cliente_cpf', '', 'trim|exact_length[18]|callback_valida_cpf');
			} else {
				$this->form_validation->set_rules('cliente_cnpj', '', 'trim|exact_length[18]|callback_valida_cnpj');
			}*/

//			$this->form_validation->set_rules('cliente_cpf', '', 'trim|exact_length[4]');
//
//			$this->form_validation->set_rules('cliente_cnpj', '', 'trim|exact_length[4]');

			$this->form_validation->set_rules('cliente_email', '', 'trim|valid_email|max_length[50]|callback_check_email');

			if (!empty($this->input->post('cliente_telefone'))) {
				$this->form_validation->set_rules('cliente_telefone', '', 'trim|max_length[15]|callback_check_telefone');
			}

//			if (!empty($this->input->post('cliente_celular'))) {
//				$this->form_validation->set_rules('cliente_celular', '', 'trim|max_length[15]|callback_check_celular');
//			}

//			$this->form_validation->set_rules('cliente_cep', '', 'trim|exact_length[5]');
			$this->form_validation->set_rules('cliente_endereco', '', 'trim|required|max_length[155]');
			$this->form_validation->set_rules('cliente_numero_endereco', '', 'trim|max_length[20]');
			$this->form_validation->set_rules('cliente_bairro', '', 'trim|required|max_length[45]');
			$this->form_validation->set_rules('cliente_complemento', '', 'trim|max_length[145]');
			$this->form_validation->set_rules('cliente_cidade', '', 'trim|required|max_length[50]');
			$this->form_validation->set_rules('cliente_estado', '', 'trim|required|exact_length[2]');
			$this->form_validation->set_rules('cliente_obs', '', 'max_length[500]');

			/*Confirmação*/
			if ($this->form_validation->run()) {

				$cliente_ativo = $this->input->post('cliente_ativo');  //Recuperar o valor que está vindo do POST se for zero (desativado) ou 1 (ativo)

				if ($this->db->table_exists('contas_receber')) { //Verificando se a tabela produto existe, esta verificação porque o controlado produto foi criado antes de criamos a tabela produto

					if ($cliente_ativo == 0 && $this->core_model->get_by_id('contas_receber', array('conta_receber_cliente_id' => $cliente_id, 'conta_receber_status' => 0))) {


						$this->session->set_flashdata('info', 'Este cliente não pode ser desativada, pois está sendo utilizada em <i class="fas fa-hand-holding-usd"></i>&nbsp; Contas a receber');
						redirect('clientes');
					}
				}


				$data = elements(
					array(
						'cliente_id',
						'cliente_data_cadastro',
						'cliente_tipo',
						'cliente_nome',
						'cliente_sobrenome',
						'cliente_data_nascimento',
						'cliente_email',
						'cliente_telefone',
						'cliente_endereco',
						'cliente_numero_endereco',
						'cliente_bairro',
						'cliente_complemento',
						'cliente_cidade',
						'cliente_estado',
						'cliente_ativo',
						'cliente_obs',
					), $this->input->post()
				);

				//******** Verificação do tipo cliente
//				if ($cliente_tipo == 1) {
//					$data['cliente_cpf_cnpj'] = $this->input->post('cliente_cpf');
//				} else {
//					$data['cliente_cpf_cnpj'] = $this->input->post('cliente_cnpj');
//				}

				//***** Para salvar no banco de dado maiúsculo
				$data['cliente_estado'] = strtoupper($this->input->post('cliente_estado'));

				$data = html_escape($data);

				//************* Salvando no banco de dado
				$this->core_model->update('clientes', $data, array('cliente_id' => $cliente_id));

				redirect('clientes');

			} else {
				# erro de validação e carregamos a nossa view...
				$data = array(

					//*** Título da página de usuários
					'titulo' => 'Atualizar clientes',
					'styles' => array(
						'vendor/datatables/dataTables.bootstrap4.min.css',
					),

					//********Carregamdo o script de Mask
					'scripts' => array(
						'vendor/mask/jquery.mask.min.js',
						'vendor/mask/app.js',
					),

					'cliente' => $this->core_model->get_by_id('clientes', array('cliente_id' => $cliente_id)),

					//****** Trazer toda informação da tabela
					'clientes' => $this->core_model->get_all('clientes'),
				);

				//*******Carregar a minha view
				$this->load->view('layout/header', $data);
				$this->load->view('clientes/edit');
				$this->load->view('layout/footer');
			}

		}
	}

	/********** Metódo cheque email**/
	function check_email($cliente_email)
	{
		/** Recuperar o id do cliente */
		$cliente_id = $this->input->post('cliente_id');

		if ($this->core_model->get_by_id('clientes', array('cliente_email' => $cliente_email, 'cliente_id !=' => $cliente_id))) {
			$this->form_validation->set_message('check_email', 'Esse e-mail ja existe');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/********** Metódo cliente telefone**/
	function check_telefone($cliente_telefone)
	{
		/** Recuperar o id do cliente */
		$cliente_id = $this->input->post('cliente_id');

		if ($this->core_model->get_by_id('clientes', array('cliente_telefone' => $cliente_telefone, 'cliente_id !=' => $cliente_id))) {
			$this->form_validation->set_message('check_telefone', 'Esse telefone ja existe');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/********** Metódo cliente celular**/
//	function check_celular($cliente_celular)
//	{
//		/** Recuperar o id do cliente */
//		$cliente_id = $this->input->post('cliente_id');
//
//		if ($this->core_model->get_by_id('clientes', array('cliente_celular' => $cliente_celular, 'cliente_id !=' => $cliente_id))) {
//			$this->form_validation->set_message('check_celular', 'Esse celular ja existe');
//			return FALSE;
//		} else {
//			return TRUE;
//		}
//	}

	/********** Método para eliminar cliente***/
	public function del($cliente_id = NULL)
	{
		if (!$cliente_id || !$this->core_model->get_by_id('clientes', array('cliente_id' => $cliente_id))) {
			$this->session->set_flashdata('error', 'Cliente não encontrado');
			redirect('clientes');
		} else {
			$this->core_model->delete('clientes', array('cliente_id' => $cliente_id));
			redirect('clientes');
		}
	}
}




/*
 * 	Validação caso em Angola venha a ter o CPF ou CNPJ
 * public function valida_cnpj($cnpj)
{

	// Verifica se um número foi informado
	if (empty($cnpj)) {
		$this->form_validation->set_message('valida_cnpj', 'Por favor digite um CNPJ válido');
		return false;
	}

	if ($this->input->post('cliente_id')) {

		$cliente_id = $this->input->post('cliente_id');

		if ($this->core_model->get_by_id('clientes', array('cliente_id !=' => $cliente_id, 'cliente_cpf_cnpj' => $cnpj))) {
			$this->form_validation->set_message('valida_cnpj', 'Esse CNPJ já existe');
			return FALSE;
		}
	}

	// Elimina possivel mascara
	$cnpj = preg_replace("/[^0-9]/", "", $cnpj);
	$cnpj = str_pad($cnpj, 14, '0', STR_PAD_LEFT);


	// Verifica se o numero de digitos informados é igual a 11
	if (strlen($cnpj) != 14) {
		$this->form_validation->set_message('valida_cnpj', 'Por favor digite um CNPJ válido');
		return false;
	}

	// Verifica se nenhuma das sequências invalidas abaixo
	// foi digitada. Caso afirmativo, retorna falso
	else if ($cnpj == '00000000000000' ||
		$cnpj == '11111111111111' ||
		$cnpj == '22222222222222' ||
		$cnpj == '33333333333333' ||
		$cnpj == '44444444444444' ||
		$cnpj == '55555555555555' ||
		$cnpj == '66666666666666' ||
		$cnpj == '77777777777777' ||
		$cnpj == '88888888888888' ||
		$cnpj == '99999999999999') {
		$this->form_validation->set_message('valida_cnpj', 'Por favor digite um CNPJ válido');
		return false;

		// Calcula os digitos verificadores para verificar se o
		// CPF é válido
	} else {

		$j = 5;
		$k = 6;
		$soma1 = "";
		$soma2 = "";

		for ($i = 0; $i < 13; $i++) {

			$j = $j == 1 ? 9 : $j;
			$k = $k == 1 ? 9 : $k;

			//$soma2 += ($cnpj{$i} * $k);

			//$soma2 = intval($soma2) + ($cnpj{$i} * $k); //Para PHP com versão < 7.4
			$soma2 = intval($soma2) + ($cnpj[$i] * $k); //Para PHP com versão > 7.4

			if ($i < 12) {
				//$soma1 = intval($soma1) + ($cnpj{$i} * $j); //Para PHP com versão < 7.4
				$soma1 = intval($soma1) + ($cnpj[$i] * $j); //Para PHP com versão > 7.4
			}

			$k--;
			$j--;
		}

		$digito1 = $soma1 % 11 < 2 ? 0 : 11 - $soma1 % 11;
		$digito2 = $soma2 % 11 < 2 ? 0 : 11 - $soma2 % 11;

		if (!($cnpj{12} == $digito1) and ($cnpj{13} == $digito2)) {
			$this->form_validation->set_message('valida_cnpj', 'Por favor digite um CNPJ válido');
			return false;
		} else {
			return true;
		}
	}
}

public function valida_cpf($cpf)
{

	if ($this->input->post('cliente_id')) {

		$cliente_id = $this->input->post('cliente_id');

		if ($this->core_model->get_by_id('clientes', array('cliente_id !=' => $cliente_id, 'cliente_cpf_cnpj' => $cpf))) {
			$this->form_validation->set_message('valida_cpf', 'Este CPF já existe');
			return FALSE;
		}
	}

	$cpf = str_pad(preg_replace('/[^0-9]/', '', $cpf), 11, '0', STR_PAD_LEFT);
	// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
	if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {

		$this->form_validation->set_message('valida_cpf', 'Por favor digite um CPF válido');
		return FALSE;
	} else {
		// Calcula os números para verificar se o CPF é verdadeiro
		for ($t = 9; $t < 11; $t++) {
			for ($d = 0, $c = 0; $c < $t; $c++) {
				//$d += $cpf{$c} * (($t + 1) - $c); // Para PHP com versão < 7.4
				$d += $cpf[$c] * (($t + 1) - $c);
				//Para PHP com versão < 7.4
			}
			$d = ((10 * $d) % 11) % 10;
			if ($cpf{$c} != $d) {
				$this->form_validation->set_message('valida_cpf', 'Por favor digite um CPF válido');
				return FALSE;
			}
		}
		return TRUE;
	}
}
*/

