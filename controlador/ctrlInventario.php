<?php
require_once('../include/config.php'); 
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
include('../include/conex.php');
session_name($session_name);
session_start();
$conn = Conectarse();

switch ($_REQUEST['action']) {
    case 'listarInventario':
        $jTableResult = array();
        $jTableResult['success'] = false;
        $jTableResult['inventario'] = "";
        
        $query = "SELECT p.Id_Peliculas, p.Titulo, p.Director, p.AñoLanzamiento, p.Stock, 
                         d.Id_Distribuidora, d.Distribuidora as Nombre_Distribuidora 
                  FROM peliculas p 
                  LEFT JOIN distribuidora d ON p.Id_Distribuidora = d.Id_Distribuidora 
                  ORDER BY p.Titulo ASC";
        
        $resultado = mysqli_query($conn, $query);
        
        if($resultado && mysqli_num_rows($resultado) > 0) {
            $tabla = '<div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Título</th>
                                    <th>Director</th>
                                    <th>Año</th>
                                    <th>Stock</th>
                                    <th>Distribuidora</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>';
            
            while($pelicula = mysqli_fetch_array($resultado)) {
                $stockClass = $pelicula['Stock'] > 0 ? 'text-success' : 'text-danger';
                $stockText = $pelicula['Stock'] > 0 ? $pelicula['Stock'] : 'Agotado';
                
                
                $tabla .= '<tr>
                            <td><strong>'.htmlspecialchars($pelicula['Titulo'], ENT_QUOTES, 'UTF-8').'</strong></td>
                            <td>'.htmlspecialchars($pelicula['Director'], ENT_QUOTES, 'UTF-8').'</td>
                            <td>'.$pelicula['AñoLanzamiento'].'</td>
                            <td><span class="'.$stockClass.'">'.$stockText.'</span></td>
                            <td>'.htmlspecialchars($pelicula['Nombre_Distribuidora'], ENT_QUOTES, 'UTF-8').'</td>
                            <td>
                                <button class="btn btn-warning btn-sm me-1" onclick="editarPelicula('.$pelicula['Id_Peliculas'].', \''.addslashes($pelicula['Titulo']).'\', \''.addslashes($pelicula['Director']).'\', '.$pelicula['AñoLanzamiento'].', '.$pelicula['Stock'].', '.$pelicula['Id_Distribuidora'].')" title="Editar película">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarPelicula('.$pelicula['Id_Peliculas'].', \''.addslashes($pelicula['Titulo']).'\')" title="Eliminar película">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                          </tr>';
            }
            
            $tabla .= '</tbody></table></div>';
            
            $jTableResult['success'] = true;
            $jTableResult['inventario'] = $tabla;
        } else {
            $jTableResult['inventario'] = '<div class="alert alert-info">No hay películas en el inventario.</div>';
        }
        
        echo json_encode($jTableResult);
        exit();
    break;
    
    case 'buscarInventario':
        $jTableResult = array();
        $jTableResult['success'] = false;
        $jTableResult['inventario'] = "";
        
        $busqueda = isset($_POST['busqueda']) ? mysqli_real_escape_string($conn, $_POST['busqueda']) : '';
        
        $query = "SELECT p.Id_Peliculas, p.Titulo, p.Director, p.AñoLanzamiento, p.Stock, 
                         d.Id_Distribuidora, d.Distribuidora as Nombre_Distribuidora 
                  FROM peliculas p 
                  LEFT JOIN distribuidora d ON p.Id_Distribuidora = d.Id_Distribuidora 
                  WHERE p.Titulo LIKE '%".$busqueda."%' 
                     OR p.Director LIKE '%".$busqueda."%' 
                     OR p.AñoLanzamiento LIKE '%".$busqueda."%'
                     OR d.Distribuidora LIKE '%".$busqueda."%'
                  ORDER BY p.Titulo ASC";
        
        $resultado = mysqli_query($conn, $query);
        
        if($resultado && mysqli_num_rows($resultado) > 0) {
            $tabla = '<div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Título</th>
                                    <th>Director</th>
                                    <th>Año</th>
                                    <th>Stock</th>
                                    <th>Distribuidora</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>';
            
            while($pelicula = mysqli_fetch_array($resultado)) {
                $stockClass = $pelicula['Stock'] > 0 ? 'text-success' : 'text-danger';
                $stockText = $pelicula['Stock'] > 0 ? $pelicula['Stock'] : 'Agotado';

                //usar ent_quotes es una medida de seguridad que evita ataques XSS
                $tabla .= '<tr>
                            <td><strong>'.htmlspecialchars($pelicula['Titulo'], ENT_QUOTES, 'UTF-8').'</strong></td>
                            <td>'.htmlspecialchars($pelicula['Director'], ENT_QUOTES, 'UTF-8').'</td>
                            <td>'.$pelicula['AñoLanzamiento'].'</td>
                            <td><span class="'.$stockClass.'">'.$stockText.'</span></td>
                            <td>'.htmlspecialchars($pelicula['Nombre_Distribuidora'], ENT_QUOTES, 'UTF-8').'</td>
                            <td>
                                <button class="btn btn-warning btn-sm me-1" onclick="editarPelicula('.$pelicula['Id_Peliculas'].', \''.addslashes($pelicula['Titulo']).'\', \''.addslashes($pelicula['Director']).'\', '.$pelicula['AñoLanzamiento'].', '.$pelicula['Stock'].', '.$pelicula['Id_Distribuidora'].')" title="Editar película">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarPelicula('.$pelicula['Id_Peliculas'].', \''.addslashes($pelicula['Titulo']).'\')" title="Eliminar película">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                          </tr>';
            }
            
            $tabla .= '</tbody></table></div>';
            
            $jTableResult['success'] = true;
            $jTableResult['inventario'] = $tabla;
        } else {
            $jTableResult['inventario'] = '<div class="alert alert-warning">No se encontraron películas que coincidan con la búsqueda.</div>';
        }
        
        echo json_encode($jTableResult);
        exit();
    break;
    
    case 'listarDistribuidoras':
        $jTableResult = array();
        $jTableResult['success'] = false;
        $jTableResult['distribuidoras'] = array();
        
        $query = "SELECT Id_Distribuidora, Distribuidora FROM distribuidora ORDER BY Distribuidora ASC";
        $resultado = mysqli_query($conn, $query);
        
        if($resultado) {
            while($dist = mysqli_fetch_array($resultado)) {
                $jTableResult['distribuidoras'][] = array(
                    'id' => $dist['Id_Distribuidora'],
                    'nombre' => $dist['Distribuidora']
                );
            }
            $jTableResult['success'] = true;
        }
        
        echo json_encode($jTableResult);
        exit();
    break;
    
    case 'agregarPelicula':
        $jTableResult = array();
        $jTableResult['success'] = false;
        $jTableResult['mensaje'] = "";
        
        $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
        $director = mysqli_real_escape_string($conn, $_POST['director']);
        $anoLanzamiento = (int)$_POST['anoLanzamiento'];
        $stock = (int)$_POST['stock'];
        $distribuidoraId = (int)$_POST['distribuidora'];
        
        // Verificar que no exista ya la película
        $checkPelicula = "SELECT Id_Peliculas FROM peliculas WHERE Titulo = '$titulo' AND Director = '$director' AND AñoLanzamiento = $anoLanzamiento";
        $resultCheckPelicula = mysqli_query($conn, $checkPelicula);
        
        if(mysqli_num_rows($resultCheckPelicula) > 0) {
            $jTableResult['mensaje'] = "Ya existe una película con el mismo título, director y año";
            echo json_encode($jTableResult);
            exit();
        }
        
        // Insertar la película
        $query = "INSERT INTO peliculas (Id_Distribuidora, Titulo, Director, AñoLanzamiento, Stock) 
                  VALUES ($distribuidoraId, '$titulo', '$director', $anoLanzamiento, $stock)";
        
        if(mysqli_query($conn, $query)) {
            $jTableResult['success'] = true;
            $jTableResult['mensaje'] = "Película agregada exitosamente";
        } else {
            $jTableResult['mensaje'] = "Error al agregar la película: " . mysqli_error($conn);
        }
        
        echo json_encode($jTableResult);
        exit();
    break;
    
    case 'editarPelicula':
        $jTableResult = array();
        $jTableResult['success'] = false;
        $jTableResult['mensaje'] = "";
        
        $id = (int)$_POST['id'];
        $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
        $director = mysqli_real_escape_string($conn, $_POST['director']);
        $anoLanzamiento = (int)$_POST['anoLanzamiento'];
        $stock = (int)$_POST['stock'];
        $distribuidoraId = (int)$_POST['distribuidora'];
        
        // Verificar que no exista otra película con los mismos datos
        $checkPelicula = "SELECT Id_Peliculas FROM peliculas WHERE Titulo = '$titulo' AND Director = '$director' AND AñoLanzamiento = $anoLanzamiento AND Id_Peliculas != $id";
        $resultCheckPelicula = mysqli_query($conn, $checkPelicula);
        
        if(mysqli_num_rows($resultCheckPelicula) > 0) {
            $jTableResult['mensaje'] = "Ya existe otra película con el mismo título, director y año";
            echo json_encode($jTableResult);
            exit();
        }
        
        // Actualizar la película
        $query = "UPDATE peliculas SET 
                    Id_Distribuidora = $distribuidoraId,
                    Titulo = '$titulo',
                    Director = '$director',
                    AñoLanzamiento = $anoLanzamiento,
                    Stock = $stock
                  WHERE Id_Peliculas = $id";
        
        if(mysqli_query($conn, $query)) {
            $jTableResult['success'] = true;
            $jTableResult['mensaje'] = "Película actualizada exitosamente";
        } else {
            $jTableResult['mensaje'] = "Error al actualizar la película: " . mysqli_error($conn);
        }
        
        echo json_encode($jTableResult);
        exit();
    break;
    
    case 'eliminarPelicula':
        $jTableResult = array();
        $jTableResult['success'] = false;
        $jTableResult['mensaje'] = "";
        
        $id = (int)$_POST['id'];
        
        // Verificar si la película tiene rentas activas
        $checkRentas = "SELECT COUNT(*) as total FROM renta WHERE Id_Peliculas = $id AND Id_EstadoRenta = 2";
        $resultCheck = mysqli_query($conn, $checkRentas);
        $row = mysqli_fetch_array($resultCheck);
        
        if($row['total'] > 0) {
            $jTableResult['mensaje'] = "No se puede eliminar la película porque tiene rentas activas";
            echo json_encode($jTableResult);
            exit();
        }
        
        // Eliminar la película
        $query = "DELETE FROM peliculas WHERE Id_Peliculas = $id";
        
        if(mysqli_query($conn, $query)) {
            $jTableResult['success'] = true;
            $jTableResult['mensaje'] = "Película eliminada exitosamente";
        } else {
            $jTableResult['mensaje'] = "Error al eliminar la película: " . mysqli_error($conn);
        }
        
        echo json_encode($jTableResult);
        exit();
    break;
}

mysqli_close($conn);
?>