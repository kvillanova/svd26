<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>WIKITABLE</title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="style.css" />
</head>
<body>
	<form>
		<div class="form-group">
		<label for="file">Arquivo:</label>
		<input type="file" name="file" id="file">
		</div>
		<div class="form-group">
		<label for="titulo">Título:</label>
		<input type="text" size="30" name="titulo" value="Histórico de Votação" />
		<input name="secao" type="number" min="2" max="5" value="3"/>
		</div>
		<?php if(isset($_GET['temp'])): ?>
		<input name="temporada" type="hidden" value="<?=$_GET['temp'];?>">
		<?php endif; ?>
		<textarea name="texto" cols="100" rows="30"></textarea>
		<button>Enviar</button>
	</form>
	<div id="r"></div>
	<script>
		$("form").submit(function(e){
			e.preventDefault();
			$.ajax({
				'url':'gettext.php',
				'type':'POST',
				'data': $(this).serializeArray()
			}).done(function(m) {
				$("textarea").val(m);
			})
		});
	</script>
</body>
</html>