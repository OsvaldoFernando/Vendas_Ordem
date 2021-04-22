<?php
defined('BASEPATH') or exit('Ação não permitida');

class Ordem_servicos extends CI_Controller
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
		$this->load->model('ordem_servicos_model');
	}

	public function index()
	{
		//*** Variável data do tipo array (informação da documentação do plugin --- Usuário --
		$data = array(

			//*** Título da página de usuários
			'titulo' => 'Ordem de serviços cadastrados',

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
			'ordens_servicos' => $this->ordem_servicos_model->get_all(),

		);

//		echo '<pre>';
//		print_r($data['ordens_servicos']);
//		exit();


		//*******Carregar a minha view
		//*** Carregar a Views
		$this->load->view('layout/header', $data);
		$this->load->view('Ordem_servicos/index');
		$this->load->view('layout/footer');
	}

	public function add()
	{
		$this->form_validation->set_rules('ordem_servico_cliente_id', '', 'required');
		$this->form_validation->set_rules('ordem_servico_equipamento', 'Marca', 'trim|required|min_length[2]|max_length[80]');
		$this->form_validation->set_rules('ordem_servico_marca_equipamento', 'Marca', 'trim|required|min_length[2]|max_length[80]');
		$this->form_validation->set_rules('ordem_servico_modelo_equipamento', 'Modelo', 'trim|required|min_length[2]|max_length[80]');
		$this->form_validation->set_rules('ordem_servico_acessorios', 'Acessórios', 'trim|required|max_length[300]');
		$this->form_validation->set_rules('ordem_servico_defeito', 'Defeito', 'trim|required|max_length[700]');

		if ($this->form_validation->run()) {

			$ordem_servico_valor_total = str_replace('AKZ', "", trim($this->input->post('ordem_servico_valor_total')));

			$data = elements(
				array(
					'ordem_servico_cliente_id',
					'ordem_servico_status',
					'ordem_servico_equipamento',
					'ordem_servico_marca_equipamento',
					'ordem_servico_modelo_equipamento',
					'ordem_servico_defeito',
					'ordem_servico_acessorios',
					'ordem_servico_obs',
					'ordem_servico_valor_desconto',
					'ordem_servico_valor_total',
				), $this->input->post()

			);

			$data['ordem_servico_valor_total'] = trim(preg_replace('/\$/', '', $ordem_servico_valor_total));

			$data = html_escape($data);

			//************* Salvando no banco de dado
			$this->core_model->insert('ordens_servicos', $data, TRUE); //Quando colcoamos true significa que vamos pegar do banco de dado último valor a ser inserido do core_model em insert

			//recuperar id

			$id_ordem_servico = $this->session->userdata('last_id');

			$servico_id = $this->input->post('servico_id');
			$servico_quantidade = $this->input->post('servico_quantidade');
			$servico_desconto = str_replace('%', '', $this->input->post('servico_desconto'));

			$servico_preco = str_replace('AKZ', '', $this->input->post('servico_preco'));
			$servico_item_total = str_replace('AKZ', '', $this->input->post('servico_item_total'));
			$servico_preco = str_replace(',', '', $servico_preco);
			$servico_item_total = str_replace(',', '', $servico_item_total);


			$qty_servico = count($servico_id);

			$ordem_servico_id = $this->input->post('ordem_servico_id');

			for ($i = 0; $i < $qty_servico; $i++) {

				$data = array(
					'ordem_ts_id_ordem_servico' => $id_ordem_servico,
					'ordem_ts_id_servico' => $servico_id[$i],
					'ordem_ts_quantidade' => $servico_quantidade[$i],
					'ordem_ts_valor_unitario' => $servico_preco[$i],
					'ordem_ts_valor_desconto' => $servico_desconto[$i],
					'ordem_ts_valor_total' => $servico_item_total[$i],
				);

				$data = html_escape($data);

				//Para cada dado do for vou inserir os meus dados
				$this->core_model->insert('ordem_tem_servicos', $data);

			}

			//Criar recurso PDF

			redirect('os/imprimir/' . $id_ordem_servico);


//				echo '<pre>';
//				print_r($this->input->post());
//				exit();

		} else {

			$data = array(
				'titulo' => 'Cadastrar ordem de Serviço',
				'styles' => array(
					'vendor/select2/select2.min.css',
					'vendor/autocomplete/jquery-ui.css',
					'vendor/autocomplete/estilo.css',
				),

				'scripts' => array(
					'vendor/autocomplete/jquery-migrate.js', //Vem primeiro
					'vendor/calcx/jquery-calx-sample-2.2.8.js',
					'vendor/calcx/os.js',
					'vendor/select2/select2.min.js',
					'vendor/select2/app.js',
					'vendor/sweetalert2/sweetalert2.js',
					'vendor/autocomplete/jquery-ui.js', //Vem último

				),
				'clientes' => $this->core_model->get_all('clientes', array('cliente_ativo' => 1)),
			);

			//*** Carregar a Views
			$this->load->view('layout/header', $data);
			$this->load->view('ordem_servicos/add');
			$this->load->view('layout/footer');
		}
	}

	public function imprimir($ordem_servico_id = NULL)
	{
//		Se ele foi passado
		if (!$ordem_servico_id || !$this->core_model->get_by_id('ordens_servicos', array('ordem_servico_id' => $ordem_servico_id))) {
			$this->session->set_flashdata('error', 'Ordem de serviço não encontrada');
			redirect('os');
		} else {

			$data = array(
				'titulo' => 'Escolha uma opção',
				//Enviar dados da ordem
				'ordem_servico' => $this->core_model->get_by_id('ordens_servicos', array('ordem_servico_id' => $ordem_servico_id)),

			);
			//*** Carregar a Views
			$this->load->view('layout/header', $data);
			$this->load->view('ordem_servicos/imprimir');
			$this->load->view('layout/footer');
		}
	}

	public function edit($ordem_servico_id = NULL)
	{
//		Se ele foi passado
		if (!$ordem_servico_id || !$this->core_model->get_by_id('ordens_servicos', array('ordem_servico_id' => $ordem_servico_id))) {
			$this->session->set_flashdata('error', 'Ordem de serviço não encontrada');
			redirect('os');

		} else {

			$this->form_validation->set_rules('ordem_servico_cliente_id', '', 'required');

			$ordem_servico_status = $this->input->post('ordem_servico_status');

			if ($ordem_servico_status == 1) {
				$this->form_validation->set_rules('ordem_servico_forma_pagamento_id', '', 'required');
			}


			$this->form_validation->set_rules('ordem_servico_equipamento', 'Marca', 'trim|required|min_length[2]|max_length[80]');
			$this->form_validation->set_rules('ordem_servico_marca_equipamento', 'Marca', 'trim|required|min_length[2]|max_length[80]');
			$this->form_validation->set_rules('ordem_servico_modelo_equipamento', 'Modelo', 'trim|required|min_length[2]|max_length[80]');
			$this->form_validation->set_rules('ordem_servico_acessorios', 'Acessórios', 'trim|required|max_length[300]');
			$this->form_validation->set_rules('ordem_servico_defeito', 'Defeito', 'trim|required|max_length[700]');

			if ($this->form_validation->run()) {

				$ordem_servico_valor_total = str_replace('AKZ', "", trim($this->input->post('ordem_servico_valor_total')));

				$data = elements(
					array(
						'ordem_servico_cliente_id',
						'ordem_servico_forma_pagamento_id',
						'ordem_servico_status',
						'ordem_servico_equipamento',
						'ordem_servico_marca_equipamento',
						'ordem_servico_modelo_equipamento',
						'ordem_servico_defeito',
						'ordem_servico_acessorios',
						'ordem_servico_obs',
						'ordem_servico_valor_desconto',
						'ordem_servico_valor_total',
					), $this->input->post()

				);

				if ($ordem_servico_status == 0) {
					unset($data['ordem_servico_forma_pagamento_id']);
				}

				$data['ordem_servico_valor_total'] = trim(preg_replace('/\$/', '', $ordem_servico_valor_total));

				$data = html_escape($data);

				//************* Salvando no banco de dado
				$this->core_model->update('ordens_servicos', $data, array('ordem_servico_id' => $ordem_servico_id));

				/*Deleteando de ordem ordem_tem_servico, os serviços antigos da ordem editada*/
				$this->ordem_servicos_model->delete_old_services($ordem_servico_id);

				$servico_id = $this->input->post('servico_id');
				$servico_quantidade = $this->input->post('servico_quantidade');
				$servico_desconto = str_replace('%', '', $this->input->post('servico_desconto'));

				$servico_preco = str_replace('AKZ', '', $this->input->post('servico_preco'));
				$servico_item_total = str_replace('AKZ', '', $this->input->post('servico_item_total'));
				$servico_preco = str_replace(',', '', $servico_preco);
				$servico_item_total = str_replace(',', '', $servico_item_total);


				$qty_servico = count($servico_id);

				$ordem_servico_id = $this->input->post('ordem_servico_id');

				for ($i = 0; $i < $qty_servico; $i++) {

					$data = array(
						'ordem_ts_id_ordem_servico' => $ordem_servico_id,
						'ordem_ts_id_servico' => $servico_id[$i],
						'ordem_ts_quantidade' => $servico_quantidade[$i],
						'ordem_ts_valor_unitario' => $servico_preco[$i],
						'ordem_ts_valor_desconto' => $servico_desconto[$i],
						'ordem_ts_valor_total' => $servico_item_total[$i],
					);

					$data = html_escape($data);

					//Para cada dado do for vou inserir os meus dados
					$this->core_model->insert('ordem_tem_servicos', $data);

				}

				//Criar recurso PDF

				redirect('os/imprimir/' . $ordem_servico_id);


//				echo '<pre>';
//				print_r($this->input->post());
//				exit();

			} else {

				$data = array(
					'titulo' => 'Atualizar ordem de Serviço',
					'styles' => array(
						'vendor/select2/select2.min.css',
						'vendor/autocomplete/jquery-ui.css',
						'vendor/autocomplete/estilo.css',
					),

					'scripts' => array(
						'vendor/autocomplete/jquery-migrate.js', //Vem primeiro
						'vendor/calcx/jquery-calx-sample-2.2.8.js',
						'vendor/calcx/os.js',
						'vendor/select2/select2.min.js',
						'vendor/select2/app.js',
						'vendor/sweetalert2/sweetalert2.js',
						'vendor/autocomplete/jquery-ui.js', //Vem último

					),
					'clientes' => $this->core_model->get_all('clientes', array('cliente_ativo' => 1)),

					'formas_pagamentos' => $this->core_model->get_all('formas_pagamentos', array('forma_pagamento_ativa' => 1)),

					'os_tem_servicos' => $this->ordem_servicos_model->get_all_servicos_by_ordem($ordem_servico_id),

				);

				$ordem_servico = $data['ordem_servico'] = $this->ordem_servicos_model->get_by_id($ordem_servico_id);


//				echo '<pre>';
//				print_r($data['os_tem_servicos']);
//				exit();

//				echo '<pre>';
//				print_r($ordem_servico);
//				exit();


				//*** Carregar a Views
				$this->load->view('layout/header', $data);
				$this->load->view('ordem_servicos/edit');
				$this->load->view('layout/footer');
			}
		}
	}

	public function pdf($ordem_servico_id = NULL)
	{
//		Se ele foi passado
		if (!$ordem_servico_id || !$this->core_model->get_by_id('ordens_servicos', array('ordem_servico_id' => $ordem_servico_id))) {
			$this->session->set_flashdata('error', 'Ordem de serviço não encontrada');
			redirect('os');
		} else {

			//Recuperar ou retornar as informações do sistema

			$empresa = $this->core_model->get_by_id('sistema', array('sistema_id' => 1));

//			echo '<pre>';
//			print_r($empresa);
//			exit();

			$ordem_servico = $this->ordem_servicos_model->get_by_id($ordem_servico_id);


//			echo '<pre>';
//			print_r($ordem_servico);
//			exit();

			$file_name = 'O.S&nbsp;' . $ordem_servico->ordem_servico_id;

//			echo '<pre>';
//			print_r($file_name);
//			exit();

//			Agora vou começar a criar o meu HTML

			$html = '<html>';

			$html .= '<head>';

			$html .= '<title>' . $empresa->sistema_nome_fantasia . ' | Impressão de Ordem de Serviços </title>'; //Pego o objeto empresa e pegando o nome do campo

			$html .= '</head>';

			$html .= '<body style="font-size: 12px">';

			$html .= '<h4 align="center">

					' . $empresa->sistema_nome_fantasia . '<br>
					' . 'AGT Número:' . $empresa->sistema_num_agt . '<br>
					' . $empresa->sistema_endereco . ',&nbsp;' . $empresa->sistema_numero . '<br>
					' . 'CEP: ' . $empresa->sistema_cep . ',&nbsp;' . $empresa->sistema_cidade . ', &nbsp;' . $empresa->sistema_estado . '<br>
					' . 'Telefone:' . $empresa->sistema_telefone_fixo . '<br>
					' . 'E-mail:' . $empresa->sistema_email . '<br>
					</h4>';

			$html .= '<hr>';

//			Dados do cliente

			$html .= '<p align="right" style="font-size: 12px"> O.S Nº&nbsp;' . $ordem_servico->ordem_servico_id . '</p>';

			$html .= '<p>'
				. '<strong>Cliente: </strong>' . $ordem_servico->cliente_nome_completo . '<br/>'
				. '<strong>Telefone: </strong>' . $ordem_servico->cliente_telefone . '<br/>'
				. '<strong>Data de emissão: </strong>' . formata_data_banco_com_hora($ordem_servico->ordem_servico_data_emissao) . '<br/>'
				. '<strong>Forma de pagamento: </strong>' . ($ordem_servico->ordem_servico_status == 1 ? $ordem_servico->forma_pagamento : 'Em aberto') . '<br/>';
			'</p>';

			$html .= '<hr>';

//			Dados da ordem
			$html .= '<table width="100%" border: solid #ddd 1px>';

			$html .= '<tr>';

			$html .= '<th>Serviço</th>';
			$html .= '<th>Quantidade</th>';
			$html .= '<th>Valor unitário</th>';
			$html .= '<th>Desconto</th>';
			$html .= '<th>Valor total</th>';

			$html .= '</tr>';

			$ordem_servico_id = $ordem_servico->ordem_servico_id;

			$servicos_ordem = $this->ordem_servicos_model->get_all_servicos($ordem_servico_id);

//			echo '<pre>';
//			print_r($servicos_ordem);
//			exit();

			$valor_final_os = $this->ordem_servicos_model->get_valor_final_os($ordem_servico_id);

//			echo '<pre>';
//			print_r($valor_final_os);
//			exit();

			foreach ($servicos_ordem as $servico):

				$html .= '<tr>';

				$html .= '<td>' . $servico->servico_nome . '</td>';
				$html .= '<td>' . $servico->ordem_ts_quantidade . '</td>';
				$html .= '<td>' . 'AKZ&nbsp;' . $servico->ordem_ts_valor_unitario . '</td>';
				$html .= '<td>' . '%&nbsp;' . $servico->ordem_ts_valor_desconto . '</td>';
				$html .= '<td>' . 'AKZ&nbsp;' . $servico->ordem_ts_valor_total . '</td>';

				$html .= '</tr>';

			endforeach;

			$html .= '<th colspan="3">';

			$html .= '<td style="border-top: solid #ddd 1px"><strong>Valor final</strong></td>';
			$html .= '<td style="border-top: solid #ddd 1px">' . $valor_final_os->os_valor_total . '</td>'; //Peguei o valor final do método get_valor_final_os

			$html .= '</table>';

			$html .= '</body>';

			$html .= '</html>';

//			echo '<pre>';
//			print_r($html);
//			exit();

			$this->pdf->createPDF($html, $file_name, false); //Quando colocamos o false é para abrir o PDF no browser e o TRUE faz o download

		}
	}

	public function del($ordem_servico_id = NULL)
	{
//		Se ele foi passado
		if (!$ordem_servico_id || !$this->core_model->get_by_id('ordens_servicos', array('ordem_servico_id' => $ordem_servico_id))) {
			$this->session->set_flashdata('error', 'Ordem de serviço não encontrada');
			redirect('os');
		}

		if ($this->core_model->get_by_id('ordens_servicos', array('ordem_servico_id ' => $ordem_servico_id, 'ordem_servico_status' => 0))) {
			$this->session->set_flashdata('error', 'Não é possível excluir uma ordem de serviço em aberto');
			redirect('os');
		}

		$this->core_model->delete('ordens_servicos', array('ordem_servico_id' => $ordem_servico_id));
		redirect('os');

	}

}
