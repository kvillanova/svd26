<?php
const NO_TN=true;
include "../../../inc/db/header.php";

$json = json_decode(file_get_contents("../../../camp/403/idolos.json"),true);

?>
<h3>Fusão</h3>
<?php
$qvd = $vd->query("SELECT nome, 
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['FUS']['pistas'][0][0]}' AND tribo='FUS' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista1a,
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['FUS']['pistas'][0][1]}' AND tribo='FUS' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista1b,
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['FUS']['pistas'][0][2]}' AND tribo='FUS' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista1c,

(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['FUS']['pistas'][1][0]}' AND tribo='FUS' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista2a,
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['FUS']['pistas'][1][1]}' AND tribo='FUS' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista2b,
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['FUS']['pistas'][1][2]}' AND tribo='FUS' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista2c,

(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['FUS']['pistas'][2][0]}' AND tribo='FUS' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista3a,
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['FUS']['pistas'][2][1]}' AND tribo='FUS' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista3b,
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['FUS']['pistas'][2][2]}' AND tribo='FUS' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista3c
FROM {$vd->keys_t_last} k HAVING 
pista1a IS NOT NULL OR 
pista1b IS NOT NULL OR 
pista1c IS NOT NULL OR 
pista2a IS NOT NULL OR 
pista2b IS NOT NULL OR 
pista2c IS NOT NULL OR 
pista3a IS NOT NULL OR 
pista3b IS NOT NULL OR
pista3c IS NOT NULL
ORDER BY nome ASC;");
if($qvd->num_rows):
?>
<div class="pistas merge">
<div class="header"><p>Nome</p></div>
<div class="header" style="grid-column: span 3"><p>Pista 1</p></div>
<div class="header" style="grid-column: span 3"><p>Pista 2</p></div>
<div class="header" style="grid-column: span 3"><p>Pista 3</p></div>
<?php while($oz=$qvd->fetch_assoc()): ?>
<div><?=$oz['nome'];?></div>
<?php foreach(array('pista1a','pista1b','pista1c','pista2a','pista2b','pista2c','pista3a','pista3b','pista3c') as $pp): if(!empty($oz[$pp])): ?>
<div title="<?=(new DateTime($oz[$pp],$dtz))->format("d/m/Y - H:i:s");?>"><p><i class="fa fa-check"></i></p></div>
<?php else: ?>
<div><p><i class="fa fa-times"></i></p></div>
<?php endif; endforeach; endwhile; ?>
</div>
<?php else: ?>
<p>Pistas ainda não foram encontradas.</p>
<?php endif; ?>
<h3>Azevedo</h3>
<?php
$qvd = $vd->query("SELECT nome, 
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['AZV']['pistas'][0][0]}' AND tribo='AZV' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista1a,
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['AZV']['pistas'][0][1]}' AND tribo='AZV' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista1b,

(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['AZV']['pistas'][1][0]}' AND tribo='AZV' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista2a,
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['AZV']['pistas'][1][1]}' AND tribo='AZV' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista2b,

(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['AZV']['pistas'][2][0]}' AND tribo='AZV' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista3a,
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['AZV']['pistas'][2][1]}' AND tribo='AZV' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista3b
FROM {$vd->keys_t_last} k HAVING 
pista1a IS NOT NULL OR 
pista1b IS NOT NULL OR 
pista2a IS NOT NULL OR 
pista2b IS NOT NULL OR 
pista3a IS NOT NULL OR 
pista3b IS NOT NULL
ORDER BY nome ASC;");
if($qvd->num_rows):
?>
<div class="pistas">
<div class="header"><p>Nome</p></div>
<div class="header" style="grid-column: span 2"><p>Pista 1</p></div>
<div class="header" style="grid-column: span 2"><p>Pista 2</p></div>
<div class="header" style="grid-column: span 2"><p>Pista 3</p></div>
<?php while($oz=$qvd->fetch_assoc()): ?>
<div><?=$oz['nome'];?></div>
<?php foreach(array('pista1a','pista1b','pista2a','pista2b','pista3a','pista3b') as $pp): if(!empty($oz[$pp])): ?>
<div title="<?=(new DateTime($oz[$pp],$dtz))->format("d/m/Y - H:i:s");?>"><p><i class="fa fa-check"></i></p></div>
<?php else: ?>
<div><p><i class="fa fa-times"></i></p></div>
<?php endif; endforeach; endwhile; ?>
</div>
<?php else: ?>
<p>Pistas ainda não foram encontradas.</p>
<?php endif; ?>
<h3>D'Ávila</h3>
<?php
$qvd = $vd->query("SELECT nome, 
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['DAV']['pistas'][0][0]}' AND tribo='DAV' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista1a,
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['DAV']['pistas'][0][1]}' AND tribo='DAV' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista1b,

