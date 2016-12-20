<?php

include ('app/boot.php');

print_r(_C_App::getLoaded());

echo _C_Router::getModule().'<br />';

echo _C_Router::getClass().'<br />';
echo _C_Router::getMethod().'<br />';
echo _C_Router::getData().'<br />';
echo _C_Router::getClientIp();

?>
