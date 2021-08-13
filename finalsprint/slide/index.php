<?php
const
ATUAL_NO=5,
TEND_NO=6,
TN_SUBDIR=true,
MDFY_A = array("LSODOXISDEJHSDHSDHSDH","eee44431111)$)#");
include "../../inc/db/header.php";
?>
<!doctype html>
<html>
<head>
<?php
$jpuzzle=true;
include "../../inc/styles/og.php";
include "../../inc/styles/rel.php";
?>
</head>
<body>
<h1>Slide Puzzle</h1>
<img src="<?=mdfy($atual_int,MDFY_A,15,"{$atual_int}_");?>.jpg" id="screen" />
<script src="puzzle.js"></script>
<div id="r"></div>
</body>
</html>