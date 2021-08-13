<?php 
require_once "../kvl.php";

class KvLKeys extends KvL {
	
	function inserirJogador($nome,$tribo,$genero='o',$tkey=false) {
		if(!$tkey) $tkey = genTkey();
		
		$genero = removeAccents($genero);
		$tribo = mb_strtoupper(removeAccents($tribo));
		
		if(!preg_match("%^[a-z0-9]+$%uis",$nome)) return $this->textarea("<b>{$nome}</b> possui caracteres ilegais.");
		
		if($tribo==='MOD') $tstatus=3;
		else $tstatus=1;
		
		$this->query("INSERT INTO {$this->keys} VALUES ('{$nome}','{$nome}','{$genero}','{$tkey}');");
		if(preg_match("%duplicate%uis",$this->error)) return $this->textarea("<b>{$nome}</b> já está cadastrado.");
		
		$this->query("INSERT INTO {$this->tribes} VALUES ('{$tkey}','{$tribo}',{$tstatus},NOW(6));");
		
		if($this->error) return $this->textarea("<b>Erro:</b> ".$this->error);
		else return $this->textarea("<b>{$nome}</b> foi inserid{$genero} com sucesso.");
	}
	
	function removerJogador($nomes) {
		$this->textarea(NULL);
		foreach($nomes as $nome):
		$nome = trim($nome);
		$qvd = $this->query("SELECT * FROM {$this->keys_t_last} WHERE nome='{$nome}';");
		if($qvd->num_rows<1) {
			$this->textarea("<u>DELETE</u>: Jogador <b>".mb_strtoupper($nome)."</b> não encontrado.",false);
			continue;
		}
		
		$oz = $qvd->fetch_assoc();
		$tstatus = +$oz['tstatus'];
		
		if($tstatus<4) {
			$this->textarea("<u>DELETE</u>: Jogador <b>".mb_strtoupper($nome)."</b> não marcado como eliminado.",false);
			continue;
		}
		
		$this->query("DELETE FROM {$this->keys} WHERE nome='{$nome}';");
		$this->textarea("<u>DELETE</u>: Jogador <b>".mb_strtoupper($nome)."</b> deletado com sucesso.",false);
		endforeach;
		return $this->textarea(NULL);
	}
	
	function quitterJogador($nomes) {
		$this->textarea(NULL);
		foreach($nomes as $nome):
		$nome = trim($nome);
		$qvd = $this->query("SELECT * FROM {$this->keys_t_last} WHERE nome='{$nome}';");
		if($qvd->num_rows<1) {
			$this->textarea("<u>QUITTER</u>: Jogador <b>".mb_strtoupper($nome)."</b> não encontrado.",false);
			continue;
		}
		
		$tribo = $qvd->fetch_assoc()['tribo'];
		$tkey = $qvd->fetch_assoc()['tkey'];
		
		$this->query("INSERT INTO {$vd->tribes} VALUES ('{$nome}','{$tribo}','{$tstatus}',NOW(6));");
		$this->textarea("<u>QUITTER</u>: Jogador <b>".mb_strtoupper($nome)."</b> marcado como desistente com sucesso.",false);
		endforeach;
		return $this->textarea(NULL);
	}
	
	function mudarChave($pessoa,$chave) {
		if(!preg_match("%^([a-z0-9]{8})$%", $chave)) return $this->textarea("CHAVE INVÁLIDA!");
		$this->query("UPDATE {$this->keys} SET tkey='{$chave}' WHERE nome='{$pessoa}';");
		
		if($this->error) return $this->textarea($this->error);
		
		$this->textarea("Chave de {$pessoa} alterada para {$chave} com sucesso.");
	}
	
	function mudarGen($pessoa,$gen) {
		if(!preg_match("%^([a-z]{1})$%uis", $gen)) return $this->textarea("GÊNERO INVÁLIDO!");
		$this->query("UPDATE {$this->keys} SET genero='{$gen}' WHERE nome='{$pessoa}';");
		
		if($this->error) return $this->textarea($this->error);
		
		$this->textarea("Gênero de {$pessoa} alterada para '{$gen}' com sucesso.");
	}
	
