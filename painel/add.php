<?php
$currentdir = dirname(__FILE__);
$htaccess = fopen(".htaccess", 'w');
$htwrite = <<<WRITING
AuthType Basic
AuthName "Área restrita"
AuthUserFile $currentdir/.htpasswd
Require valid-user
WRITING;
fwrite($htaccess, $htwrite);

$htpass = fopen(".htpasswd", "w");
$pw = "testing!vd21";
$cp = crypt($pw, base64_encode($pw));
$htwrite = <<<WRITING
vd:$cp
WRITING;
fwrite($htpass, $htwrite);
?>