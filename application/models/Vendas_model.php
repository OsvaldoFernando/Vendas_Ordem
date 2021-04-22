<?php

defined('BASEPATH') or exit('Ação não permitida');

class Vendas_model extends CI_Model
{
	public function get_all()
	{
		$this->db->select([
			'vendas.*',
			'clientes.cliente_id',
			'clientes.cliente_nome',
			'CONCAT (clientes.cliente_nome, " ", clientes.cliente_sobrenome) as cliente_nome_completo',
			'vendedores.vendedor_id',
			'vendedores.vendedor_nome_completo',
			'formas_pagamentos.forma_pagamento_id',
			'formas_pagamentos.forma_pagamento_nome as forma_pagamento',
		]); //Vou colocar em apenas uma instrução em array todas as tabelas que preciso.


		/*
		 * Agora vou fazer os meus JOIN:
		 */

		$this->db->join('clientes', 'cliente_id = venda_cliente_id', 'LEFT');
		$this->db->join('vendedores', 'vendedor_id = venda_vendedor_id', 'LEFT');
		$this->db->join('formas_pagamentos', 'forma_pagamento_id = venda_forma_pagamento_id', 'LEFT');

		return $this->db->get('vendas')->result();

		//A Clúsula LEFT trará todos os produtos no banco de dado ainda que não tenha uma categoria atribuida.

	}

	public function get_by_id($venda_id = NULL)
	{
		$this->db->select([
			'vendas.*',
			'clientes.cliente_id',
			'CONCAT (clientes.cliente_nome, " ", clientes.cliente_sobrenome) as cliente_nome_completo',
			'vendedores.vendedor_id',
			'vendedores.vendedor_nome_completo',
			'formas_pagamentos.forma_pagamento_id',
			'formas_pagamentos.forma_pagamento_nome as forma_pagamento',
		]); //Vou colocar em apenas uma instrução em array todas as tabelas que preciso.


		/*
		 * Agora vou fazer os meus JOIN:
		 */

		$this->db->where('clientes', 'cliente_id = venda_cliente_id', 'LEFT'); //Nome da coluna e o parâmetro
		$this->db->join('vendedores', 'vendedor_id = venda_vendedor_id', 'LEFT');
		$this->db->join('formas_pagamentos', 'forma_pagamento_id = venda_forma_pagamento_id', 'LEFT');

		return $this->db->get('vendas')->row();

		//A Clúsula LEFT trará todos os produtos no banco de dado ainda que não tenha uma categoria atribuida.

	}

	public function get_all_produtos_by_venda($venda_id = NULL)
	{
		if ($venda_id) { //Verificar se foi passado ordem de serviço façamos a consulta no banco

			$this->db->select([
				'venda_produtos.*', //Lá de ordem vou pegar apenas serviços
				'produtos.produto_descricao',
			]);

			$this->db->join('produtos', 'produto_id = venda_produto_id_produto', 'LEFT');

			$this->db->where('venda_produto_id_venda', $venda_id);

			return $this->db->get('venda_produtos')->result();
		}
	}


	public function delete_old_products($ordem_servico_id = NULL) //delete_old_services
	{
		if ($ordem_servico_id) { //Verificar se foi passado ordem de serviço façamos a consulta no banco

			$this->db->delete('ordem_tem_servicos', array('ordem_ts_id_ordem_servico' => $ordem_servico_id));

		}
	}

	public function get_all_servicos($ordem_servico_id = NULL)
	{

		if ($ordem_servico_id) {

			$this->db->select([
				'ordem_tem_servicos.*',
				'FORMAT(SUM(REPLACE(ordem_ts_valor_unitario, ",", "")), 2) as ordem_ts_valor_unitario',
				'FORMAT(SUM(REPLACE(ordem_ts_valor_total, ",", "")), 2) as ordem_ts_valor_total',
				'servicos.servico_id',
				'servicos.servico_nome',
			]);

			$this->db->join('servicos', 'servico_id = ordem_ts_id_servico', 'LEFT');
			$this->db->where('ordem_ts_id_ordem_servico', $ordem_servico_id);

			$this->db->group_by('ordem_ts_id_servico');

			return $this->db->get('ordem_tem_servicos')->result();
		}
	}

	public function get_valor_final_os($ordem_servico_id = NULL)
	{
		if ($ordem_servico_id) {

			$this->db->select([
				'FORMAT(SUM(REPLACE(ordem_ts_valor_total, ",", "")), 2) as os_valor_total',
			]);

			$this->db->join('servicos', 'servico_id = ordem_ts_id_servico', 'LEFT');
			$this->db->where('ordem_ts_id_ordem_servico', $ordem_servico_id);
		}

		return $this->db->get('ordem_tem_servicos')->row();
	}
}
