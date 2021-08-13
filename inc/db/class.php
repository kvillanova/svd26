<?php
class VDSQL extends MySQLi {
	
	const 
	SEASON = 26,
	PREFIX = self::SEASON . "_",
	KEYS = self::PREFIX . "\$\$keys",
	TRIBES = self::PREFIX . "\$\$tribes",
	
	KEYS_T = self::PREFIX . "\$keys_t",
	KEYS_T_LAST = self::PREFIX . "\$keys_t_last",
	
	LOGS = self::PREFIX . "\$logs",
	PROVAS = self::PREFIX . "\$provas",
	CT = self::PREFIX . "\$ct",
	QUESTS = self::PREFIX . "\$quests",
	CAMP = self::PREFIX . "\$camp",
	RESP = self::PREFIX . "\$resp",
	SERVICES = self::PREFIX . "\$services",
	VARS = self::PREFIX . "\$vars",
	VARS_ALL = self::PREFIX . "\$vars_all",
	
	BANK = self::PREFIX . "\$bank",
	IDOLO = self::PREFIX . "\$idolo",
	MARKET = self::PREFIX . "\$mercado",
	EXILIOS = self::PREFIX . "\$exilios";
	
	const
	DIR_ROOT = "/../",
	DIR_ROOT_LOC = "/../" . self::SEASON . "/",
	
	DIR_ROOT_FRANQ = "/../",
	DIR_ROOT_FRANQLOC = "/../" . self::SEASON . "/",
	
	FRANQUIA = "Survivor VD",
	TEMPORADA = "Ilha de Páscoa",
	TEMPORADA_SHORT = "Páscoa",
	SHORT = "VD".self::SEASON,
	
	SITE = "http://".self::SEASON.".svd.cc/",
	FAVICON = self::SITE."inc/styles/favicon.ico",
	IMG = self::SITE."inc/styles/site.jpg?v2";
	
	public
	$prefix = self::PREFIX,
	$keys = self::KEYS,
	$keys_t = self::KEYS_T,
	$keys_t_last = self::KEYS_T_LAST,
	$tribes = self::TRIBES,
	$logs = self::LOGS,
	$provas = self::PROVAS,
	$ct = self::CT,
	$services = self::SERVICES,
	$vars = self::VARS,
	$vars_all = self::VARS_ALL,
	$bank = self::BANK,
	$idolo = self::IDOLO,
	$market = self::MARKET,
	$camp = self::CAMP,
	$resp = self::RESP,
	$quests = self::QUESTS,
	$address,
	$address_f;
	
	//CONNECTIONS:
	
	public function __construct($local = false) {
		if($local) $this->local();
		else $this->vd3();
		$this->set_charset("utf8mb4");
	}
	
	public function __destruct() {
		$this->close();
	}
	
	function local() {
		$this->connect('localhost', 'root', '', 'vd');
		if($this->connect_error) {
			echo "Não foi possível se conectar ao banco de dados. Aguarde.";
			die;
		}
		$this->address = self::DIR_ROOT_LOC;
		$this->address_f = self::DIR_ROOT_FRANQLOC;
	}
	
	function vd3() {
		$link = @$this->connect('svdtest.mysql.dbaas.com.br', 'svdtest', 'testing!vd21', 'svdtest');
		if($this->connect_error) {
			echo "Não foi possível se conectar ao banco de dados. Aguarde.";
			die;
		}
		$this->address = self::DIR_ROOT;
		$this->address_f = self::DIR_ROOT_FRANQ;
	}
	
	/* FUNÇÕES DE EXÍLIOS */
	
	function getExiled($exile) {
		$q = $vd->query("SELECT * FROM ".self::EXILIOS." e INNER JOIN {$this->keystables} k ON e.tkey=k.tkey WHERE exile='{$exile}';");
		$q = $q->fetch_assoc();
		return $q['tkey'];	
	}
	
	/* FUNÇÕES DE PROVAS */
	
	function createTN($tn,$info=array(),$locked=false,$aux=true,$alt=false) {
		$this->createDefault($tn);
		$this->createPublic($tn);
		if($aux) {
			$this->createAux($tn,$locked);
			$this->createAuxP($tn,$locked);
		}
		if($alt) {
			$this->createAlternativa($tn,false);
		}
		$this->PutTNInfo($tn,$info);
	}
	
