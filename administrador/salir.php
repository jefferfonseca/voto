<?php
//**** Eliminar cookie de sesion *****
setcookie("VotaDatAdmin", "", time() - 3600);

//**** Redireccionar página web *****
header("Location: index.php");
exit();
