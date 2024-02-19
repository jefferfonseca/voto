<?php
require_once("../funciones.php");	
require_once("../conexionBD.php");
$link=conectarse();
//***Leer variables del sistema******
$estado=mysqli_query($link, "select * from general");
$leer= mysqli_fetch_array($estado);
//****** Verificamos si existe la cookie *****/
if(isset($_COOKIE['VotaDatAdmin'])) {
	
	//****Agregar una nueva categoria*******
	if (isset($_POST['envia_categoria'])) {
		if ((borra_espacios($_POST['nombre_cat'])!="")and(borra_espacios($_POST['descripcion_cat'])!="")) {
			$fnombre_cat=borra_espacios($_POST['nombre_cat']);
			$fdescripcion_cat=borra_espacios($_POST['descripcion_cat']);
		}
		else {
			include_once("encabezado.html");
			print "<strong>Debe llenar todos los campos<br />";
			print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
			exit;
		}
		
		//*****Validamos que no exista una  categoría duplicada**** 
		$duplica=0;
		$resp3=mysqli_query($link, "select * from categorias");
		while($row3 = mysqli_fetch_array($resp3)) {
		        if (cambia_mayuscula($fnombre_cat)==cambia_mayuscula($row3["nombre"])){
		               $duplica=1;
		        }
		}
		if ($duplica==1) {
		        include_once("encabezado.html");
		        print "<strong>Ya existe una categoría con este nombre<br />";
		        print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
		        exit;
		}
		
		//******Guardamos los datos en la BD ******
		$cons_sql  = sprintf("INSERT INTO categorias(nombre,descripcion) VALUES(%s,%s)", comillas($fnombre_cat),comillas($fdescripcion_cat));
		mysqli_query($link, $cons_sql);

		//****obtener el id de la categoria guardada
		$id_cat=mysql_insert_id($link);

		//******Guardamos los datos de control ******
                $ffecha=date("Y-m-d");
                $fhora=date("G:i:s");
                $fip = $_SERVER['REMOTE_ADDR'];
                $faccion="Admin_Crea_Categoria (id:".$id_cat.")";
                $cons_sql5  = sprintf("INSERT INTO control(c_fecha,c_hora,c_ip,c_accion,c_idest) VALUES(%s,%s,%s,%s,%d)", comillas($ffecha), comillas($fhora), comillas($fip), comillas($faccion),$_COOKIE['VotaDatAdmin']);
mysqli_query($link, $cons_sql5);

	}
	//****Actualizar información de la categoria*******
	if (isset($_POST['edita_cat'])) {
		if (($_POST['nombre_cat']!="")and($_POST['descripcion_cat']!="")) {
			$fnombre_cat=borra_espacios($_POST['nombre_cat']);
			$fdescripcion_cat=borra_espacios($_POST['descripcion_cat']);
		}
		else {
			include_once("encabezado.html");
			print "<strong>Debe llenar todos los campos<br />";
			print"<br /><a href='javascript:history.go(-1)'>Volver al formulario</a></strong></div></body></html>";
			exit;
		}
		//****Actualizar en la BD*******
		$cons_sql3  = sprintf("UPDATE categorias SET nombre=%s, descripcion=%s WHERE id=%d", comillas($fnombre_cat),comillas($fdescripcion_cat), $_POST['identificador']);
		mysqli_query($link, $cons_sql3);
	
		//******Guardamos los datos de control ******
                $ffecha=date("Y-m-d");
                $fhora=date("G:i:s");
                $fip = $_SERVER['REMOTE_ADDR'];
                $faccion="Admin_Actualiza_Categoria (id:".$_POST['identificador'].")";
                $cons_sql5  = sprintf("INSERT INTO control(c_fecha,c_hora,c_ip,c_accion,c_idest) VALUES(%s,%s,%s,%s,%d)", comillas($ffecha), comillas($fhora), comillas($fip), comillas($faccion),$_COOKIE['VotaDatAdmin']);
mysqli_query($link, $cons_sql5);
	
	}
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	echo '<html>';
	echo '<head><meta charset="UTF-8">
';
	echo '<title>'.$leer['institucion'].' - categorías de votación</title>';
	echo '<link href="../estilo.css
" rel="stylesheet" type="text/css" />';
	echo '</head>';
	echo '<body>';
	echo '<h1>'.$leer['institucion'].'</h1>';
	echo '<h2>categorías DE votación</h2>';
	echo '<div align="center">';	
	//*****Formulario para agregar una categoría *******
	if((isset($_GET['agrega']))and($_GET['agrega']=="ok")) { 
		echo '<form name="addcat" action="categorias.php" method="post">';
	        echo '<table>';
	        echo '<tr>';
	        echo '<td style="text-align:right;"><label for="nombre_cat">';
	        echo '<strong>Nombre:</strong>';
	        echo '</label></td>';
	        echo '<td><input type="text" name="nombre_cat" size="30" maxlength="50" title="Escriba el nombre de la categoria" />';
	        echo '</td></tr>';
	        echo '<tr>';
	        echo '<td style="text-align:right;"><label for="descripcion_cat">';
	        echo '<strong>Descripción:</strong>';
	        echo '</label></td>';
	        echo '<td><input type="text" name="descripcion_cat" size="30" maxlength="50" title="Escriba la Descripción de la categoría" />';
	        echo '</td></tr>';	        

	        echo '<tr><td class="cen" colspan="2"><input type="submit" name="envia_categoria" value="Guardar" title="Agregar categoría" />&nbsp&nbsp&nbsp&nbsp';
		echo '<input type="button" name="Cancel" value="Cancelar" onclick="window.location =\'categorias.php\' "/></td></tr>';
		echo '</form></table>';
	}
	else {
		echo '<div class=cen>';
		echo '<strong><a href="categorias.php?agrega=ok" title="Agregar categoría">Agregar categoría</a></strong>';
		echo '</div>';
	}
	
	//*****Formulario para editar categoría *******
	if((isset($_GET['id'])) and (isset($_GET['editar'])) and ($_GET['editar']=="ok")) { 
		$resp4=mysqli_query($link, sprintf("select * from categorias where md5(id)=%s",comillas($_GET['id'])));
        	if ($row4 = mysqli_fetch_array($resp4)) {	

			echo '<br /><form name="editacat" action="categorias.php" method="post">';
		       	echo '<table>';
		       	echo '<tr>';
		        echo '<td style="text-align:right;"><label for="nombre_cat">';
		        echo '<strong>Nombre:</strong>';
		        echo '</label></td>';
		        echo '<td><input type="text" name="nombre_cat" value="'.$row4['nombre'].'" size="30" maxlength="50" title="Escriba el nombre de la categoría" />';
		        echo '</td></tr>';
		        echo '<tr>';
		        echo '<td style="text-align:right;"><label for="descripcion_cat">';
		        echo '<strong>Descripción:</strong>';
		        echo '</label></td>';
		        echo '<td><input type="text" name="descripcion_cat" value="'.$row4['descripcion'].'" size="30" maxlength="50" title="Escriba la Descripción de la categoría" />';
		        echo '</td></tr>';		        
				echo '<input type="hidden" name="identificador" value="'.$row4['id'].'" />';
		        echo '<tr><td class="cen" colspan="2"><input type="submit" name="edita_cat" value="Guardar" title="Agregar categoría" />&nbsp&nbsp&nbsp&nbsp';
			echo '<input type="button" name="Cancel" value="Cancelar" onclick="window.location =\'categorias.php\' "/></td></tr>';
			echo '</form></table>';
		}
		else {
		      	echo '<table>';
		        echo '<tr><td class="cen"><strong>No hay datos para la categoría</strong></td></tr>';
		        echo '</table>';
		}	
	}
	//******Mostrar mensaje para borrar categoría*******
	if((isset($_GET['id']))and(isset($_GET['elimina']))and($_GET['elimina']=="0")) {		
		
		$resp5=mysqli_query($link, sprintf("select * from categorias where md5(id)=%s",comillas($_GET['id'])));
	        if ($row5 = mysqli_fetch_array($resp5)) {
				//****Verificar que no existan candidatos para eliminar la categoría******
				$resp9=mysqli_query($link, sprintf("select id from candidatos where representante=%d",$row5['id']));
				if (!$row9 = mysqli_fetch_array($resp9)) {
					echo '<br /><div class="cen"><strong>';
					echo '¿Desea borrar la categoría '.$row5['nombre'].' del sistema? ';
					echo '<a href="categorias.php?id='.$_GET['id'].'&elimina=1" title="Borrar categoría del sistema">Si</a>&nbsp&nbsp&nbsp&nbsp';
					echo '<a href="categorias.php" title="Cancelar la eliminación de la categoría">No</a>';
					echo '</strong></div>';
				}
				else {
					echo '<br /><strong>Advertencia: Debe borrar primero los candidatos que pertenecen a la categoría '.$row5['nombre'].'.</strong>';
				}
		}
		else {
			echo '<table>';
		        echo '<tr><td class="cen"><strong>No hay datos para la categoría</strong></td></tr>';
		        echo '</table>';
		}
	}
	
	//*****Eliminar categoría******
	if((isset($_GET['id']))and(isset($_GET['elimina']))and($_GET['elimina']=="1")) {
		$resp6=mysqli_query($link, sprintf("select * from categorias where md5(id)=%s",comillas($_GET['id'])));
	        $row6 = mysqli_fetch_array($resp6);
		$resp2=mysqli_query($link, sprintf("delete from categorias where md5(id)=%s",comillas($_GET['id'])));

		//******Guardamos los datos de control ******
                $ffecha=date("Y-m-d");
                $fhora=date("G:i:s");
                $fip = $_SERVER['REMOTE_ADDR'];
                $faccion="Admin_Borra_categoría (Nombre:".$row6['nombre'].")";
                $cons_sql5  = sprintf("INSERT INTO control(c_fecha,c_hora,c_ip,c_accion,c_idest) VALUES(%s,%s,%s,%s,%d)", comillas($ffecha), comillas($fhora), comillas($fip), comillas($faccion),$_COOKIE['VotaDatAdmin']);
mysqli_query($link, $cons_sql5);

	}
	
	//****MUESTRA LA TABLA DE categorías******
	echo '<br /><table>';
	echo '<thead><tr><th>NOMBRE</th><th colspan="2">OPCIONES</th></tr></thead>';
	$ContAdm=0;
	$resp=mysqli_query($link, sprintf("select * from categorias order by id"));
	while($row = mysqli_fetch_array($resp)) {		
			echo '<tr>';
			echo '<td>'.$row['nombre'].' ('.$row['descripcion'].')</td>';
			echo '<td class="cen"><a href="categorias.php?id='.md5($row['id']).'&editar=ok" title="Editar categoría"><img src="../iconos/lapiz.png" border="0" width="20px" border="0" alt="Editar" /></a></td>';
			echo '<td class="cen"><a href="categorias.php?id='.md5($row['id']).'&elimina=0" title="Borrar categoría"><img src="../iconos/delete.png" border="0" alt="Borrar" /></a></td></tr>';
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
