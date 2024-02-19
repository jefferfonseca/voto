<?php
require_once("../funciones.php");	
require_once("../conexionBD.php");
$link=conectarse();
//***Leer variables del sistema******
$estado=mysqli_query($link, "select * from general");
$leer= mysqli_fetch_array($estado);
//****** Verificamos si existe la cookie *****/
if(isset($_COOKIE['VotaDatAdmin'])) {
	
	//****Agregar una nueva grado*******
	if (isset($_POST['envia_grado'])) {
		if ((borra_espacios($_POST['nombre_grado'])!="")) {
			$fnombre_grado=cambia_mayuscula(borra_espacios($_POST['nombre_grado']));
		}
		else {
			include_once("encabezado.html");
			print "<strong>Debe escribir el nombre del grado<br />";
			print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
			exit;
		}
		
		//*****Validamos que no exista una  grado duplicada**** 
		$duplica=0;
		$resp3=mysqli_query($link, "select * from grados");
		while($row3 = mysqli_fetch_array($resp3)) {
		        if (cambia_mayuscula($fnombre_grado)==cambia_mayuscula($row3["grado"])){
		               $duplica=1;
		        }
		}
		if ($duplica==1) {
		        include_once("encabezado.html");
		        print "<strong>Ya existe una grado con este nombre<br />";
		        print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
		        exit;
		}
		
		//******Guardamos los datos en la BD ******
		$cons_sql  = sprintf("INSERT INTO grados(grado) VALUES(%s)", comillas($fnombre_grado));
		mysqli_query($link, $cons_sql);

		//****obtener el id del grado guardado
		$id_grado=mysqli_insert_id($link);

		//******Guardamos los datos de control ******
                $ffecha=date("Y-m-d");
                $fhora=date("G:i:s");
                $fip = $_SERVER['REMOTE_ADDR'];
                $faccion="Admin_Crea_grado (id:".$id_grado.")";
                $cons_sql5  = sprintf("INSERT INTO control(c_fecha,c_hora,c_ip,c_accion,c_idest) VALUES(%s,%s,%s,%s,%d)", comillas($ffecha), comillas($fhora), comillas($fip), comillas($faccion),$_COOKIE['VotaDatAdmin']);
mysqli_query($link, $cons_sql5);

	}
	//****Actualizar informacion del grado*******
	if (isset($_POST['edita_grado'])) {
		if (($_POST['nombre_grado']!="")) {
			$fnombre_grado=cambia_mayuscula(borra_espacios($_POST['nombre_grado']));
		}
		else {
			include_once("encabezado.html");
			print "<strong>Debe escribir el nombre del grado<br />";
			print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
			exit;
		}
		//****Actualizar en la BD*******
		$cons_sql3  = sprintf("UPDATE grados SET grado=%s WHERE id=%d", comillas($fnombre_grado), $_POST['identificador']);
		mysqli_query($link, $cons_sql3);
	
		//******Guardamos los datos de control ******
                $ffecha=date("Y-m-d");
                $fhora=date("G:i:s");
                $fip = $_SERVER['REMOTE_ADDR'];
                $faccion="Admin_Actualiza_grado (id:".$_POST['identificador'].")";
                $cons_sql5  = sprintf("INSERT INTO control(c_fecha,c_hora,c_ip,c_accion,c_idest) VALUES(%s,%s,%s,%s,%d)", comillas($ffecha), comillas($fhora), comillas($fip), comillas($faccion),$_COOKIE['VotaDatAdmin']);
mysqli_query($link, $cons_sql5);
	
	}
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	echo '<html>';
	echo '<head><meta charset="UTF-8">
';
	echo '<title>'.$leer['institucion'].' - Configuración grados</title>';
	echo '<link href="../estilo.css
" rel="stylesheet" type="text/css" />';
	echo '</head>';
	echo '<body>';
	echo '<h1>'.$leer['institucion'].'</h1>';
	echo '<h2>CONFIGURACIÓN GRADOS</h2>';
	echo '<div align="center">';	
	//*****Formulario para agregar un grado *******
	if((isset($_GET['agrega']))and($_GET['agrega']=="ok")) { 
		echo '<form name="addgrado" action="grados.php" method="post">';
	        echo '<table>';
	        echo '<tr>';
	        echo '<td style="text-align:right;"><label for="nombre_grado">';
	        echo '<strong>Nombre del grado:</strong>';
	        echo '</label></td>';
	        echo '<td><input type="text" name="nombre_grado" size="30" maxlength="50" title="Escriba el nombre del grado" />';
	        echo '</td></tr>';	        

	        echo '<tr><td class="cen" colspan="2"><input type="submit" name="envia_grado" value="Guardar" title="Agregar grado" />&nbsp&nbsp&nbsp&nbsp';
		echo '<input type="button" name="Cancel" value="Cancelar" onclick="window.location =\'grados.php\' "/></td></tr>';
		echo '</form></table>';
	}
	else {
		echo '<div class=cen>';
		echo '<strong><a href="grados.php?agrega=ok" title="Agregar grado">Agregar grado</a></strong>';
		echo '</div>';
	}
	
	//*****Formulario para editar grado *******
	if((isset($_GET['id'])) and (isset($_GET['editar'])) and ($_GET['editar']=="ok")) { 
		$resp4=mysqli_query($link, sprintf("select * from grados where md5(id)=%s",comillas($_GET['id'])));
        	if ($row4 = mysqli_fetch_array($resp4)) {	

			echo '<br /><form name="editagrado" action="grados.php" method="post">';
		       	echo '<table>';
		       	echo '<tr>';
		        echo '<td style="text-align:right;"><label for="nombre_grado">';
		        echo '<strong>Nombre del grado:</strong>';
		        echo '</label></td>';
		        echo '<td><input type="text" name="nombre_grado" value="'.$row4['grado'].'" size="30" maxlength="50" title="Escriba el nombre de la grado" />';
		        echo '</td></tr>';
		        
				echo '<input type="hidden" name="identificador" value="'.$row4['id'].'" />';
		        echo '<tr><td class="cen" colspan="2"><input type="submit" name="edita_grado" value="Guardar" title="Agregar grado" />&nbsp&nbsp&nbsp&nbsp';
			echo '<input type="button" name="Cancel" value="Cancelar" onclick="window.location =\'grados.php\' "/></td></tr>';
			echo '</form></table>';
		}
		else {
		      	echo '<table>';
		        echo '<tr><td class="cen"><strong>No hay datos para el grado</strong></td></tr>';
		        echo '</table>';
		}	
	}
	//******Mostrar mensaje para borrar grado*******
	if((isset($_GET['id']))and(isset($_GET['elimina']))and($_GET['elimina']=="0")) {
		
		$resp5=mysqli_query($link, sprintf("select * from grados where md5(id)=%s",comillas($_GET['id'])));
	        if ($row5 = mysqli_fetch_array($resp5)) {
				//****Verificar que no existan estudiantes para eliminar el grado******
				$resp9=mysqli_query($link, sprintf("select id from estudiantes where grado=%d",$row5['id']));
				if (!$row9 = mysqli_fetch_array($resp9)) {
					echo '<br /><div class="cen"><strong>';
					echo '¿Desea borrar el grado '.$row5['grado'].' del sistema? ';
					echo '<a href="grados.php?id='.$_GET['id'].'&elimina=1" title="Borrar grado del sistema">Si</a>&nbsp&nbsp&nbsp&nbsp';
					echo '<a href="grados.php" title="Cancelar la eliminación del grado">No</a>';
					echo '</strong></div>';
				}
				else {
					echo '<br /><strong>Advertencia: Debe borrar primero los estudiantes que pertenecen al grado '.$row5['grado'].'</strong>';
				}
		}
		else {
			echo '<table>';
		        echo '<tr><td class="cen"><strong>No hay datos para el grado</strong></td></tr>';
		        echo '</table>';
		}
	}
	
	//*****Eliminar grado******
	if((isset($_GET['id']))and(isset($_GET['elimina']))and($_GET['elimina']=="1")) {
		$resp6=mysqli_query($link, sprintf("select * from grados where md5(id)=%s",comillas($_GET['id'])));
	    $row6 = mysqli_fetch_array($resp6);
		$resp2=mysqli_query($link, sprintf("delete from grados where md5(id)=%s",comillas($_GET['id'])));

		//******Guardamos los datos de control ******
                $ffecha=date("Y-m-d");
                $fhora=date("G:i:s");
                $fip = $_SERVER['REMOTE_ADDR'];
                $faccion="Admin_Borra_grado (Nombre:".$row6['grado'].")";
                $cons_sql5  = sprintf("INSERT INTO control(c_fecha,c_hora,c_ip,c_accion,c_idest) VALUES(%s,%s,%s,%s,%d)", comillas($ffecha), comillas($fhora), comillas($fip), comillas($faccion),$_COOKIE['VotaDatAdmin']);
mysqli_query($link, $cons_sql5);

	}
	
	//****MUESTRA LA TABLA DE GRADOS******
	echo '<br /><table>';
	echo '<thead><tr><th>NOMBRE</th><th colspan="2">OPCIONES</th></tr></thead>';
	$ContAdm=0;
	$resp=mysqli_query($link, sprintf("select * from grados order by id"));
	while($row = mysqli_fetch_array($resp)) {		
			echo '<tr>';
			echo '<td>['.$row['id'].'] '.$row['grado'].'</td>';
			echo '<td class="cen"><a href="grados.php?id='.md5($row['id']).'&editar=ok" title="Editar grado"><img src="../iconos/lapiz.png" border="0" width="20px" border="0" alt="Editar" /></a></td>';
			echo '<td class="cen"><a href="grados.php?id='.md5($row['id']).'&elimina=0" title="Borrar grado"><img src="../iconos/delete.png" border="0" alt="Borrar" /></a></td></tr>';
			$ContAdm=$ContAdm+1;		
	}
	if($ContAdm==0) {
		echo '<tr><td colspan="3"><strong>No existe información para mostrar</strong></td></tr>';
	}
	echo '</table><br />';
	echo '</div>';
	echo '</body>';
	echo '</html>';
}
else {
	include_once("encabezado.html");
      	echo '<table>';
        echo '<tr><td class="cen"><strong>Su sesión ha finalizado, por favor vuelva a ingresar al sistema</strong></td></tr>';
        echo '</table></div></body></html>';
}
mysqli_close($link);
?>
