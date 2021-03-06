<?php

defined('BASEPATH') OR exit('Ação não permitida');

class Produtos_model extends CI_Model
{
	public function get_all()
	{
		$this->db->select([
			'produtos.*',
			'categorias.categoria_id',
			'categorias.categoria_nome as produto_categoria',
			'marcas.marca_id',
			'marcas.marca_nome as produto_marca',
			'fornecedores.fornecedor_id',
			'fornecedores.fornecedor_nome_fantasia as produto_fornecedor',
		]); //Vou colocar em apenas uma instrução em array todas as tabelas que preciso.


		/*
		 * Agora vou fazer os meus JOIN:
		 */

		$this->db->join('categorias', 'categoria_id = produto_categoria_id', 'LEFT');
		$this->db->join('marcas', 'marca_id = produto_marca_id', 'LEFT');
		$this->db->join('fornecedores', 'fornecedor_id = produto_fornecedor_id', 'LEFT');

		return $this->db->get('produtos')->result();

		//A Clúsula LEFT trará todos os produtos no banco de dado ainda que não tenha uma categoria atribuida.

	}

	public function update($produto_id, $diferenca)
	{

		$this->db->set('produto_qtde_estoque', 'produto_qtde_estoque - ' .$diferenca, FALSE);

		$this->db->where('produto_id', $produto_id);

		$this->db->update('produtos');
	}
}
