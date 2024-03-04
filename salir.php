<?php
//**** Eliminar cookie de sesi�n *****
setcookie("DataVota", "", time() - 3600);

//**** Redireccionar p�gina web *****
header("Location: index.php");
exit();
