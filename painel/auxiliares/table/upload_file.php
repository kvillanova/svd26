<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Uploader</title>
</head>
<body>
<?php
if ($_FILES["file"]["error"] > 0)
  {
  echo "Error: " . $_FILES["file"]["error"] . "<br>";
  }
else
  {
  /* echo "Upload: " . $_FILES["file"]["name"] . "<br>";
  echo "Type: " . $_FILES["file"]["type"] . "<br>";
  echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
  echo "Stored in: " . $_FILES["file"]["tmp_name"] . "<br>"; */
  }
  $filename = $_FILES["file"]["tmp_name"];
  $handle = fopen($filename, "r");
  $c = fread($handle, filesize($filename));
  
  //REFs
  $c = preg_replace('%<span.*?class="reference.*?id="ref_(.*?)".*?>.*?<span.*?style="color:#(.*?)">(.*?)</span>.*?</span>%uis', '{{ref|$1|$3|$2}}', $c);
  $c = preg_replace('%<span.*?class="reference.*?id="ref_(.*?)".*?><a.*?>(.*?)</a></span>%uis', '{{ref|$1|$2}}', $c);
  
  //NOTA
  $c = preg_replace('%<cite.*?id="nota_(.*?)".*?><a.*?><b>.*?([0-9]{1,2})</b></a></cite>%sui', '{{nota|$1|$2}}', $c); //NOTA
 
 //STRIP TAGS
  $c = preg_replace('%<br\s?/?>%sui', '&lt;br /&gt;', $c);
  $c = preg_replace('%<p.*?>(.*?)</p>%sui', '$1', $c);
  $c = preg_replace('%<span(.*?)style=%', '&lt;span$1style=', $c);
  $c = preg_replace('%</span%', '&lt;/span', $c);
  
  //TABLE
  $c = preg_replace('%<table>%sui', '{|', $c);
  $c = preg_replace('%<table(.*?)>%sui', '{| $1 |', $c);
  $c = preg_replace('%</table>%sui', '<br/>|}', $c);
  //TR
  $c = preg_replace('%<tr>%sui', '<br/>|-', $c);
  $c = preg_replace('%<tr(.*?)>%sui', '<br/>|- $1 |', $c);
  $c = preg_replace('%</tr>%sui', '', $c);
  //TH
  $c = preg_replace('%<th>%sui', '<br/>!', $c);
  $c = preg_replace('%<th(.*?)>%sui', '<br/>! $1 |', $c);
  $c = preg_replace('%</th>%sui', '', $c);
  //TD
  $c = preg_replace('%<td>%sui', '<br/>|', $c);
  $c = preg_replace('%<td(.*?)>%sui', '<br/>| $1 |', $c);
  $c = preg_replace('%</td>%sui', '', $c);
  
  $c = preg_replace('%<thead.*?>(.*?)</thead>%sui', '$1', $c);
  $c = preg_replace('%<tbody.*?>(.*?)</tbody>%sui', '$1', $c);
  
  $c = preg_replace('%<a.*?title="(.*?)".*?>(.*?)</a>%sui', '[[$1|$2]]', $c); //PEEPS
  
  $c = preg_replace('%<small>%sui', '<br>&lt;small&gt;', $c);
  $c = preg_replace('%</small>%sui', '<br>&lt;/small&gt;', $c);
  
  $c = preg_replace('%{{nota%sui', '<br>{{nota', $c);
  
//B AND I
$c = preg_replace('%(</?b>)|(</?strong>)%sui', "'''", $c);
$c = preg_replace('%(</?i>)|(</?oblique>)%sui', "''", $c);
  
   $c = preg_replace('%==(.*?)==%sui', '==$1==<br>', $c);
  
  echo $c;
?>
</body>
</html>