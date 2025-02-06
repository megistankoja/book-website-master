<?php

include 'config.php';

session_start();/*fillon një sesion */
session_unset();/*hiqen të gjitha të dhënat e ruajtura në sesion */
session_destroy();/*shkatërron sesionin PHP të momentit dhe çdo të dhënë e ruajtur në të */

header('location:login.php');/*përcakton se faqja duhet të ndërrohet në faqen 
(login.php) pasi të ketë përfunduar funksioni logout */

?>