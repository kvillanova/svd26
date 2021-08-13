<?php
/* PAINEL DE ADMINISTRAÇÃO - IDOLOS */
$force_microtime=1;
require_once "../header.php";
?>
<h1><i class="fa fa-binoculars"></i> Ídolos</h1>
<?php 
$dev = isset($_GET['developer']) ? (bool) $_GET['developer'] : false;
if($dev) include "../kvlrow.php";
$nome = $_GET['nome'];
?>
<h2>Palpites<em>/<?=$nome;?></em></h2>
<p><a href="./"><i class="fa fa-arrow-left"></i> Voltar</a></p>
<div class="painel-table palpitando">
<div class="header">#</div>
<div class="header">Palpite</div>
<div class="header">Horário</div>
<?php
$qvd = $vd->query("SELECT 
(SELECT COUNT(resp) FROM {$vd->idolo}_p WHERE tkey=x.tkey) resps,
resp, tpost 
FROM {$vd->idolo}_p x WHERE tkey=(SELECT tkey FROM {$vd->keys_t_last} WHERE nome='{$nome}') ORDER BY tpost DESC;");
echo $vd->error;
while($oz = $qvd->fetch_assoc()): 
extract($oz);
$tpost = !empty($tpost) ? (new DateTime($tpost,$dtz))->format("d/m/Y - H:i:s") : "&mdash;";
if(!isset($i)) $i = $resps;
else --$i;
?>
<div><p><?=$i;?></p></div>
<div class="resp"><p><?=$resp;?></p></div>
<div><p><?=$tpost;?></p></div>
<?php endwhile; ?>
</div>
<?php if($dev): ?>
<script src="../kvl.js"></script>
<?php endif; ?>
</body>
</html>