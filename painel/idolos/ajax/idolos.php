<?php
const NO_TN=true;
include "../../../inc/db/header.php";

$json = json_decode(file_get_contents("../../../camp/403/idolos.json"),true);
?>
<h3>Fusão</h3>
<?php
$qvd = $vd->query("SELECT nome, 
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['FUS']['idolo']}' AND tribo='FUS' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) horario
FROM {$vd->keys_t_last} k HAVING horario IS NOT NULL ORDER BY horario ASC;");
if($qvd->num_rows):
?>
<div class="idolos">
<div class="header"><p>Nome</p></div>
<div class="header"><p>Horário</p></div>
<?php while($oz=$qvd->fetch_assoc()): ?>
<div><?=$oz['nome'];?></div>
<div><?=(new DateTime($oz['horario'],$dtz))->format("d/m/Y - H:i:s");?></div>
<?php endwhile; ?>
</div>
<?php else: ?>
<p>O ídolo ainda não foi encontrado.</p>
<?php endif; ?>
<h3>Azevedo</h3>
<?php
$qvd = $vd->query("SELECT nome, 
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['AZV']['idolo']}' AND tribo='AZV' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) horario
FROM {$vd->keys_t_last} k HAVING horario IS NOT NULL ORDER BY horario ASC;");
if($qvd->num_rows):
?>
<div class="idolos">
<div class="header"><p>Nome</p></div>
<div class="header"><p>Horário</p></div>
<?php while($oz=$qvd->fetch_assoc()): ?>
<div><?=$oz['nome'];?></div>
<div><?=(new DateTime($oz['horario'],$dtz))->format("d/m/Y - H:i:s");?></div>
<?php endwhile; ?>
</div>
<?php else: ?>
<p>O ídolo ainda não foi encontrado.</p>
<?php endif; ?>
<h3>D'Ávila</h3>
<?php
$qvd = $vd->query("SELECT nome, 
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['DAV']['idolo']}' AND tribo='DAV' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) horario
FROM {$vd->keys_t_last} k HAVING horario IS NOT NULL ORDER BY horario ASC;");
if($qvd->num_rows):
?>
<div class="idolos">
<div class="header"><p>Nome</p></div>
<div class="header"><p>Horário</p></div>
<?php while($oz=$qvd->fetch_assoc()): ?>
<div><?=$oz['nome'];?></div>
<div><?=(new DateTime($oz['horario'],$dtz))->format("d/m/Y - H:i:s");?></div>
<?php endwhile; ?>
</div>
<?php else: ?>
<p>O ídolo ainda não foi encontrado.</p>
<?php endif; ?>
<h3>Villanova</h3>
<?php
$qvd = $vd->query("SELECT nome, 
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['VLL']['idolo']}' AND tribo='VLL' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) horario
FROM {$vd->keys_t_last} k HAVING horario IS NOT NULL ORDER BY horario ASC;");
if($qvd->num_rows):
?>
<div class="idolos">
<div class="header"><p>Nome</p></div>
<div class="header"><p>Horário</p></div>
<?php while($oz=$qvd->fetch_assoc()): ?>
<div><?=$oz['nome'];?></div>
<div><?=(new DateTime($oz['horario'],$dtz))->format("d/m/Y - H:i:s");?></div>
<?php endwhile; ?>
<?php else: ?>
<p>O ídolo ainda não foi encontrado.</p>
<?php endif; ?>
</div>