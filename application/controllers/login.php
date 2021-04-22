<?php
defined('BASEPATH') OR exit('Ação não permitida');

class Login extends CI_Controller
{
	public function index()
	{

		$data = array(
			'titulo' => 'Login',
		);
			//****** Carregar o header
			$this->load->view('layout/header', $data);
			//***** Chamar a view login
			$this->load->view('login/index');
			//********** Carregando o footer
			$this->load->view('layout/footer');
	}

	public function auth()
	{
		/*
		 * [email] => osvaldofernandomuondoqueta@gmail.com
    		[password] => 1234
		 */

		$identity = $this->security->xss_clean($this->input->post('email'));
		$password = $this->security->xss_clean($this->input->post('password'));
		$remember = FALSE; // remember the user

		if ($this->ion_auth->login($identity, $password, $remember)) {

			redirect('home');

		}else{
			//****** Mensagem de erro para usuário
			$this->session->set_flashdata('error', 'Verifique seu e-mail ou senha');

			redirect('login');

		}
	}

	public function logout()
	{
		$this->ion_auth->logout();
		redirect('login');
	}

}
