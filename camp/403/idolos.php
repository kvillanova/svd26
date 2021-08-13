<?php
$area = addslashes($_POST['area']);

$vd->query("INSERT INTO {$vd->idolo}_c SELECT * FROM (SELECT 
'{$tkey}' tkey, 
MD5(CONCAT('RICKROLLED','{$tkey}',NOW(6),'NEVERGONNACHANGE')) ciclo, 
ADDTIME(NOW(6),'8:00:00') tempo 
) tmp
WHERE NOT EXISTS(
	SELECT * FROM {$vd->idolo}_c WHERE tkey='{$tkey}' AND tpost>=NOW(6)
);");

$vd->query("INSERT INTO {$vd->idolo}_a SELECT * FROM (SELECT
'{$tkey}' tkey,
(SELECT tribo FROM {$vd->keys_t_last} WHERE tkey='{$tkey}') tribo,
'{$area}' coords,
NOW(6) tpost,
(SELECT ciclo FROM {$vd->idolo}_c WHERE tkey='{$tkey}' ORDER BY tpost DESC LIMIT 1) ciclo
) tmp WHERE (SELECT COUNT(*) FROM {$vd->idolo}_a WHERE tkey='{$tkey}' AND ciclo=tmp.ciclo)<10 AND NOT EXISTS (
SELECT * FROM {$vd->idolo}_a WHERE DATE_ADD(tpost,INTERVAL 10 SECOND)>=NOW(6) AND tribo=(SELECT tribo FROM {$vd->keys_t_last} WHERE tkey='{$tkey}') AND coords='{$area}'
);");

if(!$vd->affected_rows) {
	$qvd = $vd->query("SELECT
	IF((SELECT COUNT(*) FROM {$vd->idolo}_a WHERE tkey='{$tkey}' AND ciclo=(SELECT ciclo FROM {$vd->idolo}_c WHERE tkey='{$tkey}' ORDER BY tpost DESC LIMIT 1))>=10,1,0) contagem,
	EXISTS(SELECT * FROM {$vd->idolo}_a WHERE DATE_ADD(tpost,INTERVAL 10 SECOND)>=NOW(6) AND tribo=(SELECT tribo FROM {$vd->keys_t_last} WHERE tkey='{$tkey}') AND coords='{$area}') visita;
	");
	$qvd = $qvd->fetch_assoc();
	if(+$qvd['visita']) rerro("Você acabou de visitar essa área (<strong>{$area}</strong>). Tente outra.","orange");
	if(+$qvd['contagem']) rerro("Sua quantidade de cliques esgotou. Aguarde um novo ciclo.","red",false);
}

$tribo = $vd->getTribe($tn,$tkey);
$json = json_decode(file_get_contents("403/idolos.json"),true);
$json = $json[$tribo];
$add = "++cliques;";

if($area===$json['idolo']) rerro("Parabéns, você achou as coordenadas do ídolo! Avise à Moderação.", "green");
if(in_array($area,array_merge($json['pistas'][0],$json['pistas'][1],$json['pistas'][2]))) rerro("Você encontrou uma pista para o ídolo. Acesse pelo menu.", "blue");

rerro("Buscas realizadas em <strong>{$area}</strong> com sucesso. Porém nada foi encontrado.","yellow",false);
?>