	function createDefault($tn) {
		$this->query("CREATE TABLE IF NOT EXISTS {$tn} (
		cook VARCHAR(30) NOT NULL,
		tkey VARCHAR(8),
		tref VARCHAR(12) NOT NULL,
		tstart DATETIME(6) DEFAULT NOW(6),
		CONSTRAINT PRIMARY KEY(tkey),
		CONSTRAINT UNIQUE(cook),
		CONSTRAINT UNIQUE(tref),
		CONSTRAINT FOREIGN KEY(tkey) REFERENCES ".self::KEYS."(tkey) ON UPDATE CASCADE ON DELETE CASCADE
		) Engine=InnoDB CHARACTER SET utf8mb4
      COLLATE utf8mb4_general_ci;");
		if($this->error) die("100: ".$this->error);
	}
	
	function createPublic($tn) {
		$this->query("CREATE TABLE IF NOT EXISTS {$tn}_p (
		cook VARCHAR(150),
		atual DOUBLE(5,3) DEFAULT 0,
		score INT(5) DEFAULT 0,
		tstart DATETIME(6),
		CONSTRAINT PRIMARY KEY(cook)
		) Engine=InnoDB CHARACTER SET utf8mb4
      COLLATE utf8mb4_general_ci;");
		if($this->error) die("100/p: ".$this->error);
	}
	
	function createAux($tn,$locked=true) {
		if($locked) $pk = "CONSTRAINT PRIMARY KEY(tkey,atual)";
		else $pk = "CONSTRAINT PRIMARY KEY(tkey,atual,tpost)";
		
		$this->query("CREATE TABLE IF NOT EXISTS {$tn}_x (
		tkey VARCHAR(8),
		atual DOUBLE(5,3) DEFAULT 0,
		tpost DATETIME(6) DEFAULT NOW(6),
		score INT(5) DEFAULT 0,
		resp TEXT DEFAULT NULL,
		{$pk},
		CONSTRAINT FOREIGN KEY(tkey) REFERENCES ".self::KEYS."(tkey) ON UPDATE CASCADE ON DELETE CASCADE
		) Engine=InnoDB CHARACTER SET utf8mb4
      COLLATE utf8mb4_general_ci;");
		if($this->error) die("100/x: ".$this->error);
	}

	function createAlternativa($tn,$locked=false) {
		if($locked) $pk = "CONSTRAINT PRIMARY KEY(tkey,atual)";
		else $pk = "CONSTRAINT PRIMARY KEY(tkey,atual,tpost)";
		
		$this->query("CREATE TABLE IF NOT EXISTS {$tn}_a (
		tkey VARCHAR(8),
		atual DOUBLE(5,3) DEFAULT 0,
		tpost DATETIME(6) DEFAULT NOW(6),
		score INT(5) DEFAULT 0,
		resp TEXT DEFAULT NULL,
		{$pk},
		CONSTRAINT FOREIGN KEY(tkey) REFERENCES ".self::KEYS."(tkey) ON UPDATE CASCADE ON DELETE CASCADE
		) Engine=InnoDB CHARACTER SET utf8mb4
      COLLATE utf8mb4_general_ci;");
		if($this->error) die("100/x: ".$this->error);
	}
	
	function createAuxP($tn,$locked=true) {
		if($locked) $pk = "CONSTRAINT PRIMARY KEY(tkey,atual)";
		else $pk = "CONSTRAINT PRIMARY KEY(tkey,atual,tpost)";
		
		$this->query("CREATE TABLE IF NOT EXISTS {$tn}_p_x (
		tkey VARCHAR(150),
		atual DOUBLE(5,3) DEFAULT 0,
		tpost DATETIME(6) DEFAULT NOW(6),
		score INT(5) DEFAULT 0,
		resp VARCHAR(5000) DEFAULT NULL,
		{$pk},
		CONSTRAINT FOREIGN KEY(tkey) REFERENCES {$tn}_p(cook) ON UPDATE CASCADE ON DELETE CASCADE
		) Engine=InnoDB CHARACTER SET utf8mb4
      COLLATE utf8mb4_general_ci;");
		if($this->error) die("100/px: ".$this->error);
	}
	
