<?php
defined('BASEPATH') or exit('Ação não permitida');

class Usuarios extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		//Definir se há sessão
		if (!$this->ion_auth->logged_in()) {
			//**** Mensagem de sessão
			$this->session->set_flashdata('info', 'Sua sessão expirou');
			redirect('login');
		}
	}

	//*** Chamar a Views os usuários cadastrados
	public function index()
	{

		//*** Variável data do tipo array (informação da documentação do plugin --- Usuário --
		$data = array(

			//*** Título da página de usuários
			'titulo' => 'Usuários cadastrados',

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

			'usuarios' => $users = $this->ion_auth->users()->result(),
		);

		//*** Debug para saber o que esta vindo do banco de dado
		//echo '<pre>';
		//print_r($data[usuarios]);
		//exit();


		//*** Carregar a Views
		$this->load->view('layout/header', $data);
		$this->load->view('usuarios/index');
		$this->load->view('layout/footer');

		//*** Vamos criar uma pasta chamada usuário dentro da nossa Views/pode copiar o index da pasta home
	}

	//*** Função de adicionar
	public function add()
	{

		///*** Validando os campos
		$this->form_validation->set_rules('first_name', '', 'trim|required');
		$this->form_validation->set_rules('last_name', '', 'trim|required');
		$this->form_validation->set_rules('email', '', 'trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('username', '', 'trim|required|is_unique[users.username]');
		$this->form_validation->set_rules('password', 'Senha', 'required|min_length[5] |max_length[255]');
		$this->form_validation->set_rules('confirm_password', 'Confirme', 'matches[password]');

		//*** abrir a função de validar o formulário
		if ($this->form_validation->run()) {

			//exit('Validado');
			//****** Vamos recuperar do banco de dado
			$username = $this->security->xss_clean($this->input->post('username'));
			$password = $this->security->xss_clean($this->input->post('password'));
			$email = $this->security->xss_clean($this->input->post('email'));
			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'username' => $this->input->post('username'),
				'active' => $this->input->post('active'),
				// Tem mais campos a preecnher na tabela mas estamos a preencher os principais
			);
			$group = array($this->input->post('perfil_usuario')); // Sets user to admin.

			$additional_data = $this->security->xss_clean($additional_data);

			$group = $this->security->xss_clean($group);

// Verificar se foi executado com sucesso
			if ($this->ion_auth->register($username, $password, $email, $additional_data, $group)) {

				$this->session->set_flashdata('sucesso', 'Dados salvos com sucesso');

			} else {
				$this->session->set_flashdata('error', 'Erro ao salvar os dados');
			}

			redirect('usuarios');
			//alterar ion_auth_model

		} else {

			//**** Erro de validação
			$data = array(
				//*** Caso exista cria o array data
				//*** Título da página
				'titulo' => 'Cadastrar usuário',
				'styles' => array(
					'vendor/datatables/dataTables.bootstrap4.min.css',
				),


			);

			//*** Carregando as Views
			$this->load->view('layout/header', $data);
			$this->load->view('usuarios/add');
			$this->load->view('layout/footer');

		}

	}

	//*** Função edit
	public function edit(
		$usuario_id = NULL)
	{

		//*** Verificar se não foi passado ou passado mais ele não existe
		if (!$usuario_id || !$this->ion_auth->user($usuario_id)->row()) {

			//**** Informação (mensagem)
			$this->session->set_flashdata('error', 'Usuário não encontrado');
			redirect('usuarios'); // Este valor é a pasta

		} else {

			/*
			 *  [first_name] => istrator
				[email] => admin@admin.com
				[username] => administrator
				[active] => 1
				[perfil_usuario] => 1
				[password] =>
				[confirm_password] =>
				[usuario_id] => 1
			 */


//			//*** Fazendo debug
//			echo '<pre>';
//			print_r($this->input->post());
//			exit();


			///*** Validando os campos
			$this->form_validation->set_rules('first_name', '', 'trim|required');
			$this->form_validation->set_rules('last_name', '', 'trim|required');
			$this->form_validation->set_rules('email', '', 'trim|required|valid_email|callback_email_check');
			$this->form_validation->set_rules('username', '', 'trim|required|callback_username_check');
			$this->form_validation->set_rules('password', 'Senha', 'min_length[5] |max_length[255]');
			$this->form_validation->set_rules('confirm_password', 'Confirme', 'matches[password]');

			//*** Verificando a validação do campo
			if ($this->form_validation->run()) {

				$data = elements(
					array(
						'first_name',
						'last_name',
						'email',
						'username',
						'active',
						'password'
					), $this->input->post()
				);

				$data = $this->security->xss_clean($data);

				//**** Verificar se o campo password foi passado
				$password = $this->input->post('password');

				if (!$password) {

					unset($data['password']);
				}


				if ($this->ion_auth->update($usuario_id, $data)) {

					// [perfil_usuario] => 1 verificar
					///*** Recuperando o perfil de usuário que esta vindo do bd
					$perfil_usuario_db = $this->ion_auth->get_users_groups($usuario_id)->row();

					//*** Recuperando o perfil de usuário que esta vindo do post
					$perfil_usuario_post = $this->input->post('perfil_usuario');

					//****** Verificação se for diferente atuliza o grupo
					if ($perfil_usuario_db->id != $perfil_usuario_post) {

						//******* Remover usuário do grupo
						$this->ion_auth->remove_from_group($perfil_usuario_db->id, $usuario_id);

						//********** Adicionar usuário ao grupo
						$this->ion_auth->add_to_group($perfil_usuario_post, $usuario_id);
					}

					//******* Mensagem de sucesso
					$this->session->set_flashdata('sucesso', 'Dados salvos com sucesso');
				} else {
					$this->session->set_flashdata('error', 'Erro ao salvar os dados');
				}
				redirect('usuarios');
			} else {

				$data = array(

					//*** Caso exista cria o array data
					//*** Título da página
					'titulo' => 'Editar usuário',

					//*** Estou enviando os dados deste usuário
					'usuario' => $this->ion_auth->user($usuario_id)->row(),

					//*** Trazer o perfil do usuário
					'perfil_usuario' => $this->ion_auth->get_users_groups($usuario_id)->row(),
				);


				//*** Carregando as Views
				$this->load->view('layout/header', $data);
				$this->load->view('usuarios/edit');
				$this->load->view('layout/footer');
			}
		}
	}

	//*** Função email_check para validação
	public function email_check($email)
	{
		//**** Vamos recuperar o id do usuário
		$usuario_id = $this->input->post('usuario_id');

		//***** Criação da função
		//****** Carregar o nosso core_model
		if ($this->core_model->get_by_id('users', array('email' => $email, 'id !=' => $usuario_id))) {

			$this->form_validation->set_message('email_check', 'Esse e-mail ja existe');

			return FALSE;

		} else {
			return TRUE;
		}
	}

	//*** Função username_check para validação
	public function username_check($username)
	{
		//**** Vamos recuperar o id do usuário
		$usuario_id = $this->input->post('usuario_id');

		//***** Criação da função
		//****** Carregar o nosso core_model
		if ($this->core_model->get_by_id('users', array('username' => $username, 'id !=' => $usuario_id))) {

			$this->form_validation->set_message('username_check', 'Esse usuário ja existe');

			return FALSE;

		} else {
			return TRUE;
		}
	}

	//*** Função de deletar
	public function del($usuario_id = NULL)
	{
		//******** Não encontra usuário não cadastradi
		if (!$usuario_id || !$this->ion_auth->user($usuario_id)->row()) {
			$this->session->set_flashdata('error', 'Usuário não encontrado');
			redirect('usuarios');
		}

		//********** Não deleta usuário administrador
		if ($this->ion_auth->is_admin($usuario_id)) {
			$this->session->set_flashdata('error', 'O administrador não pode ser excluído');
			redirect('usuarios');
		}

		if ($this->ion_auth->delete_user($usuario_id)) {
			$this->session->set_flashdata('sucesso', 'Usuário excluído com sucesso');
			redirect('usuarios');
		} else {
			$this->session->set_flashdata('error', 'Usuário não pode ser excluído');
			redirect('usuarios');
		}
	}
}
