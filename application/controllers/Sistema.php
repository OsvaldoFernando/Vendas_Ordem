<?php
defined('BASEPATH') or exit('Ação não permitida');

class Sistema extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'titulo' => 'Editar informações do sistema',
			'styles' => array(
				'vendor/datatables/dataTables.bootstrap4.min.css',
			),


			//********Carregamdo o script de Mask
			'scripts' => array(
				'vendor/mask/jquery.mask.min.js',
				'vendor/mask/app.js',
			),

			'sistema' => $this->core_model->get_by_id('sistema', array('sistema_id' => 1)),
		);


		//******* Validar os campos do sistema
		$this->form_validation->set_rules('sistema_razao_social', 'Razão social', 'required|min_length[10]|max_length[145]');
		$this->form_validation->set_rules('sistema_nome_fantasia', 'Nome fantasia', 'required|min_length[5]|max_length[145]');
		$this->form_validation->set_rules('sistema_nif', '', 'required|exact_length[14]'); //14 car
		$this->form_validation->set_rules('sistema_num_agt', '', 'required|max_length[25]');
		$this->form_validation->set_rules('sistema_telefone_fixo', '', 'max_length[25]');
		$this->form_validation->set_rules('sistema_telefone_movel', '', 'required|max_length[25]');
		$this->form_validation->set_rules('sistema_email', '', 'required|valid_email');
		$this->form_validation->set_rules('sistema_site_url', 'URL do site', 'required|valid_url|max_length[100]');
		$this->form_validation->set_rules('sistema_cep', 'CEP', 'max_length[145]');
		$this->form_validation->set_rules('sistema_endereco', 'Endereço', 'max_length[25]');
		$this->form_validation->set_rules('sistema_numero', 'Número', 'max_length[25]');
		$this->form_validation->set_rules('sistema_cidade', 'Cidade', 'required|max_length[45]');
		$this->form_validation->set_rules('sistema_estado', 'Sigla', 'required|max_length[2]');
		$this->form_validation->set_rules('sistema_txt_ordem_servico', 'Texto da ordem de serviço e venda', 'max_length[500]');

		//****** Verificar se tem erro de validação
		if ($this->form_validation->run()) {

			//******* Inserindo no banco de dado
			$data = elements(
				array(
					//****** Colocar as informações
					'sistema_razao_social',
					'sistema_nome_fantasia',
					'sistema_nif',
					'sistema_num_agt',
					'sistema_telefone_fixo',
					'sistema_telefone_movel',
					'sistema_site_url',
					'sistema_email',
					'sistema_endereco',
					'sistema_cep',
					'sistema_numero',
					'sistema_cidade',
					'sistema_estado',
					'sistema_txt_ordem_servico',

					//***** Capturar tudo que vem do post
				), $this->input->post()
			);
			/*Limpar os dados*/
			$data = $this->security->xss_clean($data);

			/******** Teste para salvar os dados sem fazer a limpeza do array com o help-security*/
			$this->core_model->update('sistema', $data, array('sistema_id' => 1));

			redirect('sistema');

		} else {
			// Erro de validação
			//**** Quando temos erro de validação
			$this->load->view('layout/header', $data);
			$this->load->view('sistema/index');
			$this->load->view('layout/footer');
		}

	}
}
