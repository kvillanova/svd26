<?php
include "../../header.php";
?>
<h1><i class="fa fa-chess"></i> Auxiliar/DB</h1>
	<form action="">
		<label for="tabela">Tabela</label>
		<input name="tabela" type="text" maxlenght="100">
		<div class="developer">
		<div class="kvl-container">
			<div class="kvl-inside"><textarea name="texto" class="script" cols="50" rows="12"></textarea>
			</div>
			<div class="kvl-inside console"><code class="log" style="display:inline-block;">>:RESULTS</code>
			</div>
		</div>
		<br/>
		<input type="button" value="← Limpar códigos" id="clearscript"/>
		<input type="submit" value="Enviar"/>
		<input type="button" value="Limpar resultado →" id="clearlog"/>
	</form>
	<script>
		$("form").submit(function(e) {
			e.preventDefault();
			$.ajax({
				"url":"resp.php",
				"type":"POST",
				"data": $(this).serializeArray()
			}).done(function(m) {
				$(".log").html(m);
			});
		});
	</script>
</body>
</html>