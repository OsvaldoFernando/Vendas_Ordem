<?php
defined('BASEPATH') OR exit('Ação não permitida');

class Core_model extends CI_Model
{

	//*** Permite recuperar todas informações
	public function get_all($tabela = NULL, $condicao = NULL)
	{
		//***Verificar a tabela
		if ($tabela) {

			//*** Se é um array
			if (is_array($condicao)) {

				//***Conectar a base de dado
				$this->db->where($condicao);
			}

			//*** Retornar
			return $this->db->get($tabela)->result();
		}
		//*** Caso não seja passada nenhuma tabela
		else {
			return FALSE;
		}
	}

	public function get_by_id($tabela = NULL, $condicao = NULL)
	{
		//*** Se a tabela for passada e é array
		if ($tabela && is_array($condicao)) {

			//*** Vai conectar a base de dado
			$this->db->where($condicao);

			//*** Limitar apena uma linha
			$this->db->limit(1);

			//*** Pegar a informação na base de dado
			return $this->db->get($tabela)->row();
		}
		//*** Se não retorna false
		else {
			return FALSE;
		}
	}

	//*** FUNÇÃO PARA INSERIR INFORMAÇÃO AO BANCO DE DADO
	public function insert($tabela = NULL, $data = NULL, $get_last_id = NULL)
	{

		//*** Verificar se a tabela for passada e data é umm array insira os dados
		if ($tabela && is_array($data)) {

			//*** Inserir os dados
			$this->db->insert($tabela, $data);

			//*** Caso o seja verdadeiro (true)
			if ($get_last_id) {

				//*** Na sessão do usuário cria uma coluna ou campo chamado lasta_id com o valor do último id que foi inserido no banco de dado
				$this->session->set_userdata('last_id', $this->db->insert_id());
			}

			//*** Verificação se o número de linha afetada for maior que zero
			if ($this->db->affected_rows() > 0) {

				//*** Mensagem (mensagem sucesso com o seguinte valor dados salvos com sucesso
				$this->session->set_flashdata('sucesso', 'Dados salvos com sucessos');

			}
			//*** Caso haja um erro a nível de banco de dado
			else {

				$this->session->set_flashdata('error', 'Erro ao salvar dados');
			}


		} else {

		}
	}

	//*** FUNÇÃO PARA ATUALIZAR
	public function update($tabela = NULL, $data = NULL, $condicao = NULL)
	{
		//***
		if ($tabela && is_array($data) && is_array($condicao)) {

			if ($this->db->update($tabela, $data, $condicao)) {

				$this->session->set_flashdata('sucesso', 'Dados salvos com sucessos');
			}else {

				$this->session->set_flashdata('error', 'Erro ao atualizar os dados');
			}

			//*** Se a condição a cima não for atendida retorna falso
		} else {
			return FALSE;
		}
	}

	//*** FUNÇÃO DE DELETAR
	public function delete($tabela = NULL, $condicao = NULL)
	{
		//*** Desabilitar db do codeigniter
		$this->db->db_debug = FALSE;

		if ($tabela && is_array($condicao)) {

			$status = $this->db->delete($tabela, $condicao);

			$error = $this->db->error();

			if (!$status) {

				foreach ($error as $code) {
					if ($code == 1451) {

						$this->session->set_flashdata('error', 'Esse registros não poderá ser excluido, pós esta sendo utilizado em outra tabela');
					}
				}
			} else {
				
				//*** se não houve erro ocorreu a deleção
				$this->session->set_flashdata('sucesso', 'Registro excluido com sucesso');
			}

			//*** habilitar o debug
			$this->db->db_debug = TRUE;

		} else {
			//*** Se a condição a cima não for atendida retorna false
			return FALSE;
		}
	}

	/**
	 * @ Habilitar helper string
	 * @param string $table
	 * @param string $type_of_code. Ex.: 'numeric', 'alpha', 'alnum', 'basic', 'numeric', 'nozero', 'md5', 'sha1'
	 * @param int $size_of_code
	 * @param string $field_seach
	 * @return int
	 */
	public function generate_unique_code($table = NULL, $type_of_code = NULL, $size_of_code, $field_search) {

		do {
			$code = random_string($type_of_code, $size_of_code);
			$this->db->where($field_search, $code);
			$this->db->from($table);
		} while ($this->db->count_all_results() >= 1);

		return $code;
	}

	public function auto_complete_produtos($busca = NULL)
	{
		if ($busca) { //Se foi passado consulta no banco

			$this->db->like('produto_descricao', $busca, 'both');
			$this->db->where('produto_ativo', 1); //Produto a ser retornado deve ser ativo
			$this->db->where('produto_qtde_estoque >', 0); //Produto a ser retornado deve ser ativo e cuja quantidade em estoque seja maior que zero
			return $this->db->get('produtos')->result();

		} else { //Se não foi passado o term no busca

			return FALSE;
		}
	}

	public function auto_complete_servicos($busca = NULL)
	{
		if ($busca) { //Se foi passado consulta no banco

			$this->db->like('servico_descricao', $busca, 'both');
			$this->db->where('servico_ativo', 1); //Produto a ser retornado deve ser ativo
			return $this->db->get('servicos')->result();

		} else { //Se não foi passado o term no busca

			return FALSE;
		}
	}


}
