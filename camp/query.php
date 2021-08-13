 <?php
if(__FILE__ === $_SERVER['SCRIPT_FILENAME']) die;

$vd->createDefault($tn);
echo $vd->error;

$vd->query("CREATE TABLE IF NOT EXISTS {$vd->idolo}_c(
tkey VARCHAR(8) NOT NULL,
ciclo VARCHAR(32),
tpost DATETIME(6) DEFAULT NOW(6),
UNIQUE(ciclo),
PRIMARY KEY(tkey,tpost),
CONSTRAINT FOREIGN KEY(tkey) REFERENCES {$vd->keys}(tkey) ON UPDATE CASCADE ON DELETE CASCADE
) Engine=InnoDB CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;");

if($vd->error) die($vd->error);

$vd->query("CREATE TABLE IF NOT EXISTS {$vd->idolo}_a(
tkey VARCHAR(8) NOT NULL,
tribo VARCHAR(3) NOT NULL,
coords VARCHAR(5) NOT NULL,
tpost DATETIME(6) DEFAULT NOW(6),
ciclo VARCHAR(32),
PRIMARY KEY(tkey,tpost),
CONSTRAINT FOREIGN KEY(tkey) REFERENCES {$vd->keys}(tkey) ON UPDATE CASCADE ON DELETE CASCADE,
CONSTRAINT FOREIGN KEY(ciclo) REFERENCES {$vd->idolo}_c(ciclo) ON UPDATE CASCADE ON DELETE CASCADE
) Engine=InnoDB CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;");

if($vd->error) die($vd->error);

$vd->query("CREATE OR REPLACE VIEW {$vd->idolo}_v AS
SELECT k.nome,
t.tribo tribo,
t.coords coords,
t.tpost tstart,
DATE_ADD((SELECT tpost FROM {$vd->idolo}_a WHERE ciclo=t.ciclo ORDER BY tpost DESC LIMIT 1), INTERVAL (SELECT COUNT(*) FROM {$vd->idolo}_a WHERE ciclo=t.ciclo)*30*60 SECOND) tend,
t.ciclo ciclo
FROM {$vd->idolo}_a t INNER JOIN {$vd->keys} k ON k.tkey=t.tkey WHERE DATE_ADD((SELECT tpost FROM {$vd->idolo}_a WHERE ciclo=t.ciclo 
ORDER BY tpost DESC LIMIT 1), INTERVAL (SELECT COUNT(*) FROM {$vd->idolo}_a WHERE ciclo=t.ciclo)*30*60 SECOND)>=NOW(6) ORDER BY tstart DESC;
");

$qvd = $vd->query("CREATE OR REPLACE VIEW {$vd->idolo}_vall AS
SELECT * FROM {$vd->idolo}_v
UNION
SELECT nome,tribo,NULL,NULL,NULL,NULL FROM {$vd->keys_t_last} k WHERE NOT EXISTS(
	SELECT * FROM {$vd->idolo}_v WHERE nome=k.nome
);");

if($vd->error) die($vd->error);
?>