(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['DAV']['pistas'][1][0]}' AND tribo='DAV' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista2a,
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['DAV']['pistas'][1][1]}' AND tribo='DAV' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista2b,

(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['DAV']['pistas'][2][0]}' AND tribo='DAV' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista3a,
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['DAV']['pistas'][2][1]}' AND tribo='DAV' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista3b
FROM {$vd->keys_t_last} k HAVING 
pista1a IS NOT NULL OR 
pista1b IS NOT NULL OR 
pista2a IS NOT NULL OR 
pista2b IS NOT NULL OR 
pista3a IS NOT NULL OR 
pista3b IS NOT NULL
ORDER BY nome ASC;");
if($qvd->num_rows):
?>
<div class="pistas">
<div class="header"><p>Nome</p></div>
<div class="header" style="grid-column: span 2"><p>Pista 1</p></div>
<div class="header" style="grid-column: span 2"><p>Pista 2</p></div>
<div class="header" style="grid-column: span 2"><p>Pista 3</p></div>
<?php while($oz=$qvd->fetch_assoc()): ?>
<div><?=$oz['nome'];?></div>
<?php foreach(array('pista1a','pista1b','pista2a','pista2b','pista3a','pista3b') as $pp): if(!empty($oz[$pp])): ?>
<div title="<?=(new DateTime($oz[$pp],$dtz))->format("d/m/Y - H:i:s");?>"><p><i class="fa fa-check"></i></p></div>
<?php else: ?>
<div><p><i class="fa fa-times"></i></p></div>
<?php endif; endforeach; endwhile; ?>
</div>
<?php else: ?>
<p>Pistas ainda não foram encontradas.</p>
<?php endif; ?>
<h3>Villanova</h3>
<?php
$qvd = $vd->query("SELECT nome, 
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['VLL']['pistas'][0][0]}' AND tribo='VLL' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista1a,
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['VLL']['pistas'][0][1]}' AND tribo='VLL' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista1b,

(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['VLL']['pistas'][1][0]}' AND tribo='VLL' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista2a,
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['VLL']['pistas'][1][1]}' AND tribo='VLL' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista2b,

(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['VLL']['pistas'][2][0]}' AND tribo='VLL' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista3a,
(SELECT tpost FROM {$vd->idolo}_a WHERE coords='{$json['VLL']['pistas'][2][1]}' AND tribo='VLL' AND tkey=k.tkey ORDER BY tpost ASC LIMIT 1) pista3b
FROM {$vd->keys_t_last} k HAVING 
pista1a IS NOT NULL OR 
pista1b IS NOT NULL OR 
pista2a IS NOT NULL OR 
pista2b IS NOT NULL OR 
pista3a IS NOT NULL OR 
pista3b IS NOT NULL
ORDER BY nome ASC;");
if($qvd->num_rows):
?>
<div class="pistas">
<div class="header"><p>Nome</p></div>
<div class="header" style="grid-column: span 2"><p>Pista 1</p></div>
<div class="header" style="grid-column: span 2"><p>Pista 2</p></div>
<div class="header" style="grid-column: span 2"><p>Pista 3</p></div>
<?php while($oz=$qvd->fetch_assoc()): ?>
<div><?=$oz['nome'];?></div>
<?php foreach(array('pista1a','pista1b','pista2a','pista2b','pista3a','pista3b') as $pp): if(!empty($oz[$pp])): ?>
<div title="<?=(new DateTime($oz[$pp],$dtz))->format("d/m/Y - H:i:s");?>"><p><i class="fa fa-check"></i></p></div>
<?php else: ?>
<div><p><i class="fa fa-times"></i></p></div>
<?php endif; endforeach; endwhile; ?>
</div>
<?php else: ?>
<p>Pistas ainda não foram encontradas.</p>
<?php endif; ?>