	function PutTNInfo($tn,$info) {
		$id = "'" . preg_replace("%^".self::PREFIX."%", "", $tn) . "'";
		$num = isset($info[0]) ? intval($info[0]) : "NULL";
		$rotulo = isset($info[1]) ? "'".$info[1]."'" : "NULL";
		$nome = isset($info[2]) ? "'".$info[2]."'" : "NULL";
		$tipo = isset($info[3]) ? "'".$info[3]."'" : "'imunidade'";
		$pstatus = isset($info[6]) ? intval($info[6]) : ($_SERVER['HTTP_HOST']==='localhost' ? 1 : 0);
		
		$p = preg_replace("%^".self::PREFIX."%", "", $tn);
		$o = $this->query("SELECT * FROM ".self::PROVAS." WHERE id='{$p}';");
		if($o->num_rows<1)
			$this->query("INSERT INTO ".self::PROVAS." VALUES ($num,$id,$rotulo,$nome,$tipo,$pstatus);");	
	}
	
	function getChallengeStatus($id) {
		$q = $this->query("SELECT * FROM {$id}");
		if($this->error) return -1;
		
		if(preg_match("%^".self::PREFIX."%", $id)) $id = preg_replace("%^".self::PREFIX."%", '', $id);
		$q = $this->query("SELECT pstatus FROM ".self::PROVAS." WHERE id='{$id}';");
		
		if($q->num_rows<1) return -1;
		
		$qvd = $q->fetch_assoc();
		return (int) $qvd['pstatus'];
	}
	
	function setChallengeStatus($id,$status) {
		if(preg_match("%^".self::PREFIX."%", $id)) $id = preg_replace("%^".self::PREFIX."%", '', $id);
		$q = $this->query("UPDATE ".self::PROVAS." SET pstatus={$status} WHERE id='{$id}';");
	}
	
	//FUNÇõES GET & UPDATE (JOINED)
	
	function select($tn, $selection, $field) {
		if(strlen($field)===8) $fieldname = "tkey";
		elseif(strlen($field)===12) $fieldname = "tref";
		else $fieldname = "cook";
		$qvd = $this->query("SELECT {$selection} FROM {$tn} WHERE {$fieldname}='{$field}';");
		$qvd = $qvd->fetch_assoc();
		return $qvd[$selection];	
	}
	
	function getNome($tn, $tkey) {
		if(preg_match("%_p$%uis", $tn)) return NULL;
		if(strlen($tkey)!==8) $tkey = $this->getTkey($tn,$tkey);
		
		$qvd = $this->query("SELECT nome FROM ".self::KEYS." WHERE tkey='{$tkey}';");
		$qvd = $qvd->fetch_assoc();
		return $qvd['nome'];
	}
	
	function getGen($tn, $tkey) {
		if(preg_match("%_p$%uis", $tn)) return 'o/a';
		if(strlen($tkey)!==8) $tkey = $this->getTkey($tn,$tkey);
		
		$qvd = $this->query("SELECT genero FROM ".self::KEYS." WHERE tkey='{$tkey}';");
		$qvd = $qvd->fetch_assoc();
		return $qvd['genero'];
	}
	
