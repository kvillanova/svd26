<?php
require_once "header.php";

if(isset($_GET['destinos'])) include "index.php";
elseif(isset($_GET['idolo'])) include "idolo.php";
elseif(isset($_GET['pistas'])) include "pistas.php";
elseif(isset($_GET['quest'])) { $_GET['id']=0; include "questionarios.php"; }
?>