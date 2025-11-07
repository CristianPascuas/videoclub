
case 'buscarId_Usu':
			$jTableResult = array();
			$jTableResult['id_persona']="";
			$jTableResult['numero_identificacion']="";
			$jTableResult['nombre']="";
			$jTableResult['apellido']="";
			$jTableResult['telefono']="";
			$jTableResult['email']="";
			$jTableResult['estado']="";
			$jTableResult['Id_rol_fk']="";
			$jTableResult['nombre_rol']="";
			$jTableResult['id_area_FK']="";
			$jTableResult['areas']="";
				$query = " SELECT  prsn.id_persona,  prsn.fecha_Registro_Usu,  prsn.numero_identificacion,  prsn.nombre,
				prsn.apellido,  prsn.telefono,  prsn.email,  prsn.clave,  prsn.estado,  prsn.id_rol_fk,
				rl.nombre_rol
				FROM    persona prsn INNER JOIN rol rl ON prsn.id_rol_fk = rl.Id_rol 
				WHERE prsn.id_persona='".$_SESSION['id_Usu']."';"; 
				//exit();
				$resultado = mysqli_query($conn, $query);
				while($registro = mysqli_fetch_array($resultado))
					{
						$jTableResult['id_persona']=$registro['id_persona'];
						$jTableResult['numero_identificacion']=$registro['numero_identificacion'];
						$jTableResult['nombre']=$registro['nombre'];
						$jTableResult['apellido']=$registro['apellido'];
						$jTableResult['telefono']=$registro['telefono'];
						$jTableResult['email']=$registro['email'];
						$jTableResult['estado']=$registro['estado'];
						$jTableResult['Id_rol_fk']=$registro['id_rol_fk'];
						$jTableResult['nombre_rol']=$registro['nombre_rol'];
						$jTableResult['id_area_FK']=$registro['id_area_FK'];
					}
				$jTableResult['areas']="<option value='0'>:.</option>";
				$query = " select id_area, nombre_area FROM area";	
				$resultado = mysqli_query($conn, $query);
				while($registro = mysqli_fetch_array($resultado))
					{
						if($jTableResult['id_area_FK']==$registro['id_area']){
							$jTableResult['areas'].="<option value='".$registro['id_area']."' selected >".$registro['nombre_area']."</option>";
						}else{
							$jTableResult['areas'].="<option value='".$registro['id_area']."'>".$registro['nombre_area']."</option>";							
						}
					}					
			print json_encode($jTableResult);
		break;