	function getTribe($tn, $tkey, $tempo=NULL) {
		if(preg_match("%_p$%uis", $tn)) return NULL;
		
		if(strlen($tkey)!==8) $tkey = $this->getTkey($tn,$tkey);
		
		if(!$tempo) {
			$qvd = $this->query("SELECT tribo FROM ".self::KEYS_T_LAST." WHERE tkey='{$tkey}';");
			if($this->error) die("150/1: ".$this->error);
			$qvd = $qvd->fetch_assoc();
			return $qvd['tribo'];
		}
		else {
			$qvd = $this->query("SELECT tribo FROM ".self::KEYS_T."
			WHERE tpost=(SELECT MAX(tpost) FROM ".self::KEYS_T." WHERE tkey='{$tkey}' AND tpost<='{$tempo}') AND tkey='{$tkey}';");
			if($this->error) die("150/2: ".$this->error);
			$qvd = $qvd->fetch_assoc();
			return $qvd['tribo'];
		}
		
	}
	
	function getTkey($tn, $field) {
		if(preg_match('%[.:][0-9]%uis', $field)) $fieldname="ip";
		elseif(strlen($field)===12) $fieldname = "tref";
		else $fieldname = "cook";
		
		if(preg_match("%_p(_.+)?$%uis",$tn) && $fieldname==='cook') return $field;
		
		$qvd = $this->query("SELECT tkey FROM {$tn} WHERE {$fieldname}='{$field}';");
		if($this->error) die("130: ".$this->error);
		if(!$qvd->num_rows) return false;
		
		$qvd = $qvd->fetch_assoc();
		return $qvd['tkey'];
	}
	
	function getTRef($tn, $field) {
		if(strlen($field)===8) $fieldname = "tkey";
		elseif(preg_match('%[.:][0-9]%uis', $field)) $fieldname="ip";
		else $fieldname = "cook";
		$qvd = $this->query("SELECT tref FROM {$tn} WHERE {$fieldname}='{$field}';");
		$qvd = $qvd->fetch_assoc();
		return $qvd['tref'];
	}
	
	function getAtual($tn,$tkey) {
		if(strlen($tkey)!==8) $tkey = $this->getTkey($tn,$tkey);
		
		$qvd = $this->query("SELECT atual FROM {$tn}_x WHERE tkey='{$tkey}' ORDER BY atual DESC;");
		if($qvd->num_rows===0) return -1;
		
		return $qvd->fetch_assoc()['atual'];
	}
	
	function getScore($tn,$field,$sum=false) {
		if(strlen($field)===8) $fieldname = "tkey";
		elseif(preg_match('%[.:][0-9]%uis', $field)) $fieldname="ip";
		else $fieldname = "cook";
		
		if($fieldname!=='tkey') $tkey = $this->getTkey($tn,$field);
		else $tkey = $field;
		
		if(preg_match("%_p(_.+)?$%uis",$tn)) $fieldname = "cook";
		else $fieldname = "tkey";
		
		if($sum) $qvd = $this->query("SELECT SUM(score) pontos FROM {$tn} WHERE {$fieldname}='{$tkey}';");
		else $qvd = $this->query("SELECT score pontos FROM {$tn} WHERE {$fieldname}='{$tkey}';");
		 
		$qvd = $qvd->fetch_assoc();
		return (int) $qvd['pontos'];
	}
	
	function getTpost($tn,$tkey,$atual=0,$first='DESC') {
		
		if(strlen($tkey)!==8) $tkey = $this->getTkey($tn,$tkey);
				
		$qvd = $this->query("SELECT tpost FROM {$tn}_x WHERE tkey='{$tkey}' AND atual=$atual ORDER BY -tpost {$first};");
		if($qvd->num_rows===0) return false;
		return $qvd->fetch_assoc()['tpost'];
	}
	
	function getTstart($tn,$field,$atual=1,$first='DESC') {
		return $this->getTpost($tn,$field,$atual,$first);
	}
	
	function getTend($tn,$field,$atual=2,$first='ASC') {
		return $this->getTpost($tn,$field,$atual,$first);
	}
	
	function getResult($tn,$field) {
		if(strlen($field)===8) $fieldname = "tkey";
		elseif(strlen($field)===12) $fieldname = "tref";
		elseif(preg_match('%[.:][0-9]%uis', $field)) $fieldname="ip";
		else $fieldname = "cook";
		$qvd = $this->query("SELECT result FROM {$tn} WHERE {$fieldname}='{$field}';");
		if($qvd->num_rows<1) return false;
		$qvd = $qvd->fetch_assoc();
		return $qvd['result'];
	}
	
	function getTribemates($tn, $tribo, $tstart=NULL, $conds=NULL) {
		$where = "WHERE tribo='{$tribo}'";
		if($tstart) $where .= " AND tstart<='{$tstart}'";
		if($conds) $where .= " {$conds}";
		$qvd = $this->query("SELECT nome FROM {$tn} t INNER JOIN ".self::KEYS_T_LAST." k ON t.tkey=k.tkey {$where};");
		return $qvd->num_rows;
	}
	
	function update($tn, $column, $value, $field) {
		if(strlen($field)===8) $fieldname = "tkey";
		else $fieldname = "cook";
		$this->query("UPDATE {$tn} SET {$column}='{$value}' WHERE {$fieldname}='{$field}';");
	}
	
	function updateAtual($tn, $field,$inc=1) {
		if(strlen($field)===8) $fieldname = "tkey";
		else $fieldname = "cook";
		$atual = (float) $this->getAtual($tn, $field);
		$next = bcadd($atual,$inc,3);

		$this->query("UPDATE {$tn} SET atual={$next} WHERE {$fieldname}='{$field}';");
	}
	
	function updateScore($tn,$acertos,$field,$settend=false) {
		if(strlen($field)===8) $fieldname = "tkey";
		else $fieldname = "cook";
		$this->query("UPDATE {$tn} SET score={$acertos} WHERE {$fieldname}='{$field}';");
		if($settend) $this->updateTend($tn,$field);	
	}
	
	function updateTstart($tn,$field,$d=false) {
		$this->updateTime($tn,"tstart",$field,$d);
	}
	
	function updateTend($tn,$field,$d=false) {
		$this->updateTime($tn,"tend",$field,$d);
	}
	
	function updateTime($tn,$column,$field,$d=false) {
		if(!$d) $d = $this->maketime();
		if(strlen($field)===8) $fieldname = "tkey";
		else $fieldname = "cook";
		$this->query("UPDATE {$tn} SET {$column}='{$d}' WHERE {$fieldname}='{$field}';");
	}
	
	function clearTime($tn,$column,$field) {
		if(!$d) $d = $this->maketime();
		if(strlen($field)===8) $fieldname = "tkey";
		else $fieldname = "cook";
		$this->query("UPDATE {$tn} SET {$column}=NULL WHERE {$fieldname}='{$field}';");
	}
	
	function clearTend($tn,$field) {
		$this->clearTime($tn,"tend",$field);
	}
	
	function updateResult($tn,$result,$field) {
		if(strlen($field)===8) $fieldname = "tkey";
		else $fieldname = "cook";
		$this->query("UPDATE {$tn} SET result='{$result}' WHERE {$fieldname}='{$field}';");
	}
	
	function zerarAtual($tn, $field) {
		if(strlen($field)===8) $fieldname = "tkey";
		else $fieldname = "cook";
		$this->query("UPDATE {$tn} SET atual=1 WHERE {$fieldname}='{$field}';");
	}
	
	function zerarModerador($tn,$modtkey) {
		$this->query("DELETE t,x FROM {$tn} t INNER JOIN {$tn}_x x ON x.tkey=t.tkey WHERE t.tkey='{$modtkey}';");
	}
	
	/* FUNÇÕES DE CHECK/CADASTRO DE TABELAS */
	
	function checkCook($cook, $tn, $statuses) {
		$q = $this->query("SELECT * FROM {$tn} t INNER JOIN ".self::KEYS_T_LAST." k ON t.tkey=k.tkey WHERE cook='{$cook}' AND tstatus IN({$statuses});");
		if($this->error) { die("3002: Erro de execução<br/>Avise ao programador"); }
		return $q->num_rows; 
	}
	function checkCookPublic($cook,$tn) {
		$q = $this->query("SELECT * FROM {$tn} WHERE cook='{$cook}';");
		if($this->error) { die("3002/p: Erro de execução<br/>Avise ao programador"); }
		return $q->num_rows; 
	}
	
	function checkIP($ip,$cook,$tn) {
		$q = $this->query("SELECT * FROM ".self::LOGS." WHERE ip='{$ip}' AND cook='{$cook}' AND id='{$tn}';");
		if($this->error) { die("3003: Erro de execução<br/>Avise ao programador"); }
		return $q->num_rows;
	}
	
	function checkIPExile($ip, $tn, $elim=false) {
		if($elim) $a = "0,1,2,3,4"; else $a = "0,1,2,3";
		$q = $this->query("SELECT * FROM {$tn} t INNER JOIN ".self::KEYS_T_LAST." k ON t.tkey=k.tkey WHERE ip='{$ip}' AND tstatus IN({$a});");
		if($this->error) { die("9000: Erro de execução<br/>Avise ao programador"); }
		return $q->num_rows; 
	}

	function checkUA($cook,$tn) {
		$ua = $_SERVER['HTTP_USER_AGENT'];
		$q = $this->query("SELECT * FROM ".self::LOGS." WHERE ua='{$ua}' AND cook='{$cook}' AND id='{$tn}';");
		if($this->error) { die("3004: Erro de execução<br/>Avise ao programador"); }
		return $q->num_rows;
	}
	
	function selectExile($code, $tkey) {
		if($code===$tkey) {
			$q = $this->query("SELECT * FROM ".self::KEYS_T_LAST." WHERE tkey='{$code}' AND tstatus IN (0,1);");
		}
		else {
			$q = $this->query("SELECT * FROM ".self::KEYS_T_LAST." WHERE tkey='{$code}' AND tstatus=3;");
		}
		return (bool) $q->num_rows;
	}
	
	function inStatues($tkey,$statuses,$not=false) {
		if($not) $x="NOT IN";
		else $x="IN";
		
		$q = $this->query("SELECT * FROM ".self::KEYS_T_LAST." WHERE tkey='{$tkey}' AND tstatus {$x}($statuses);");
		return (bool) $q->num_rows;
	}
	
	function onlyMods($tkey) {
		return $this->inStatues($tkey,'2,3');
	}
	
	function offlineKey($tkey,$statuses) {
		return $this->inStatues($tkey,$statuses,true);
	}
	
	function insertKey($cook, $ip, $tkey, $tn) {
		$this->insertLog($cook,$ip,$tn,$tkey);
		
		$d = $this->maketime();
		$ref = substr(md5($tn.$tkey.self::PREFIX),0,12);
		$q = $this->query("INSERT INTO {$tn} (cook,tkey,tref,tstart) VALUES ('{$cook}','{$tkey}','{$ref}','{$d}');");
		
		if($this->error) {
			#echo $this->error;
			if(preg_match("%.*Duplicate entry.*%", $this->error)) return 1;
			elseif(preg_match("%foreign key constraint fails%", $this->error)) return 2;
			else die("4002: Há algum erro aqui, informe ao programador");
			
		}
		$this->AOKLog($cook);
		return 0;
	}
	
	function insertKeyPublic($cook,$tn) {
		$d = $this->maketime();
		$q = $this->query("INSERT INTO {$tn} (cook,tstart) VALUES ('{$cook}','{$d}');");
		if($this->error)
		die("4002/p: Há algum erro aqui, informe ao programador");
	}
	
	function insertLog($cook,$ip,$tn,$tkey=NULL) {
		if($_SERVER['HTTP_HOST']!=="localhost" and $_SERVER['HTTP_HOST']!=='127.0.0.1' and $_SERVER['HTTP_HOST']!=='192.168.0.117') $locale = false;
		else $locale = true;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if($locale) curl_setopt($ch, CURLOPT_URL, "http://ipinfo.io/json");
		else curl_setopt($ch, CURLOPT_URL, "http://ipinfo.io/{$ip}/json");
		#curl_setopt($ch, CURLOPT_POST, TRUE);   //is it optional?
    	#curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    	$str = curl_exec($ch);
    	curl_close($ch);
		
		$k = json_decode($str);
		
		if(!$tkey) $tkey = $this->getTkey($tn,$cook);
		
		$ua = $_SERVER['HTTP_USER_AGENT'];
		$q = $this->query("INSERT INTO ".self::LOGS." VALUES ('$cook','{$tkey}','$tn',NOW(6),'$ip','{$k->loc}','{$k->city}','{$k->region}','{$k->country}','{$k->org}','$ua',0);");
	}
	
	function AOKLog($cook) {
		$q = $this->query("UPDATE ".self::LOGS." SET aok=1 WHERE cook='{$cook}';");
	}
	
	function insertKeyExile($ip, $tkey, $tn) {
		$d = $this->maketime();
		$ref = substr(md5($d.$tn.$tkey),0,12);
		$q = $this->query("INSERT INTO {$tn} (ip,tkey,tref,tstart) VALUES ('{$ip}','{$tkey}','{$ref}','{$d}');");
		
		if($this->error) {
			#echo $this->error;
			if(preg_match("%.*Duplicate entry.*tkey.*%", $this->error)) return 1;
			elseif(preg_match("%foreign key constraint fails%", $this->error)) return 2;
			else die("5000: Há algum erro aqui, informe ao programador");
		}
		else return 0;
	}
	
	function maketime() {
		$u = microtime();
		$u = explode(" ", $u);
		$u = preg_replace("%.*\.([0-9]+)%", '$1', $u[0]);
		
		$d = new DateTime("now", new DateTimeZone("America/Sao_Paulo"));
		$data = $d->format("Y-m-d H:i:s");
		return $data . ".$u";
	}
	
} //end class
?>