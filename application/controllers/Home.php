<?php
defined('BASEPATH') or exit('Ação não permitida');

class Home extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			//**** Mensagem de sessão
			$this->session->set_flashdata('info', 'Sua sessão expirou');
			redirect('login');
		}
	}

	public function index()
	{
		//***Vai carregar o header
		$this->load->view('layout/header');


		//*** Vai carregar a view Home que ainda não esta criada
		$this->load->view('home/index');


		//***Vai carregar o footer
		$this->load->view('layout/footer');
	}
}
