<?php 
define("NO_TN", true);
require_once  __DIR__ ."/../inc/db/header.php";
require_once  __DIR__ ."/chaves/tkeygen.php";

class KvL extends VDSQL {
	
	/*
	.
	FUNÇÕES DE CHAVES:
	.
	*/
	function inserirJogador($nome,$tribo,$genero='o',$tkey=false) {
		if(!$tkey) $tkey = genTkey();
		
		$genero = removeAccents($genero);
		$tribo = mb_strtoupper(removeAccents($tribo));
		
		if(!preg_match("%^[a-z]+$%uis",$nome)) return $this->textarea("<b>{$nome}</b> possui caracteres ilegais.");
		
		if($tribo==='MOD') $tstatus=3;
		else $tstatus=1;
		
		$this->query("INSERT INTO ".self::KEYS." VALUES ('{$nome}','{$genero}','{$tkey}',{$tstatus}) ON DUPLICATE KEY UPDATE nome=VALUES(nome),genero=VALUES(genero),tkey=VALUES(tkey),tstatus=VALUES(tstatus);");
		
		$this->query("INSERT INTO ".self::TRIBES." VALUES ('{$tkey}','{$tribo}',NOW(6));");
		
		if($this->error) return $this->textarea("<b>Erro:</b> ".$this->error);
		else return $this->textarea("<b>{$nome}</b> foi inserid{$genero} com sucesso.");
	}
	
	function removerJogador($nomes) {
		$this->textarea(NULL);
		foreach($nomes as $nome):
		$nome = trim($nome);
		$qvd = $this->query("SELECT * FROM ".self::KEYS." WHERE nome='{$nome}';");
		if($qvd->num_rows<1) {
			$this->textarea("<u>DELETE</u>: Jogador <b>".mb_strtoupper($nome)."</b>  não encontrado.",false);
			continue;
		}
		
		$oz = $qvd->fetch_assoc();
		$tstatus = +$oz['tstatus'];
		
		if($tstatus<4) {
			$this->textarea("<u>DELETE</u>: Jogador <b>".mb_strtoupper($nome)."</b> não marcado como eliminado.",false);
			continue;
		}
		
		$this->query("DELETE FROM ".self::KEYS." WHERE nome='{$nome}';");
		$this->textarea("<u>DELETE</u>: Jogador <b>".mb_strtoupper($nome)."</b> deletado com sucesso.",false);
		endforeach;
		return $this->textarea(NULL);
	}
	
	function mudarChave($pessoa,$chave) {
		if(!preg_match("%^([a-z0-9]{8})$%", $chave)) return $this->textarea("CHAVE INVÁLIDA!");
		$this->query("UPDATE ".self::KEYS." SET tkey='{$chave}' WHERE nome='{$pessoa}';");
		
		if($this->error) return $this->textarea($this->error);
		
		$this->textarea("Chave de {$pessoa} alterada para {$chave} com sucesso.");
	}
	
	function mudarGen($pessoa,$gen) {
		if(!preg_match("%^([a-z]{1})$%uis", $gen)) return $this->textarea("GÊNERO INVÁLIDO!");
		$this->query("UPDATE ".self::KEYS." SET genero='{$gen}' WHERE nome='{$pessoa}';");
		
		if($this->error) return $this->textarea($this->error);
		
		$this->textarea("Gênero de {$pessoa} alterada para '{$gen}' com sucesso.");
	}
	
	function mudarTribo($pessoa,$tribo) {
		if(!preg_match("%^([a-z]{3})$%i", $tribo)) return $this->textarea("TRIBO INVÁLIDA!");
		$tribo = mb_strtoupper($tribo);
		
		$this->query("INSERT INTO ".self::TRIBES." VALUES ((SELECT tkey FROM ".self::KEYS." WHERE nome='{$pessoa}'),'{$tribo}',NOW(6));");
		
		if($this->error) return $this->textarea($this->error);
		
		$this->textarea("Tribo de {$pessoa} alterada para {$tribo} com sucesso.");
	}
	
