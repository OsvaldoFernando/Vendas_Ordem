<?php
defined('BASEPATH') or exit('Ação não permitida');

class Vendas extends CI_Controller
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
		$this->load->model('vendas_model');
		$this->load->model('produtos_model');
	}

	public function index()
	{
		//*** Variável data do tipo array (informação da documentação do plugin --- Usuário --
		$data = array(

			//*** Título da página de usuários
			'titulo' => 'Vendas cadastradas',

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
			'vendas' => $this->vendas_model->get_all(),

		);

//    echo '<pre>';
//    print_r($data['vendas']);
//    exit();


		//*******Carregar a minha view
		//*** Carregar a Views
		$this->load->view('layout/header', $data);
		$this->load->view('vendas/index');
		$this->load->view('layout/footer');
	}

	public function add()
	{

		$this->form_validation->set_rules('venda_cliente_id', '', 'required');
		$this->form_validation->set_rules('venda_tipo', '', 'required');
		$this->form_validation->set_rules('venda_forma_pagamento_id', '', 'required');
		$this->form_validation->set_rules('venda_vendedor_id', '', 'required');

		if ($this->form_validation->run()) {

			$venda_valor_total = str_replace('AKZ', "", trim($this->input->post('venda_valor_total')));

			$data = elements(
				array(
					'venda_cliente_id',
					'venda_forma_pagamento_id',
					'venda_tipo',
					'venda_vendedor_id',
					'venda_valor_desconto',
					'venda_valor_total',
				), $this->input->post()

			);

			$data['venda_valor_total'] = trim(preg_replace('/\$/', '', $venda_valor_total));

			$data = html_escape($data);

			//************* Salvando no banco de dado
			$this->core_model->insert('vendas', $data, TRUE); //Permite recuperar o último ID inserido.

			$id_venda = $this->session->userdata('last_id');


			$produto_id = $this->input->post('produto_id');
			$produto_quantidade = $this->input->post('produto_quantidade');
			$produto_desconto = str_replace('%', '', $this->input->post('produto_desconto'));

			$produto_preco_venda = str_replace('AKZ', '', $this->input->post('produto_preco_venda'));
			$produto_item_total = str_replace('AKZ', '', $this->input->post('produto_item_total'));

			$produto_preco = str_replace(',', '', $produto_preco_venda);
			$produto_item_total = str_replace(',', '', $produto_item_total);


			$qty_produto = count($produto_id);


			for ($i = 0; $i < $qty_produto; $i++) {

				$data = array(
					'venda_produto_id_venda' => $id_venda,
					'venda_produto_id_produto' => $produto_id[$i],
					'venda_produto_quantidade' => $produto_quantidade[$i],
					'venda_produto_valor_unitario' => $produto_preco_venda[$i],
					'venda_produto_desconto' => $produto_desconto[$i],
					'venda_produto_valor_total' => $produto_item_total[$i],
				);

				$data = html_escape($data);

				//Para cada dado do for vou inserir os meus dados
				$this->core_model->insert('venda_produtos', $data);

				/*Inicio atualização de estoque CASO QUEIRA ALTERAR UMA VENDA. */

				$produto_qtde_estoque = 0;

				$produto_qtde_estoque += intval($produto_quantidade[$i]);

				$produtos = array(
					'produto_qtde_estoque' => $produto_qtde_estoque,
				);

				$this->produtos_model->update($produto_id[$i], $produto_qtde_estoque);


				/*Fim atualização estoque*/


			}// Fim foreach

			redirect('vendas/imprimir/' . $id_venda); //comentamos porque ainda não existe o venda.
//       redirect('vendas');


//          echo '<pre>';
//          print_r($this->input->post());
//          exit();

		} else {

			$data = array(
				'titulo' => 'Cadastrar venda',
				'styles' => array(
					'vendor/select2/select2.min.css',
					'vendor/autocomplete/jquery-ui.css',
					'vendor/autocomplete/estilo.css',
				),

				'scripts' => array(
					'vendor/autocomplete/jquery-migrate.js', //Vem primeiro
					'vendor/calcx/jquery-calx-sample-2.2.8.js',
					'vendor/calcx/venda.js',
					'vendor/select2/select2.min.js',
					'vendor/select2/app.js',
					'vendor/sweetalert2/sweetalert2.js',
					'vendor/autocomplete/jquery-ui.js', //Vem último

				),
				'clientes' => $this->core_model->get_all('clientes', array('cliente_ativo' => 1)),

				'formas_pagamentos' => $this->core_model->get_all('formas_pagamentos', array('forma_pagamento_ativa' => 1)),

				'vendedores' => $this->core_model->get_all('vendedores', array('vendedor_ativo' => 1)),

			);


//          echo '<pre>';
//          print_r($data['os_tem_servicos']);
//          exit();

//          echo '<pre>';
//          print_r($venda_produtos);
//          exit();


			//*** Carregar a Views
			$this->load->view('layout/header', $data);
			$this->load->view('vendas/add');
			$this->load->view('layout/footer');
		}
	}

	public function edit($venda_id = NULL)
	{
//    Se ele foi passado
		if (!$venda_id || !$this->core_model->get_by_id('vendas', array('venda_id' => $venda_id))) {
			$this->session->set_flashdata('error', 'Vendas não encontrada');
			redirect('vendas');

		} else {

//          Atualização de estoque
//       $venda_produtos = $data['venda_produtos'] = $this->vendas_model->get_all_produtos_by_venda($venda_id); //será utilizado na quantidade de produtos
//CASO QUEIRA ALTERAR UMA VENDA

			$this->form_validation->set_rules('venda_cliente_id', '', 'required');
			$this->form_validation->set_rules('venda_tipo', '', 'required');
			$this->form_validation->set_rules('venda_forma_pagamento_id', '', 'required');
			$this->form_validation->set_rules('venda_vendedor_id', '', 'required');

			if ($this->form_validation->run()) {

				$venda_valor_total = str_replace('AKZ', "", trim($this->input->post('venda_valor_total')));

				$data = elements(
					array(
						'venda_cliente_id',
						'venda_forma_pagamento_id',
						'venda_tipo',
						'venda_vendedor_id',
						'venda_valor_desconto',
						'venda_valor_total',
					), $this->input->post()

				);

				$data['venda_valor_total'] = trim(preg_replace('/\$/', '', $venda_valor_total));

				$data = html_escape($data);

				//************* Salvando no banco de dado
				$this->core_model->update('vendas', $data, array('venda_id' => $venda_id));

				/*Deleteando da venda  os produtos antigos da venda editada*/
				$this->vendas_model->delete_old_products($venda_id);

				$produto_id = $this->input->post('produto_id');
				$produto_quantidade = $this->input->post('produto_quantidade');
				$produto_desconto = str_replace('%', '', $this->input->post('produto_desconto'));

				$produto_preco_venda = str_replace('AKZ', '', $this->input->post('produto_preco_venda'));
				$produto_item_total = str_replace('AKZ', '', $this->input->post('produto_item_total'));

				$produto_preco = str_replace(',', '', $produto_preco_venda);
				$produto_item_total = str_replace(',', '', $produto_item_total);


				$qty_produto = count($produto_id);


				for ($i = 0; $i < $qty_produto; $i++) {

					$data = array(
						'venda_produto_id_venda' => $venda_id,
						'venda_produto_id_produto' => $produto_id[$i],
						'venda_produto_quantidade' => $produto_quantidade[$i],
						'venda_produto_valor_unitario' => $produto_preco_venda[$i],
						'venda_produto_desconto' => $produto_desconto[$i],
						'venda_produto_valor_total' => $produto_item_total[$i],
					);

					$data = html_escape($data);

					//Para cada dado do for vou inserir os meus dados
					$this->core_model->insert('venda_produtos', $data);

					/*Inicio atualização estoque CASO QUEIRA ALTERAR UMA VENDA. */

//             foreach ($venda_produtos as $venda_p) {
//
//                if ($venda_p->venda_produto_quantidade < $produto_quantidade[$i]) {
//
//                   $produto_quantidade_estoque = 0;
//
//                   $produto_quantidade_estoque += intval($produto_quantidade[$i]);
//
//                   $diferenca = ($produto_quantidade_estoque - $venda_p->venda_produto_quantidade);
//
//                   $this->produtos_model->update($produto_id[$i], $diferenca);
//                }
//             }


					/*Fim atualização estoque*/


				}// Fim foreach


//          redirect('vendas/imprimir/' . $venda_id); comentamos porque ainda não existe o venda.
				redirect('vendas');


//          echo '<pre>';
//          print_r($this->input->post());
//          exit();

			} else {

				$data = array(
					'titulo' => 'Atualizar venda',
					'styles' => array(
						'vendor/select2/select2.min.css',
						'vendor/autocomplete/jquery-ui.css',
						'vendor/autocomplete/estilo.css',
					),

					'scripts' => array(
						'vendor/autocomplete/jquery-migrate.js', //Vem primeiro
						'vendor/calcx/jquery-calx-sample-2.2.8.js',
						'vendor/calcx/venda.js',
						'vendor/select2/select2.min.js',
						'vendor/select2/app.js',
						'vendor/sweetalert2/sweetalert2.js',
						'vendor/autocomplete/jquery-ui.js', //Vem último

					),
					'clientes' => $this->core_model->get_all('clientes', array('cliente_ativo' => 1)),

					'formas_pagamentos' => $this->core_model->get_all('formas_pagamentos', array('forma_pagamento_ativa' => 1)),

					'vendedores' => $this->core_model->get_all('vendedores', array('vendedor_ativo' => 1)),

					'venda' => $this->vendas_model->get_by_id($venda_id),

					'venda_produtos' => $this->vendas_model->get_all_produtos_by_venda($venda_id),

					'desabilitar' => TRUE,
				);


//          echo '<pre>';
//          print_r($data['os_tem_servicos']);
//          exit();

//          echo '<pre>';
//          print_r($venda_produtos);
//          exit();


				//*** Carregar a Views
				$this->load->view('layout/header', $data);
				$this->load->view('vendas/edit');
				$this->load->view('layout/footer');
			}
		}
	}

	public function del($venda_id = NULL)
	{
//    Se ele foi passado
		if (!$venda_id || !$this->core_model->get_by_id('vendas', array('venda_id' => $venda_id))) {
			$this->session->set_flashdata('error', 'Vendas não encontrada');
			redirect('vendas');

		} else {
			$this->core_model->delete('vendas', array('venda_id' => $venda_id));
			redirect('vendas');
		}

	}

	public function imprimir($venda_id = NULL)
	{
//    Se ele foi passado
		if (!$venda_id || !$this->core_model->get_by_id('vendas', array('venda_id' => $venda_id))) {
			$this->session->set_flashdata('error', 'Vendas não encontrada');
			redirect('vendas');

		} else {

			$data = array(
				'titulo' => 'Escolha uma opção',
				//Enviar dados da ordem
				'venda' => $this->core_model->get_by_id('vendas', array('venda_id' => $venda_id)),

			);
			//*** Carregar a Views
			$this->load->view('layout/header', $data);
			$this->load->view('vendas/imprimir');
			$this->load->view('layout/footer');
		}
	}

	public function pdf($venda_id = NULL)
	{
//    Se ele foi passado
		if (!$venda_id || !$this->core_model->get_by_id('vendas', array('venda_id' => $venda_id))) {
			$this->session->set_flashdata('error', 'venda não encontrada');
			redirect('vendas');
		} else {

			//Recuperar ou retornar as informações do sistema

			$empresa = $this->core_model->get_by_id('sistema', array('sistema_id' => 1));

//       echo '<pre>';
//       print_r($empresa);
//       exit();

			$venda = $this->vendas_model->get_by_id($venda_id);


//       echo '<pre>';
//       print_r($venda);
//       exit();

			$file_name = 'venda&nbsp;' . $venda->venda_id;

//       echo '<pre>';
//       print_r($file_name);
//       exit();

//       Agora vou começar a criar o meu HTML

			$html = '<html>';

			$html .= '<head>';

			$html .= '<title>' . $empresa->sistema_nome_fantasia . ' | Impressão de venda </title>'; //Pego o objeto empresa e pegando o nome do campo

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

//       Dados do cliente

			$html .= '<p align="right" style="font-size: 12px"> venda Nº&nbsp;' . $venda->venda_id . '</p>';

			$html .= '<p>'
				. '<strong>Cliente: </strong>' . $venda->cliente_nome_completo . '<br/>'
				. '<strong>Telefone: </strong>' . $venda->cliente_telefone . '<br/>'
				. '<strong>Data de emissão: </strong>' . formata_data_banco_com_hora($venda->venda_data_emissao) . '<br/>'
				. '<strong>Forma de pagamento: </strong>' . $venda->forma_pagamento . '<br/>';
			'</p>';

			$html .= '<hr>';

//       Dados da ordem
			$html .= '<table width="100%" border: solid #ddd 1px>';

			$html .= '<tr>';

			$html .= '<th>Código</th>';
			$html .= '<th>Descrição</th>';
			$html .= '<th>Quantidade</th>';
			$html .= '<th>Valor unitário</th>';
			$html .= '<th>Desconto</th>';
			$html .= '<th>Valor total</th>';

			$html .= '</tr>';

//       $venda_id = $venda->venda_id;

			$produtos_venda = $this->vendas_model->get_all_produtos($venda_id);

//       echo '<pre>';
//       print_r($servicos_ordem);
//       exit();

			$valor_final_venda = $this->vendas_model->get_valor_final_venda($venda_id);

//       echo '<pre>';
//       print_r($valor_final_os);
//       exit();

			foreach ($produtos_venda as $produto):

				$html .= '<tr>';

				$html .= '<td>' . $produto->venda_produto_id_produto . '</td>';
				$html .= '<td>' . $produto->produto_descricao . '</td>';
				$html .= '<td>' . $produto->venda_produto_quantidade . '</td>';
				$html .= '<td>' . 'AKZ&nbsp;' . $produto->venda_produto_valor_unitario . '</td>';
				$html .= '<td>' . '%&nbsp;' . $produto->venda_produto_desconto . '</td>';
				$html .= '<td>' . 'AKZ&nbsp;' . $produto->venda_produto_valor_total . '</td>';

				$html .= '</tr>';

			endforeach;

			$html .= '<th colspan="3">';

			$html .= '<td style="border-top: solid #ddd 1px"><strong>Valor final</strong></td>';
			$html .= '<td style="border-top: solid #ddd 1px">' . $valor_final_venda->venda_valor_total . '</td>'; //Peguei o valor final do método get_valor_final_os

			$html .= '</table>';

			$html .= '</body>';

			$html .= '</html>';

			echo '<pre>';
			print_r($html);
			exit();

			$this->pdf->createPDF($html, $file_name, false); //Quando colocamos o false é para abrir o PDF no browser e o TRUE faz o download

		}
	}
}
