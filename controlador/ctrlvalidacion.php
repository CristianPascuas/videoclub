<?php
require_once('../include/config.php'); 
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
include('../include/conex.php');
session_name($session_name);
session_start();
$conn=Conectarse();
switch ($_REQUEST['action']) 
    {
        case 'buscarUsuario':
            $jTableResult = array();
            $jTableResult['success'] = false;
            $jTableResult['resultados'] = "";
            
            $query = " SELECT p.Id_Persona, p.Nombre, p.Apellido, p.Identificacion, p.Email, p.Id_Validacion, v.nombre_validacion 
                      FROM persona p 
                      LEFT JOIN validacion v ON p.Id_Validacion = v.Id_Validacion 
                      WHERE p.Id_Persona LIKE '%".$_POST['buscarUsu']."%' 
                      OR p.Nombre LIKE '%".$_POST['buscarUsu']."%' 
                      OR p.Apellido LIKE '%".$_POST['buscarUsu']."%' 
                      OR p.Identificacion LIKE '%".$_POST['buscarUsu']."%'
                      OR p.Email LIKE '%".$_POST['buscarUsu']."%' 
                      OR v.nombre_validacion LIKE '%".$_POST['buscarUsu']."%'";
            
            $resultado = mysqli_query($conn, $query);
            
            if($resultado && mysqli_num_rows($resultado) > 0) {
                $tabla = '<div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Identificación</th>
                                        <th>Email</th>
                                        <th>Validación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>';
                
                while($registro = mysqli_fetch_array($resultado)) {
                    $tabla .= '<tr>
                                <td data-label="ID">'.$registro['Id_Persona'].'</td>
                                <td data-label="Nombre">'.$registro['Nombre'].'</td>
                                <td data-label="Apellido">'.$registro['Apellido'].'</td>
                                <td data-label="Identificación">'.$registro['Identificacion'].'</td>
                                <td data-label="Email">'.$registro['Email'].'</td>
                                <td data-label="Validación">'.$registro['nombre_validacion'].'</td>
                                <td data-label="Acciones">
                                    <button class="btn btn-warning btn-sm me-1" onclick="editarUsuario('.$registro['Id_Persona'].', \''.$registro['Nombre'].'\', \''.$registro['Apellido'].'\', '.$registro['Id_Validacion'].')" data-bs-toggle="modal" data-bs-target="#modalEditarValidacion">
                                        <i class="fas fa-edit"></i> Editar Validación
                                    </button>
                                </td>
                              </tr>';
                }
                
                $tabla .= '</tbody></table></div>';
                
                $jTableResult['success'] = true;
                $jTableResult['resultados'] = $tabla;
            } else {
                $jTableResult['resultados'] = '<div class="alert alert-info">No se encontraron usuarios con ese criterio de búsqueda.</div>';
            }
            
            echo json_encode($jTableResult);
            exit();
        break;
        
        case 'actualizarValidacion':
            $jTableResult = array();
            $jTableResult['success'] = false;
            $jTableResult['mensaje'] = "";
            
            $usuarioId = isset($_POST['usuarioId']) ? (int)$_POST['usuarioId'] : 0;
            $estadoValidacion = isset($_POST['estadoValidacion']) ? (int)$_POST['estadoValidacion'] : 0;
            
            if($usuarioId > 0 && in_array($estadoValidacion, [0, 1])) {
                $query = "UPDATE persona SET Id_Validacion = $estadoValidacion WHERE Id_Persona = $usuarioId";
                
                if(mysqli_query($conn, $query)) {
                    $jTableResult['success'] = true;
                    $jTableResult['mensaje'] = "Estado de validación actualizado correctamente";
                } else {
                    $jTableResult['mensaje'] = "Error al actualizar el estado de validación";
                }
            } else {
                $jTableResult['mensaje'] = "Datos inválidos";
            }
            
            echo json_encode($jTableResult);
            exit();
        break;
    }
mysqli_close($conn);
?>