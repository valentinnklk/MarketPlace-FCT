<?php
session_start();
session_destroy();
header("Location: ../VISTA/loginVista.php");
exit();
?>