	function mostrarTribos() {
		$t = <<<LAT
		//						
.
.
NOMES DAS TRIBOS:
<span class="arq mini tribo">*</span>Anarquia (ARQ);
<span class="gal mini tribo">*</span>Gallo (GAL);
<span class="mos mini tribo">*</span>Mosca (MOSCA);
<span class="plu mini tribo">*</span>Pluma (PLUMA).
LAT;
		$this->textarea($t);
	}
	
	function comandosChaves() {
		$t = <<<LAT
		.
		.
		<u style="color:#ccc">COMANDOS</u>:
<b>#TRIBES:</b>
Tribo1:Participante1 [,Participante2...];
[Tribo2:Participante1;]
/*Muda as tribos dos participantes*/
--
<b>#KEYS:</b>
Participante:Chave;
[Participante2:Chave2;]
/*Muda as chaves dos participantes*/
--
<b>#INSERT:</b>
Participante;
[Participante2;]
/*Acrescenta participantes*/
--
<b>#DELETE:</b>
Participante;
[Participante2;]
/*Apaga chaves extras que tiverem marcadas como eliminados*/
--
<b>#NAMES:</b>
/*recebe nome e código das tribos*/	
LAT;
		$this->textarea($t);
	}
	
	/*
	.
	FUNÇÕES DE PROVAS:
	.
	*/
	function insertChallenge($id, $num=0, $rotulo=NULL, $nome=NULL, $tipo="imunidade") {
		$q = $this->query("INSERT INTO ".self::PROVAS."(id,num,rotulo,nome,tipo) 
		VALUES('{$id}',{$num},'{$rotulo}','{$nome}','{$tipo}');");	
	}
	
	function mudarChallenge($id, $num, $rotulo, $nome, $tipo) {
		$q = $this->query("SELECT * FROM ".self::PROVAS." WHERE id='{$id}';");
		if($q->num_rows<1) return $this->insertChallenge($id,$num,$rotulo,$nome);
		
		$sets = "";
		if(!empty($num) && mb_strtoupper($num!=='NULL')) $sets .= "num='{$num}',";
		if(!empty($rotulo) && mb_strtoupper($rotulo!=='NULL')) $sets .= "rotulo='{$rotulo}',";
		if(!empty($nome) && mb_strtoupper($nome!=='NULL')) $sets .= "nome='{$nome}',";
		if(!empty($tipo) && mb_strtoupper($tipo!=='NULL')) $sets .= "tipo='{$tipo}',";
		$sets = rtrim($sets, ",");
		
		if($sets==="") return $this->textarea("Você digitou nada!",false);
		
		$this->query("UPDATE ".self::PROVAS." SET {$sets} WHERE id='{$id}';");
	}
	
	function derrubarJogador($id, $nome) {
		if(!preg_match("%^".self::PREFIX."%",$id)) $id = self::PREFIX . $id;
		$this->query("DELETE FROM {$id} WHERE tkey=(SELECT tkey FROM ".self::KEYS." WHERE nome='{$nome}');");
		if($this->error) return 0;
	}
	
	function comandosProvas() {
		$t = <<<LAT
		.
		.
		<u style="color:#ccc">COMANDOS</u>:
<b>#CHALLENGE:</b>
ID:Número[:Rótulo][:Nome][:Tipo*]
/*Altera ou insere uma prova, caso não exista. Tipo pode ser imunidade, recompensa, mista, fogo, idolo, repescagem ou outra*/
<b>#DROP:</b>
ID:Nome;
/*Derruba um participante de determinada prova.*/
LAT;
		$this->textarea($t);
	}
	
	/*
	.
	FUNÇÕES DE SCORE:
	.
	*/
	
	function mudarScore($id, $placar, $desempate) {
		$q = $this->query("SELECT * FROM ".self::PROVAS." WHERE id='{$id}';");
		if($q->num_rows<1) return $this->textarea("Não existe prova com esse ID!");
		
		switch($placar):
		case 'pontos+': $placar = "acertos"; break;
		case 'pontos-': $placar = "erros"; break;
		case 'tempo-': $placar = "agilidade"; break;
		case 'tempo+': $placar = "resistencia"; break;
		default: $placar = NULL;
		endswitch;
		
		switch($desempate):
		case 'pontos+':
		case 'pontos-':
		case 'tempo-':
		case 'tempo+': $desempate = $desempate; break;
		default: $desempate = NULL; 
		endswitch;
		
		$sets = "";
		if(!empty($placar) && mb_strtoupper($placar!=='NULL')) $sets .= "placar='{$placar}',";
		if(!empty($desempate) && mb_strtoupper($desempate!=='NULL')) $sets .= "desempate='{$desempate}',";
		$sets = rtrim($sets, ",");
		
		if($sets==="") return $this->textarea("Você digitou nada!",false);
		
		$this->query("UPDATE ".self::PROVAS." SET {$sets} WHERE id='{$id}';");
	}
	
	function comandosScore() {
		$t = <<<L
		.
		.
		<u style="color:#ccc">COMANDOS</u>:
<b>#CHANGE:</b>
Prova:Criterio1[,Criterio2];
/*Ordena o score de acordo com os critérios. Valores possíveis para critérios: pontos+, pontos-, tempo+, tempo-. */	
L;
		$this->textarea($t);
	}
	
	function comandosMina() {
		$t = <<<LAT
		.
		.
		<u style="color:#ccc">COMANDOS</u>:
<b>#PICARETAS:</b>
Num1 Num2 Num3 Num4...;
/*Modifica a quantidade de picaretas dos 20 jogadores por ordem alfabética. */	
LAT;
		$this->textarea($t);
	}
	
	function comandosBanco() {
		$t = <<<LAT
		.
		.
		<u style="color:#ccc">COMANDOS</u>:
<b>#ADD:</b>
Jogador:Valor:Descrição[:Horário];
/*Adiciona uma ou mais entradas no VDBank. Horário opcional no formato YYYY-MM-DD HH:II:SS */
<b>#REMOVE:</b>
TID;
[TID2;]
/*Remove uma ou mais entradas no VDBank. Use os valores TID (transfer id#) para remover uma entrada. */
LAT;
		$this->textarea($t);
	}
	
	function comandosMercado() {
		$t = <<<L
		.
		.
		<u style="color:#ccc">COMANDOS</u>:
<b>#ITENS:</b>
ID:Nome:Descrição:Condição:Quantidade:On/Off;
/*Insere ou modifica itens na tabela do Mercadito. */	
L;
		$this->textarea($t);
	}
	
	
	/*
	.
	FUNÇÕES COMUNS:
	.
	*/
	function textarea($a,$q=true) {
	$a = $this->makeClean($a);
	if($q) $a = '<br/>-------<br/>' . $a;
	else $a = '<br/>' . $a;
	echo "<script>var kl = $('#kvl .log'); var v = kl.html(); kl.html(v+'{$a}'); kl.scrollTop(kl.prop('scrollHeight'));</script>";	
	}

	function textclear() {
	echo "<script>$('#kvl .log').html('>:CONSØLE/L0G');</script>";	
	}
	
	function makeClean($a) {
		$b = array("%\n%uis", "%^\s+%uis", "%\s{2,}%uis", "%'%");
		
		$c = array('<br/>', '', " ", "\'");
		
		$a = preg_replace($b,$c, $a);
		return $a;
	}
	
	function digiteHelp() {
	 	$t = "Digite <b>HELP</b> para acessar os comandos."	;
		$this->textarea($t);
	}
	
	function creditos() {
		$t = <<<L
KvL é uma linguagagem de script bem marota criada por Kevo especialmente para o site do Survivor VD. Ela tem acesso direito ao banco dados, então não saia mexendo sem ter a exata certeza do que está fazendo, senão um raio vai cair no servidor do site e vai arruinar o trabalho de uma vida toda. Digite <b>HELP</b> e dê enviar na caixinha para saber os comandos disponíveis para essa seção.		
L;
		$this->textarea($t);
	}
	
	
} //end class.
?>