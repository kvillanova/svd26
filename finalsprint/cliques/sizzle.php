<?php
require '../../inc/js/php-packer/autoload.php';
$js = file_get_contents("403/script.js");

/*
 * params of the constructor :
 * $script:           the JavaScript to pack, string.
 * $encoding:         level of encoding, int or string :
 *                    0,10,62,95 or 'None', 'Numeric', 'Normal', 'High ASCII'.
 *                    default: 62 ('Normal').
 * $fastDecode:       include the fast decoder in the packed result, boolean.
 *                    default: true.
 * $specialChars:     if you have flagged your private and local variables
 *                    in the script, boolean.
 *                    default: false.
 * $removeSemicolons: whether to remove semicolons from the source script.
 *                    default: true.
 */

$packer = new Tholu\Packer\Packer($js,10,false,true,true);
$packed_js = $packer->pack();
$packer = new Tholu\Packer\Packer($packed_js,62,false,true,true);
$packed_js = $packer->pack();
$packer = new Tholu\Packer\Packer($packed_js,95,false,true,true);
$packed_js = $packer->pack();
echo utf8_encode($packed_js);