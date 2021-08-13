<?php
class VDSQLSetup extends VDSQL {
	
	public function __construct($local = false) {
		if($local) $this->local();
		else $this->vd3();
		
		$this->createKeys();
		$this->createTribes();
		$this->createChallenges();
		$this->createLogs();
		$this->createCT();
		$this->createServices();
		$this->createBank();
		$this->createMarket();
		$this->createExiles();
		$this->createQuests();
	}
	
	public function createKeys() {		
		$this->query("CREATE TABLE IF NOT EXISTS ".self::KEYS."(
		nome VARCHAR(20) NOT NULL,
		nomef VARCHAR(50) NOT NULL,
		genero VARCHAR(1) NOT NULL DEFAULT 'o',
		tkey VARCHAR(8) NOT NULL,
		CONSTRAINT uk_nome UNIQUE(nome),
		CONSTRAINT pk_tkey PRIMARY KEY(tkey)
		) Engine=InnoDB CHARACTER SET utf8mb4
		COLLATE utf8mb4_general_ci;");
		if($this->error) die($this->error);
	}
	
		public function createTribes() {		
		$this->query("CREATE TABLE IF NOT EXISTS ".self::TRIBES."(
		tkey VARCHAR(8) NOT NULL,
		tribo VARCHAR(3) NOT NULL DEFAULT 'YYZ',
		tstatus TINYINT(1) NOT NULL DEFAULT 1,
		tpost DATETIME(6) DEFAULT NOW(6),
		PRIMARY KEY(tkey,tpost),
		CONSTRAINT FOREIGN KEY(tkey) REFERENCES ".self::KEYS."(tkey) ON UPDATE CASCADE ON DELETE CASCADE
		) Engine=InnoDB CHARACTER SET utf8mb4
		COLLATE utf8mb4_general_ci;");
		if($this->error) die($this->error);
			
		$this->query(
		"CREATE OR REPLACE VIEW ".self::KEYS_T." AS SELECT k.*,t.tribo,t.tstatus,t.tpost tribo_time FROM ".self::KEYS." k INNER JOIN ".self::TRIBES." t ON k.tkey=t.tkey;"
		);
			
		if($this->error) die($this->error);
		
		$this->query(
		"CREATE OR REPLACE VIEW ".self::KEYS_T_LAST." AS SELECT *, 
		(SELECT tribo FROM ".self::TRIBES." WHERE tkey=k.tkey ORDER BY tpost DESC LIMIT 1) tribo,
		(SELECT tstatus FROM ".self::TRIBES." WHERE tkey=k.tkey ORDER BY tpost DESC LIMIT 1) tstatus
		FROM ".self::KEYS." k;"
		);
			
		if($this->error) die($this->error);
	}
	
	public function createChallenges() {
		$this->query("CREATE TABLE IF NOT EXISTS ".self::PROVAS."(
		num tinyint(2) DEFAULT NULL,
		id varchar(80) NOT NULL,
		rotulo varchar(8) DEFAULT NULL,
		nome varchar(200) DEFAULT NULL,
		tipo enum('recompensa','imunidade','mista','repescagem','fogo','exilio','outra') DEFAULT 'imunidade',
		pstatus tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
		PRIMARY KEY(id)
		) Engine=InnoDB CHARACTER SET utf8mb4
		COLLATE utf8mb4_general_ci;");
		if($this->error) die($this->error);
	}
	
	public function createLogs() {
		$this->query("CREATE TABLE IF NOT EXISTS ".self::LOGS."(
		cook VARCHAR(30) NOT NULL,
		tkey VARCHAR(8) NOT NULL,
		id VARCHAR(80) NOT NULL,
		tpost DATETIME(6) NOT NULL,
		ip VARCHAR(20) NOT NULL,
		loc VARCHAR(100) DEFAULT NULL,
		city VARCHAR(100) DEFAULT NULL,
		region VARCHAR(100) DEFAULT NULL,
		country VARCHAR(10) DEFAULT NULL,
		org VARCHAR(100) DEFAULT NULL,
		ua VARCHAR(2000) NOT NULL,
		aok TINYINT(1) DEFAULT NULL,
		FOREIGN KEY(tkey) REFERENCES ".self::KEYS."(tkey) ON UPDATE CASCADE ON DELETE CASCADE
		) Engine=InnoDB CHARACTER SET utf8mb4
		COLLATE utf8mb4_general_ci;");
		if($this->error) die($this->error);
	}
	
