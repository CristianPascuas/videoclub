<?php

namespace VideoClub\Controlador;

use VideoClub\Config\Configuration;
use VideoClub\Database\Connection;

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

session_name(Configuration::SESSION_NAME);
session_start();
$conn = Connection::connect();

switch ($_REQUEST['action']) {
    case 'listarPeliculas':
        $jTableResult = array();
        $jTableResult['success'] = false;
        $jTableResult['peliculas'] = "";
        
        $query = "SELECT p.Id_Peliculas, p.Titulo, p.Director, p.AñoLanzamiento, p.Stock, d.Distribuidora as Nombre_Distribuidora
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
                            <td><strong>'.$pelicula['Titulo'].'</strong></td>
                            <td>'.$pelicula['Director'].'</td>
                            <td>'.$pelicula['AñoLanzamiento'].'</td>
                            <td><span class="'.$stockClass.'">'.$stockText.'</span></td>
                            <td>'.$pelicula['Nombre_Distribuidora'].'</td>
                            <td>
                                <button class="btn btn-primary btn-sm me-1" onclick="comprarPelicula('.$pelicula['Id_Peliculas'].', \''.$pelicula['Titulo'].'\', '.$pelicula['Stock'].')"
                                        '.($pelicula['Stock'] > 0 ? '' : 'disabled').'>
                                    <i class="fas fa-shopping-cart"></i> Comprar
                                </button>
                                <button class="btn btn-info btn-sm" onclick="verDetalle('.$pelicula['Id_Peliculas'].')" data-bs-toggle="modal" data-bs-target="#modalDetallePelicula">
                                    <i class="fas fa-eye"></i> Ver
                                </button>
                            </td>
                          </tr>';
            }
            
            $tabla .= '</tbody></table></div>';
            
            $jTableResult['success'] = true;
            $jTableResult['peliculas'] = $tabla;
        } else {
            $jTableResult['peliculas'] = '<div class="alert alert-info">No hay películas disponibles en el inventario.</div>';
        }
        
        echo json_encode($jTableResult);
        exit();
    break;
    
    case 'buscarPeliculas':
        $jTableResult = array();
        $jTableResult['success'] = false;
        $jTableResult['peliculas'] = "";
        
        $busqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';
        $busquedaParam = '%' . $busqueda . '%';
        
        $query = "SELECT p.Id_Peliculas, p.Titulo, p.Director, p.AñoLanzamiento, p.Stock, d.Distribuidora as Nombre_Distribuidora
                  FROM peliculas p
                  LEFT JOIN distribuidora d ON p.Id_Distribuidora = d.Id_Distribuidora
                  WHERE p.Titulo LIKE ?
                  OR p.Director LIKE ?
                  OR p.AñoLanzamiento LIKE ?
                  OR d.Distribuidora LIKE ?
                  ORDER BY p.Titulo ASC";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ssss', $busquedaParam, $busquedaParam, $busquedaParam, $busquedaParam);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        
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
                            <td><strong>'.$pelicula['Titulo'].'</strong></td>
                            <td>'.$pelicula['Director'].'</td>
                            <td>'.$pelicula['AñoLanzamiento'].'</td>
                            <td><span class="'.$stockClass.'">'.$stockText.'</span></td>
                            <td>'.$pelicula['Nombre_Distribuidora'].'</td>
                            <td>
                                <button class="btn btn-primary btn-sm me-1" onclick="comprarPelicula('.$pelicula['Id_Peliculas'].', \''.$pelicula['Titulo'].'\', '.$pelicula['Stock'].')"
                                        '.($pelicula['Stock'] > 0 ? '' : 'disabled').'>
                                    <i class="fas fa-shopping-cart"></i> Comprar
                                </button>
                                <button class="btn btn-info btn-sm" onclick="verDetalle('.$pelicula['Id_Peliculas'].')" data-bs-toggle="modal" data-bs-target="#modalDetallePelicula">
                                    <i class="fas fa-eye"></i> Ver
                                </button>
                            </td>
                          </tr>';
            }
            
            $tabla .= '</tbody></table></div>';
            
            $jTableResult['success'] = true;
            $jTableResult['peliculas'] = $tabla;
        } else {
            $jTableResult['peliculas'] = '<div class="alert alert-warning">No se encontraron películas que coincidan con la búsqueda.</div>';
        }
        
        echo json_encode($jTableResult);
        exit();
    break;
    
    case 'detallePelicula':
        $jTableResult = array();
        $jTableResult['success'] = false;
        $jTableResult['detalle'] = "";
        
        $idPelicula = isset($_POST['idPelicula']) ? (int)$_POST['idPelicula'] : 0;
        
        if($idPelicula > 0) {
            $query = "SELECT p.Id_Peliculas, p.Titulo, p.Director, p.AñoLanzamiento, p.Stock, d.Distribuidora as Nombre_Distribuidora
                      FROM peliculas p
                      LEFT JOIN distribuidora d ON p.Id_Distribuidora = d.Id_Distribuidora
                      WHERE p.Id_Peliculas = ?";
            
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'i', $idPelicula);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);
            
            if($resultado && mysqli_num_rows($resultado) > 0) {
                $pelicula = mysqli_fetch_array($resultado);
                
                $jTableResult['success'] = true;
                $jTableResult['detalle'] = array(
                    'id' => $pelicula['Id_Peliculas'],
                    'titulo' => $pelicula['Titulo'],
                    'director' => $pelicula['Director'],
                    'año' => $pelicula['AñoLanzamiento'],
                    'stock' => $pelicula['Stock'],
                    'distribuidora' => $pelicula['Nombre_Distribuidora']
                );
            } else {
                $jTableResult['mensaje'] = "No se encontró la película solicitada.";
            }
        } else {
            $jTableResult['mensaje'] = "ID de película inválido.";
        }
        
        echo json_encode($jTableResult);
        exit();
    break;
}

mysqli_close($conn);
