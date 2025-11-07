<?php
header('Cache-Control: no-cache, must-revalidate'); 
date_default_timezone_set('America/Bogota');
header('Content-Type: application/json');
include('../include/conex.php');
$conn=Conectarse();
$fecha = date("Y-m-d");
switch ($_REQUEST['action']) 
    {	
        // Listar roles con botón para asignar permisos		
        case 'presentarRoles':
            $jTableResult = array();
            $jTableResult['msjListar']="";
            $jTableResult['rsultd']="";
            $jTableResult['listaRoles']="";
                $query="SELECT id_rol, rol_nombre 
                FROM rol 
                ORDER BY rol_nombre ASC ";
                $jTableResult['listaRoles']="
                    <thead>
                        <tr>
                            <th scope='col' >Nombre</th>
                        </tr>
                    </thead>";
                if($result= mysqli_query($conn,$query)){
                    while($registro = mysqli_fetch_array($result))
                        {
                            $jTableResult['listaRoles'].="
                            <tr >
                                <td width='3%'>
                                    <button  
                                        type='button'
                                        id='idBtnRol' 
                                        name='idBtnRol'
                                        class='btn btn-danger btn-sm'
                                        data-bs-toggle='modal'									
                                        data-bs-target='#asignarPermisos'
                                        data-idrol=	'".$registro['id_rol']."'
                                        data-nomrerol='".$registro['rol_nombre']."'
                                        title='asignar permisos para ".$registro['rol_nombre']."'>
                                        asignar permisos ".$registro['rol_nombre']."
                                    </button>
                                </td>
                            <tr>";
                        }
                }else{
                    
                }				
             print json_encode($jTableResult);
         break;
        // Listar menú y submenú con checkswitch para asignar permisos
        case 'presentarMenu':
            $jTableResult = array();
            $jTableResult['msjListar']="";
            $jTableResult['rsultd']="";
            $jTableResult['listaMenu']="";

            $idRol = isset($_REQUEST['idrol']) ? (int)$_REQUEST['idrol'] : 0;
            $accordionId = "accordionPermisosR".$idRol;

            // Obtener permisos existentes para este rol
            $permisosExistentes = array();
            $queryPermisos = "SELECT id_menu, id_submenu FROM permisos WHERE id_rol = $idRol";
            if($resultPermisos = mysqli_query($conn, $queryPermisos)){
                while($permiso = mysqli_fetch_array($resultPermisos)){
                    $key = $permiso['id_menu'].'_'.$permiso['id_submenu'];
                    $permisosExistentes[$key] = true;
                }
                mysqli_free_result($resultPermisos);
            }

            $query="SELECT Id_Menu, Nombre_Menu, Orden_Menu FROM menu ORDER BY Orden_Menu ASC ";
            $jTableResult['listaMenu'] = "<div class='accordion accordion-flush' id='{$accordionId}'>";

            // Cargar los menús
            if($result = mysqli_query($conn, $query)){
                while($registro = mysqli_fetch_array($result)){
                    $idMenu = (int)$registro['Id_Menu'];
                    $nombreMenu = htmlspecialchars($registro['Nombre_Menu'], ENT_QUOTES, 'UTF-8');
                    $headingId = "headingR{$idRol}_M{$idMenu}";
                    $collapseId = "collapseR{$idRol}_M{$idMenu}";

                    $jTableResult['listaMenu'] .= "
                    <div class='accordion-item'>
                        <h2 class='accordion-header' id='{$headingId}'>
                            <button class='accordion-button collapsed' type='button'
                                data-bs-toggle='collapse'
                                data-bs-target='#{$collapseId}'
                                aria-expanded='false'
                                aria-controls='{$collapseId}'>
                                {$nombreMenu}
                            </button>
                        </h2>
                        <div id='{$collapseId}' class='accordion-collapse collapse' aria-labelledby='{$headingId}' data-bs-parent='#{$accordionId}'>
                            <div class='accordion-body p-0'>";

                    // Submenús con switch por rol
                    $querySub = "SELECT Id_Submenu, Nombre_Submenu, Orden_Submenu 
                                 FROM submenu 
                                 WHERE Id_Menu = {$idMenu}
                                 ORDER BY Orden_Submenu ASC";
                    if($resultSub = mysqli_query($conn, $querySub)){
                        if(mysqli_num_rows($resultSub) > 0){
                            $jTableResult['listaMenu'] .= "<ul class='list-group list-group-flush submenu-list'>";
                            while($sub = mysqli_fetch_array($resultSub)){
                                $idSub = (int)$sub['Id_Submenu'];
                                $nombreSub = htmlspecialchars($sub['Nombre_Submenu'], ENT_QUOTES, 'UTF-8');
                                $switchId = "switchR{$idRol}_M{$idMenu}_S{$idSub}";

                                // Verificar si existe el permiso
                                $keyPermiso = $idMenu.'_'.$idSub;
                                $checked = isset($permisosExistentes[$keyPermiso]) ? "checked" : "";

                                // Agregar el checkswitch para el submenú
                                $jTableResult['listaMenu'] .= "
                                <li class='list-group-item d-flex align-items-center justify-content-between'>
                                    <span>{$nombreSub}</span>
                                    <div class='form-check form-switch m-0'>
                                        <input class='form-check-input permiso-switch' type='checkbox'
                                            id='{$switchId}'
                                            name='perm[{$idRol}][{$idMenu}][]'
                                            value='{$idSub}'
                                            data-id-rol='{$idRol}'
                                            data-id-menu='{$idMenu}'
                                            data-id-submenu='{$idSub}'
                                            data-nombre-menu='{$nombreMenu}'
                                            data-nombre-submenu='{$nombreSub}' {$checked}>
                                        <label class='form-check-label' for='{$switchId}'>Permitir</label>
                                    </div>
                                </li>";
                            }
                            $jTableResult['listaMenu'] .= "</ul>";
                        }else{
                            $jTableResult['listaMenu'] .= "<div class='text-muted small px-3 py-2'>No hay submenús.</div>";
                        }
                        mysqli_free_result($resultSub);
                    }
                    // Cerrar el acordeón
                    $jTableResult['listaMenu'] .= "
                            </div>
                        </div>
                    </div>";
                }
                mysqli_free_result($result);
            }
            $jTableResult['listaMenu'] .= "</div>";

            print json_encode($jTableResult);
         break;

         case 'guardarPermiso':
            $jTableResult = array();
            $jTableResult['success'] = false;
            $jTableResult['mensaje'] = "";

            // Obtener parámetros de la solicitud
            $idRol = isset($_REQUEST['id_rol']) ? (int)$_REQUEST['id_rol'] : 0;
            $idMenu = isset($_REQUEST['id_menu']) ? (int)$_REQUEST['id_menu'] : 0;
            $idSubmenu = isset($_REQUEST['id_submenu']) ? (int)$_REQUEST['id_submenu'] : 0;
            $activar = isset($_REQUEST['activar']) ? $_REQUEST['activar'] === 'true' : false;

            if($idRol > 0 && $idMenu > 0 && $idSubmenu > 0){
                if($activar){
                    // Verificar si ya existe el permiso
                    $queryCheck = "SELECT id_permisos FROM permisos WHERE id_rol = $idRol AND id_menu = $idMenu AND id_submenu = $idSubmenu";
                    $resultCheck = mysqli_query($conn, $queryCheck);
                    
                    if(mysqli_num_rows($resultCheck) == 0){
                        // Insertar nuevo permiso
                        $queryInsert = "INSERT INTO permisos (id_rol, id_menu, id_submenu) VALUES ($idRol, $idMenu, $idSubmenu)";
                        if(mysqli_query($conn, $queryInsert)){
                            $jTableResult['success'] = true;
                        }else{
                            $jTableResult['mensaje'] = "Error al guardar el permiso";
                        }
                    }else{
                        $jTableResult['success'] = true;
                    }
                }else{
                    // Eliminar permiso
                    $queryDelete = "DELETE FROM permisos WHERE id_rol = $idRol AND id_menu = $idMenu AND id_submenu = $idSubmenu";
                    if(mysqli_query($conn, $queryDelete)){
                        $jTableResult['success'] = true;
                    }else{
                        $jTableResult['mensaje'] = "Error al eliminar el permiso";
                    }
                }
            }else{
                $jTableResult['mensaje'] = "Datos incompletos";
            }

            print json_encode($jTableResult);
         break;
    }
    
    mysqli_close($conn);
?>