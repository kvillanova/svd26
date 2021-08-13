<?php
//REFs
$c = preg_replace('%<span.*?class="reference.*?id="ref_(.*?)">.*?<a.*?>(.*?)</a>.*?</span>%uis', '{{ref|$1|$2}}', $c);

//NOTA
$c = preg_replace('%<cite.*?id="nota_(.*?)".*?><a.*?><b>.*?([0-9]{1,2})</b></a></cite>%sui', '{{nota|$1|$2}}', $c); //NOTA

//STRIP TAGS
$c = preg_replace('%<br\s?/?>\s*%sui', '<br />', $c);
$c = preg_replace('%<p.*?>(.*?)</p>%sui', '$1', $c);
$c = preg_replace('%<noscript>.*?</noscript>%uis', '', $c);

//TABLE
$c = preg_replace('%<table>%sui', '{|', $c);
$c = preg_replace('%<table(.*?)>%sui', '{| $1 |', $c);
$c = preg_replace('%</table>%sui', '|}', $c);
//TR
$c = preg_replace('%<tr>%sui', '|-', $c);
$c = preg_replace('%<tr(.*?)>%sui', '|- $1 |', $c);
$c = preg_replace('%</tr>%sui', '', $c);
//TH
$c = preg_replace('%<th>%sui', '!', $c);
$c = preg_replace('%<th(.*?)>%sui', '! $1 |', $c);
$c = preg_replace('%</th>%sui', '', $c);
//TD
$c = preg_replace('%<td>%sui', '|', $c);
$c = preg_replace('%<td(.*?)>%sui', '| $1 |', $c);
$c = preg_replace('%</td>%sui', '', $c);

$c = preg_replace('%</?t(head|body)>%sui', '', $c);

$c = preg_replace('%<a.*?title="(.*?)".*?>.*?img.*?data-image-name="(.*?)".*?width="(.*?)"(.*?)</a>%sui', '[[Arquivo:$2|$3px|link=$1]]', $c); //PEEPS

$c = preg_replace('%<a.*?title="(.*?)".*?\>(.*?)</a>%sui', '[[$1|$2]]', $c); //PEEPS

//B AND I
$c = preg_replace('%(</?b>)|(</?strong>)%sui', "'''", $c);
$c = preg_replace('%(</?i>)|(</?oblique>)|(</?em>)%sui', "''", $c);

//ESPAÇOS
$c = preg_replace('%\n\s+%', '', $c);
?>