	function mudarTstatus($pessoa,$tstatus) {
		if(!preg_match("%^([0-9]{1})$%uis", $tstatus)) return $this->textarea("STATUS INVÁLIDO!");
		$this->query("INSERT INTO {$this->tribes} 
		SELECT tkey,tribo,{$tstatus},NOW(6) FROM {$this->keys_t_last} WHERE tkey=(SELECT tkey FROM {$this->keys} WHERE nome='{$pessoa}');");
		
		if($this->error) return $this->textarea($this->error);
		
		$this->textarea("Status de {$pessoa} alterado para '{$tstatus}' com sucesso.");
	}
	
	function mudarTribo($pessoa,$tribo) {
		if(!preg_match("%^([a-z]{3})$%i", $tribo)) return $this->textarea("TRIBO INVÁLIDA!");
		$tribo = mb_strtoupper($tribo);
		
		$this->query("INSERT INTO {$this->tribes} 
		SELECT tkey,'{$tribo}',tstatus,NOW(6) FROM {$this->keys_t_last} WHERE tkey=(SELECT tkey FROM {$this->keys} WHERE nome='{$pessoa}');");
		
		if($this->error) return $this->textarea($this->error);
		
		$this->textarea("Tribo de {$pessoa} alterada para {$tribo} com sucesso.");
	}
	
	function desligar($tkey,$modera=false) {
		if($modera) $o = 2; else $o = 0;
		$this->query("INSERT INTO {$this->tribes}
		SELECT tkey,tribo,{$o},NOW(6) FROM {$this->keys_t_last} WHERE tkey='{$tkey}';");
		
		$qvd = $this->query("SELECT DISTINCT(id) tn FROM {$this->logs} WHERE tkey='{$tkey}';");
		while($oz = $qvd->fetch_assoc()) {
			$this->query("DELETE FROM {$oz['tn']} WHERE tkey='{$tkey}';");
		}
		if($this->error) return $this->textarea($this->error);
	}
	
	function ligar($tkey,$modera=false) {
		if($modera) $o = 3; else $o = 1;
		$this->query("INSERT INTO {$this->tribes}
		SELECT tkey,tribo,{$o},NOW(6) FROM {$this->keys_t_last} WHERE tkey='{$tkey}';");
		if($this->error) return $this->textarea($this->error);
	}
	
	function eliminar($tkey) {
		$this->query("INSERT INTO {$this->tribes}
		SELECT tkey,tribo,4,NOW(6) FROM {$this->keys_t_last} WHERE tkey='{$tkey}';");
		if($this->error) return $this->textarea($this->error);
	}
	
	function ligarTodos($modera=false) {
		if($modera) { $o = 3; $n = 2; } else { $o = 1; $n = 0; }
		$this->query("INSERT INTO {$this->tribes}
		SELECT tkey,tribo,{$o},NOW(6) FROM {$this->keys_t_last} WHERE tstatus={$n};");
	}
	
	function desligarTodos($modera=false) {
		if($modera) { $o = 2; $n = 3; } else { $o = 0; $n = 1; }
		$this->query("INSERT INTO {$this->tribes}
		SELECT tkey,tribo,{$o},NOW(6) FROM {$this->keys_t_last} WHERE tstatus={$n};");
	}
	
	function comandos() {
		$this->textarea("<u>INSERIR:</u>");
		$this->textarea("NOME(1,20) TRIBO(3) GEN(1) [CHAVE(8)] /* chave é opcional. */",false);
		$this->textarea("<u>MODIFICAR:</u>");
		$this->textarea("NOME(1,20) TRIBO(3) /* mudar tribo */",false);
		$this->textarea("NOME(1,20) CHAVE(8) /* mudar chave */",false);
		$this->textarea("NOME(1,20) GÊNERO(1) /* mudar gênero */",false);
		$this->textarea("<u>DELETAR:</u>");
		$this->textarea("/* <b>--AVISO:</b> Não delete a menos que saiba o que está fazendo. Não delete jogadores reais, apenas usuários de teste, pois apagará todos os dados gerados pelo jogador no site. */",false);
		$this->textarea("<b>DELETE</b> NOME(1,20) [NOME2(1,20),...] /* Pode colocar quantos nomes quiser após a palavra delete. Os usuários devem estar com as chaves marcadas como eliminados. */",false);
	}	
	
} //end class.
?>