	public function createCT() {
		$this->query("CREATE TABLE IF NOT EXISTS ".self::CT."_e(
		cook VARCHAR(30),
		tkey VARCHAR(8) NOT NULL,
		tref VARCHAR(12) NOT NULL,
		tstart DATETIME(6) DEFAULT NOW(6),
		tend DATETIME(6) DEFAULT NULL,
		CONSTRAINT PRIMARY KEY(tkey),
		CONSTRAINT FOREIGN KEY(tkey) REFERENCES ".self::KEYS."(tkey) ON UPDATE CASCADE ON DELETE CASCADE
		) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
		");
		
		$this->query("CREATE TABLE IF NOT EXISTS ".self::CT."_c(
		num FLOAT(4,2),
		tribo VARCHAR(3),
		closed TINYINT(1) DEFAULT 0,
		CONSTRAINT PRIMARY KEY(num,tribo)
		) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
		
		$this->query("CREATE TABLE IF NOT EXISTS ".self::CT."_a(
		tkey VARCHAR(8),
		num FLOAT(4,2),
		tribo VARCHAR(3),
		voto VARCHAR(20),
		justificativa VARCHAR(3000),
		tpost DATETIME(6),
		CONSTRAINT PRIMARY KEY(tkey,tpost),
		CONSTRAINT FOREIGN KEY(tkey) REFERENCES ".self::KEYS."(tkey) ON UPDATE CASCADE ON DELETE CASCADE,
		CONSTRAINT FOREIGN KEY(voto) REFERENCES ".self::KEYS."(nome) ON UPDATE CASCADE ON DELETE CASCADE
		) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
		
		$this->query("CREATE OR REPLACE VIEW ".self::CT." AS
		SELECT * FROM ".self::CT."_a a WHERE tpost=(SELECT MAX(tpost) FROM ".self::CT."_a WHERE tkey=a.tkey AND num=a.num AND tribo=a.tribo);");
		
		if($this->error) die($this->error);
	}
	
	public function createServices() {
		$this->query("CREATE TABLE IF NOT EXISTS {$this->services}(
		nome VARCHAR(80) NOT NULL,
		id VARCHAR(80) NOT NULL,
		open TINYINT(1) DEFAULT 1,
		PRIMARY KEY(id)
		) Engine=InnoDB CHARACTER SET utf8mb4
		COLLATE utf8mb4_general_ci;");
		
		$this->createDefault("{$this->services}_e");
		
		$this->query("CREATE TABLE IF NOT EXISTS {$this->vars_all}(
		varname VARCHAR(100) NOT NULL,
		valor VARCHAR(3000) DEFAULT NULL,
		ativa TINYINT(1),
		expira DATETIME(6) DEFAULT NULL,
		criada DATETIME(6),
		atualizada DATETIME(6),
		PRIMARY KEY(varname)
		) Engine=InnoDB CHARACTER SET utf8mb4
		COLLATE utf8mb4_general_ci;");
		
		if($this->error) die($this->error);
		
		$this->query(
		"CREATE OR REPLACE VIEW {$this->vars} AS SELECT * FROM {$this->vars_all} WHERE ativa=1 AND IF(expira IS NOT NULL,expira<=NOW(6),1);"
		);
	}
	
	public function createQuests() {
		$this->query("CREATE TABLE IF NOT EXISTS {$this->quests}(
		num VARCHAR(20) NOT NULL,
		tstart DATETIME(6) NOT NULL,
		tend DATETIME(6) NOT NULL,
		open TINYINT(1) DEFAULT 1,
		PRIMARY KEY(num)
		) Engine=InnoDB CHARACTER SET utf8mb4
		COLLATE utf8mb4_general_ci;");
		
		$this->query("CREATE TABLE IF NOT EXISTS {$this->quests}_s(
		tkey VARCHAR(8) NOT NULL,
		num VARCHAR(20) NOT NULL,
		perg INT(3),
		tpost DATETIME(6),
		resp LONGTEXT,
		PRIMARY KEY(tkey,num,perg),
		FOREIGN KEY(tkey) REFERENCES {$this->keys}(tkey) ON UPDATE CASCADE ON DELETE CASCADE,
		FOREIGN KEY(num) REFERENCES {$this->quests}(num) ON UPDATE CASCADE ON DELETE CASCADE
		) Engine=InnoDB CHARACTER SET utf8mb4
		COLLATE utf8mb4_general_ci;");
		
		$this->query("CREATE TABLE IF NOT EXISTS {$this->quests}_x(
		tkey VARCHAR(8) NOT NULL,
		num VARCHAR(20) NOT NULL,
		perg INT(3),
		tpost DATETIME(6),
		resp LONGTEXT,
		PRIMARY KEY(tkey,num,perg,tpost),
		FOREIGN KEY(tkey) REFERENCES {$this->keys}(tkey) ON UPDATE CASCADE ON DELETE CASCADE,
		FOREIGN KEY(num) REFERENCES {$this->quests}(num) ON UPDATE CASCADE ON DELETE CASCADE
		) Engine=InnoDB CHARACTER SET utf8mb4
		COLLATE utf8mb4_general_ci;");
		
		if($this->error) die($this->error);
		
		$this->query(
		"CREATE OR REPLACE VIEW {$this->vars} AS SELECT * FROM {$this->vars_all} WHERE ativa=1 AND IF(expira IS NOT NULL,expira<=NOW(6),1);"
		);
	}
	
	public function createBank() {
		$this->query("CREATE TABLE IF NOT EXISTS {$this->bank}(
		tkey VARCHAR(8) NOT NULL,
		tpost DATETIME(6) NOT NULL,
		valor DOUBLE(8,2) NOT NULL,
		tipo VARCHAR(3),
		descr VARCHAR(200) DEFAULT NULL,
		tid VARCHAR(40) DEFAULT NULL,
		PRIMARY KEY(tkey,tpost),
		FOREIGN KEY(tkey) REFERENCES {$this->keys}(tkey) ON UPDATE CASCADE ON DELETE CASCADE
		) Engine=InnoDB CHARACTER SET utf8mb4
		COLLATE utf8mb4_general_ci;");
		
		if($this->error) die($this->error);
	}
	
	public function createMarket() {
		$this->query("CREATE TABLE IF NOT EXISTS {$this->market}(
		pid VARCHAR(50) NOT NULL,
		nome VARCHAR(200) NOT NULL,
		descr VARCHAR(200) DEFAULT NULL,
		cond TINYINT(1),
		CONSTRAINT PRIMARY KEY(pid)
		) Engine=InnoDB CHARACTER SET utf8mb4
		COLLATE utf8mb4_general_ci;");
		
		if($this->error) die($this->error);
		
		$this->query("CREATE TABLE IF NOT EXISTS {$this->market}_p(
		pid VARCHAR(50) NOT NULL,
		valor DOUBLE(8,2) NOT NULL,
		tpost DATETIME(6),
		CONSTRAINT PRIMARY KEY(pid,tpost),
		CONSTRAINT FOREIGN KEY(pid) REFERENCES {$this->market}(pid)
		) Engine=InnoDB CHARACTER SET utf8mb4
		COLLATE utf8mb4_general_ci;");
		
		$this->query("CREATE TABLE IF NOT EXISTS {$this->market}_c(
		tkey VARCHAR(8) NOT NULL,
		rodada TINYINT(2),
		tpost DATETIME(6),
		pid VARCHAR(50),
		CONSTRAINT PRIMARY KEY(tkey,tpost,pid),
		CONSTRAINT FOREIGN KEY(tkey) REFERENCES {$this->keys}(tkey) ON UPDATE CASCADE ON DELETE CASCADE,
		CONSTRAINT FOREIGN KEY(pid) REFERENCES {$this->market}(pid)
		) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
		");
		
		if($this->error) die($this->error);
	}
	
	public function createExiles() {
		$this->query("CREATE TABLE IF NOT EXISTS ".self::EXILIOS."(
		id VARCHAR(50) NOT NULL,
		open TINYINT(1) DEFAULT 1,
		descr VARCHAR(200) DEFAULT NULL,
		CONSTRAINT PRIMARY KEY(id)
		) Engine=InnoDB CHARACTER SET utf8mb4
		COLLATE utf8mb4_general_ci;");
		
		if($this->error) die($this->error);
		
		$this->query("CREATE TABLE IF NOT EXISTS ".self::EXILIOS."_p(
		id VARCHAR(50),
		tkey VARCHAR(8),
		tpost DATETIME(6),
		palpite VARCHAR(2000),
		CONSTRAINT PRIMARY KEY(id,tpost,tkey),
		CONSTRAINT FOREIGN KEY(id) REFERENCES ".self::EXILIOS."(id),
		CONSTRAINT FOREIGN KEY(tkey) REFERENCES ".self::KEYS."(tkey)
		) Engine=InnoDB CHARACTER SET utf8mb4
		COLLATE utf8mb4_general_ci;");
		
		if($this->error) die($this->error);
	}
}
?>