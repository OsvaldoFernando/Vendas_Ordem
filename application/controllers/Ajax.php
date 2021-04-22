<?php
defined('BASEPATH') or exit('Ação não permitida');

class Ajax extends CI_Controller
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
		redirect('/'); //Raiz do site (Home)
	}

	public function produtos()
	{
//		Verificar para impedir que o acesso desse módulo seja pela URL

		if (!$this->input->is_ajax_request()) { //Se a nossa requisição não for com ajax_request ele vai da erro

			exit('Ação não permitida');
		} else {

			$busca = $this->input->post('term'); //Vai ser utilizado virado ao nosso plugin autocomplete
			$data['response'] = 'false';

			$query = $this->core_model->auto_complete_produtos($busca);

			if ($query) {

				$data['response'] = 'true';

				$data['message'] = array();

				foreach ($query as $row) {

					$data['message'][] = array(

						'id' => $row->produto_id,
						'value' => $row->produto_descricao, //Responsável por armazenar toda sua pesquisa
						'produto_preco_venda' => $row->produto_preco_venda,
						'produto_qtde_estoque' => $row->produto_qtde_estoque,
					);
				}

				echo json_encode($data); //Estou enviando para minha requisão em caso de sucesso, eu retorno o produto que foi encontrado no banco de dado de acordo que vc informou no input
			} else {
				echo json_encode($data);
			}
		}

	}

	public function servicos()
	{
//		Verificar para impedir que o acesso desse módulo seja pela URL

		if (!$this->input->is_ajax_request()) { //Se a nossa requisição não for com ajax_request ele vai da erro

			exit('Ação não permitida');
		} else {

			$busca = $this->input->post('term'); //Vai ser utilizado virado ao nosso plugin autocomplete
			$data['response'] = 'false';

			$query = $this->core_model->auto_complete_servicos($busca);

			if ($query) {

				$data['response'] = 'true';

				$data['message'] = array();

				foreach ($query as $row) {

					$data['message'][] = array(

						'id' => $row->servico_id,
						'value' => $row->servico_descricao, //Responsável por armazenar toda sua pesquisa
						'servico_preco' => $row->servico_preco,
					);
				}

				echo json_encode($data); //Estou enviando para minha requisão em caso de sucesso, eu retorno o produto que foi encontrado no banco de dado de acordo que vc informou no input
			} else {
				echo json_encode($data);
			}
		}

	}

}
