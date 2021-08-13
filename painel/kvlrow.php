<div class="developer">
	<h3>Script/KvLanguage <i class="fa fa-question-circle"></i></h3>
	<form id="kvl">
		<div class="kvl-container">
			<div class="kvl-inside"><textarea name="script" class="script" cols="50" rows="12"></textarea>
			</div>
			<div class="kvl-inside console"><code class="log">>:CONSØLE/L0G</code>
			</div>
		</div>
		<div class="botoes">
		<button type="button" id="clearscript"><i class="fa fa-arrow-left"></i> Limpar scripts</button>
		<button type="submit">Enviar</button>
		<button type="button" id="clearlog">Limpar log <i class="fa fa-arrow-right"></i></button>
		</div>
	</form>
	<div id="content"></div>
	<?php if(preg_match("%chaves%uis",$_SERVER['REQUEST_URI'])): ?>
	<h3>Keygen</h3>
	<form id="mdgen">
		<input id="keyholder" type="text" size="20" value="—" readonly/>
		<button type="submit">Receber</button>
	</form>
	<?php endif; ?>
</div>