<?php

defined('BASEPATH') or exit('Ação não permitida');

class Ordem_servicos_model extends CI_Model
{
	public function get_all()
	{
		$this->db->select([
			'ordens_servicos.*',
			'clientes.cliente_id',
			'clientes.cliente_nome',
			'formas_pagamentos.forma_pagamento_id',
			'formas_pagamentos.forma_pagamento_nome as forma_pagamento',
		]); //Vou colocar em apenas uma instrução em array todas as tabelas que preciso.


		/*
		 * Agora vou fazer os meus JOIN:
		 */

		$this->db->join('clientes', 'cliente_id = ordem_servico_cliente_id', 'LEFT');
		$this->db->join('formas_pagamentos', 'forma_pagamento_id = ordem_servico_forma_pagamento_id', 'LEFT');

		return $this->db->get('ordens_servicos')->result();

		//A Clúsula LEFT trará todos os produtos no banco de dado ainda que não tenha uma categoria atribuida.

	}

	public function get_all_servicos_by_ordem($ordem_servico_id = NULL)
	{
		if ($ordem_servico_id) { //Verificar se foi passado ordem de serviço façamos a consulta no banco

			$this->db->select([
				'ordem_tem_servicos.*', //Lá de ordem vou pegar apenas serviços
				'servicos.servico_descricao',
			]);

			$this->db->join('servicos', 'servico_id = ordem_ts_id_servico', 'LEFT');

			$this->db->where('ordem_ts_id_ordem_servico', $ordem_servico_id);

			return $this->db->get('ordem_tem_servicos')->result();
		}
	}

	public function delete_old_services($ordem_servico_id = NULL)
	{
		if ($ordem_servico_id) { //Verificar se foi passado ordem de serviço façamos a consulta no banco

			$this->db->delete('ordem_tem_servicos', array('ordem_ts_id_ordem_servico' => $ordem_servico_id));

		}
	}

	public function get_by_id($ordem_servico_id = NULL)
	{
		$this->db->select([
			'ordens_servicos.*',
			'clientes.cliente_id',
			'clientes.cliente_telefone',
			'CONCAT (clientes.cliente_nome, " ", clientes.cliente_sobrenome) as cliente_nome_completo',
//			'clientes.cliente_nome',
			'formas_pagamentos.forma_pagamento_id',
			'formas_pagamentos.forma_pagamento_nome as forma_pagamento',
		]); //Vou colocar em apenas uma instrução em array todas as tabelas que preciso.


		/*
		 * Agora vou fazer os meus JOIN:
		 */

		$this->db->where('ordem_servico_id', $ordem_servico_id); //Nome da coluna e o parâmetro
		$this->db->join('clientes', 'cliente_id = ordem_servico_cliente_id', 'LEFT');
		$this->db->join('formas_pagamentos', 'forma_pagamento_id = ordem_servico_forma_pagamento_id', 'LEFT');

		return $this->db->get('ordens_servicos')->row();

		//A Clúsula LEFT trará todos os produtos no banco de dado ainda que não tenha uma categoria atribuida.

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
