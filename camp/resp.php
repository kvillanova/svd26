<?php
const
TN_NAME = "\$services_e",
FOOTER_NAME = "unlimited",
NO_CHALLENGE = true,
FORCE_STATUSES = "0,1,2,3";
include "../inc/db/header.php";

$sender = $_POST['sender'];

if(file_exists("403/{$sender}.php")) include "403/{$sender}.php";