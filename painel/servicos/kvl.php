<?php 
require_once "../kvl.php";

class KvLServicos extends KvL {
	
	function inserirServico($id,$nome,$open=0,$vars=NULL) {
		if(!preg_match("%^[a-z0-9_]+$%uis",$id)) return $this->textarea("<b>{$nome}</b> possui caracteres ilegais.");
		
		$this->query("INSERT INTO {$this->services} VALUES ('{$nome}','{$id}',{$open});");
		if(preg_match("%duplicate%uis",$this->error)) return $this->textarea("O serviço <b>{$nome}</b> já está cadastrado.");
		
		if($this->error) return $this->textarea("<b>Erro:</b> ".$this->error);
		else return $this->textarea("O serviço <b>{$nome}</b> foi inserido com sucesso.");
	}
	
	function removerServicos($nomes) {
		$this->textarea(NULL);
		foreach($nomes as $nome):
		$nome = trim($nome);
		$qvd = $this->query("SELECT * FROM {$this->services} WHERE id='{$nome}';");
		if($qvd->num_rows<1) {
			$this->textarea("<u>DELETE</u>: Serviço <b>".mb_strtoupper($nome)."</b> não encontrado.",false);
			continue;
		}
		else {
			$this->query("DELETE FROM {$this->services} WHERE id='{$nome}';");
			$this->textarea("<u>DELETE</u>: Serviço <b>".mb_strtoupper($nome)."</b> deletado com sucesso.",false);
		}
		endforeach;
		return $this->textarea(NULL);
	}
	
	function inserirVars($vars) {
		foreach($vars as $vv):
		
		$vv = array_map(function($x){
			return trim($x);
		},$vv);
		
		list($varname,$valor) = $vv;
		$valor= empty($valor) ? "NULL" : "'{$valor}'";
		$ativa = isset($vv[2]) ? intval($vv[2]) : 1;
		$expira = isset($vv[3]) ? "'{$vv[3]}'" : "NULL";
		
		$this->query("INSERT INTO {$this->vars_all} VALUES ('{$varname}',{$valor},{$ativa},{$expira},NOW(6),NOW(6)) ON DUPLICATE KEY UPDATE
		valor=VALUES(valor),ativa=VALUES(ativa),expira=VALUES(expira),atualizada=NOW(6);");
		if($this->error) return $this->textarea($this->error);
		$this->textarea("Variável <strong>{$varname}</strong> foi adicionada ou atualizada com sucesso.");
		endforeach;
	}
	
	function unsetVars($vars) {
		foreach($vars as $vv):
		
		$vv = array_map(function($x){
			return trim($x);
		},$vv);
		
		list($varname) = $vv;
		$this->query("DELETE FROM {$this->vars_all} WHERE varname='{$varname}';");
		if($this->error) return $this->textarea($this->error);
		$this->textarea("Variável <strong>{$varname}</strong> foi excluída com sucesso.");
		endforeach;
	}
	
	function inserirVariavel($servico,$nome,$open=0,$vars=NULL) {
		if(!preg_match("%^[a-z0-9_]+$%uis",$id)) return $this->textarea("<b>{$nome}</b> possui caracteres ilegais.");
		
		$this->query("INSERT INTO {$this->services} VALUES ('{$nome}','{$id}',{$open},'{$vars}');");
		if(preg_match("%duplicate%uis",$this->error)) return $this->textarea("O serviço <b>{$nome}</b> já está cadastrado.");
		
		if($this->error) return $this->textarea("<b>Erro:</b> ".$this->error);
		else return $this->textarea("O serviço <b>{$nome}</b> foi inserido com sucesso.");
	}
	
	function comandos() {
//		$this->textarea("<u>INSERIR:</u>");
//		$this->textarea("NOME(1,20) TRIBO(3) GEN(1) [CHAVE(8)] /* chave é opcional. */",false);
//		$this->textarea("<u>MODIFICAR:</u>");
//		$this->textarea("NOME(1,20) TRIBO(3) /* mudar tribo */",false);
//		$this->textarea("NOME(1,20) CHAVE(8) /* mudar chave */",false);
//		$this->textarea("NOME(1,20) GÊNERO(1) /* mudar gênero */",false);
//		$this->textarea("<u>DELETAR:</u>");
//		$this->textarea("/* <b>--AVISO:</b> Não delete a menos que saiba o que está fazendo. Não delete jogadores reais, apenas usuários de teste, pois apagará todos os dados gerados pelo jogador no site. */",false);
//		$this->textarea("<b>DELETE</b> NOME(1,20) [NOME2(1,20),...] /* Pode colocar quantos nomes quiser após a palavra delete. Os usuários devem estar com as chaves marcadas como eliminados. */",false);
	}
	
} //end class.
?>