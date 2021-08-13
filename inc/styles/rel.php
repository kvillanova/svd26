<?php
if(!defined("NO_OWN_CSS")) define("NO_OWN_CSS", false);

if(!isset($jquery_ver)) $jquery_ver = "3.3.1";

if(isset($jquery_ui) and $jquery_ui) {
	$jquery_ui = PHP_EOL . "<script src=\"{$vd->address}inc/js/jquery-ui.min.js\"></script>";
	if(!isset($no_punch) or !$no_punch) $jquery_ui .= "<script src=\"{$vd->address}inc/js/punch.js\"></script>";
}
else $jquery_ui = NULL;

if($jquery_ui and (isset($jspunch) and !$jspunch))
	$jquery_ui = PHP_EOL . "<script src=\"{$vd->address}inc/js/jquery-ui.min.js\"></script>";

if(isset($fancybox) and $fancybox)
	$fancybox = PHP_EOL . '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js"></script>';
else $fancybox = NULL;

if(isset($jpuzzle) and $jpuzzle) {
	$jpuzzle = PHP_EOL . "<script type=\"text/javascript\" src=\"{$vd->address}inc/js/jquery.jqpuzzle.full.js\"></script><link rel=\"stylesheet\" href=\"{$vd->address}inc/js/jquery.jqpuzzle.css\" />";
	$jquery_ver = "1.5";
}
else $jpuzzle = NULL;

if(isset($jmask) and $jmask) {
	$jmask = PHP_EOL . "<script type=\"text/javascript\" src=\"{$vd->address}inc/js/jquery.mask.js\"></script>";
}
else $jmask = NULL;

if(isset($jscratch) and $jscratch) {
	$jscratch = PHP_EOL . "<script type=\"text/javascript\" src=\"{$vd->address}inc/js/scratchie.js\"></script>";
}
else $jscratch = NULL;

if(isset($jsclipboard) and $jsclipboard) {
	$jsclipboard = PHP_EOL . "<script type=\"text/javascript\" src=\"{$vd->address}inc/js/clipboard.min.js\"></script>";
}
else $jsclipboard = NULL;

if(isset($froala) and $froala) {
	//$froala = PHP_EOL . 
//"<link rel=\"stylesheet\" href=\"{$vd->address}inc/js/froala/css/froala_editor.pkgd.min.css\">" .
//"<script type=\"text/javascript\" src=\"{$vd->address}inc/js/froala/js/froala_editor.pkgd.min.js\"></script>" .
//"<script type=\"text/javascript\" src=\"{$vd->address}inc/js/froala/js/languages/pt_br.js\"></script>";
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/3.2.3/js/froala_editor.pkgd.min.js" integrity="sha512-Kyk8wCAFF3GLMUvEy/Zx5Q0hN4ojyMfc2fgKXFy+lMhum3kQ53pAIXM2weqdBQr/Ia/+A6WXiQoyDm23osPoOw==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/3.2.3/css/froala_editor.pkgd.min.css" integrity="sha512-NqrmYgJpvRxKvUhETixZ4R9X3kWhSxZW8plJpjqIPI7i+73mJ50qNj8av2hifvXtQyV4EfxSkMBPvzo6eq9TcA==" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/3.2.3/js/languages/pt_br.min.js" integrity="sha512-J8qAg0T7X96xCaihowls5UfZt4oou0BbkA7ashrZShxE8CuALvHMFd1ItRZlgX2pAeFL4N1+6dk2w5azGQN7sg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/3.2.3/js/plugins.pkgd.min.js" integrity="sha512-KGeXyZaI9e/JnskSNaTwGiEn4FYgTXpWtmU2Ug4maSFKjXxGn9FjhjYT8c5wupLnecrXY+ugjORe2kYiL5l7tA==" crossorigin="anonymous"></script>
<?php
}
else $froala = NULL;

if(isset($force_microtime)) $force_microtime = "?v=" . microtime(true);
else $force_microtime = NULL;

$own_css = NULL;
if(!NO_OWN_CSS):

if(file_exists(getcwd()."/style.css")) 
	$own_css .= PHP_EOL . '<link rel="stylesheet" href="style.css'.$force_microtime.'">';

if(TN_SUBDIR && file_exists(dirname(getcwd())."/style.css"))
	$own_css = '<link rel="stylesheet" href="../style.css'.$force_microtime.'">' . $own_css;

endif; //NO_OWN CSS

if(defined('VDSQL::FAVICON')) $favicon = $vd::FAVICON;
else $favicon = NULL;

echo <<<O
<link rel="shortcut icon" href="{$favicon}">
<link rel="stylesheet" href="{$vd->address}inc/styles/w3.css">
<link rel="stylesheet" href="{$vd->address}inc/styles/style.css{$force_microtime}">
<link rel="stylesheet" href="{$vd->address}inc/styles/fontawesome/css/all.min.css">
<script src="{$vd->address}inc/js/jquery-{$jquery_ver}.min.js"></script>{$jquery_ui}{$fancybox}{$jmask}{$jpuzzle}{$jscratch}{$jsclipboard}{$own_css}
O;
echo PHP_EOL;
?>