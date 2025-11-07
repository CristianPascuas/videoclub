<?php
// Verificar que existe una sesión activa
if (!isset($_SESSION['usuario_rol'])) {
    session_start();
}

$rolUsuario = isset($_SESSION['usuario_rol']) ? (int)$_SESSION['usuario_rol'] : 0;

if ($rolUsuario > 0) {
    // Obtener solo los menús y submenús permitidos para este rol
    $SQL = "SELECT DISTINCT m.Id_Menu, m.Nombre_Menu, m.Orden_Menu 
            FROM menu m 
            INNER JOIN permisos p ON m.Id_Menu = p.id_menu 
            WHERE p.id_rol = $rolUsuario 
            ORDER BY m.Orden_Menu ASC";
    
    $resultado = mysqli_query($conexion, $SQL);

    while($menu = mysqli_fetch_assoc($resultado)){
        $idMenu = (int)$menu['Id_Menu'];
        $nombreMenu = htmlspecialchars($menu['Nombre_Menu']);

        $collapseId = "collapse".$idMenu;
        $headingId  = "heading".$idMenu;

        // Obtener solo los submenús permitidos para este rol y menú
        $SQL_sub = "SELECT s.Id_Submenu, s.Nombre_Submenu, s.Orden_Submenu 
                    FROM submenu s 
                    INNER JOIN permisos p ON s.Id_Submenu = p.id_submenu AND s.Id_Menu = p.id_menu
                    WHERE s.Id_Menu = $idMenu AND p.id_rol = $rolUsuario 
                    ORDER BY s.Orden_Submenu ASC";
        
        $resultado_sub = mysqli_query($conexion, $SQL_sub);
        $tieneSub = mysqli_num_rows($resultado_sub) > 0;

        // Solo mostrar el menú si tiene submenús permitidos
        if ($tieneSub) {
            echo '<div class="accordion-item">';
            echo '  <h2 class="accordion-header" id="'.$headingId.'">';
            
            if($tieneSub){
                echo '  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#'.$collapseId.'" aria-expanded="false" aria-controls="'.$collapseId.'">'.$nombreMenu.'</button>';
            }
            
            echo '  </h2>';

            echo '<div id="'.$collapseId.'" class="accordion-collapse collapse" aria-labelledby="'.$headingId.'" data-bs-parent="#menuAccordion">';
            echo '  <div class="accordion-body p-0">';
            echo '      <ul class="list-group list-group-flush submenu-list">';
            
            while($submenu = mysqli_fetch_assoc($resultado_sub)){
                $idSub = (int)$submenu['Id_Submenu'];
                $nombreSub = htmlspecialchars($submenu['Nombre_Submenu']);
                
                if($idMenu === 1 && $idSub === 1){
                    echo '<li class="list-group-item"><a href="#" id="verRoles" data-bs-toggle="modal" data-bs-target="#permisos">'.$nombreSub.'</a></li>';
                }elseif($idMenu === 1 && $idSub === 2){
                    echo '<li class="list-group-item"><a href="validacion.php">'.$nombreSub.'</a></li>';
                }elseif($idMenu === 2 && $idSub === 3){
                    echo '<li class="list-group-item"><a href="ingresos.php">'.$nombreSub.'</a></li>';
                }elseif($idMenu === 4 && $idSub === 4){
                    echo '<li class="list-group-item"><a href="rentas.php">'.$nombreSub.'</a></li>';
                }elseif($idMenu === 4 && $idSub === 5){
                    echo '<li class="list-group-item"><a href="deudas.php">'.$nombreSub.'</a></li>';
                }elseif($idMenu === 5 && $idSub === 6){
                    echo '<li class="list-group-item"><a href="clientes.php">'.$nombreSub.'</a></li>';
                }elseif($idMenu === 6 && $idSub === 7){
                    echo '<li class="list-group-item"><a href="staff.php">'.$nombreSub.'</a></li>';
                }elseif($idMenu === 7 && $idSub === 8){
                    echo '<li class="list-group-item"><a href="inventario.php">'.$nombreSub.'</a></li>';
                }elseif($idMenu === 7 && $idSub === 9){
                    echo '<li class="list-group-item"><a href="admin_inventario.php">'.$nombreSub.'</a></li>';
                }elseif($idMenu === 3 && $idSub === 10){
                    echo '<li class="list-group-item"><a href="peliculas.php">'.$nombreSub.'</a></li>';
                }else{
                    echo '<li class="list-group-item"><a href="#">'.$nombreSub.'</a></li>';
                }
            }
            
            echo '      </ul>';
            echo '  </div>';
            echo '</div>';
            echo '</div>';
        }
    }
} else {
    // Si no hay rol asignado o no está logueado, no mostrar nada o redirigir
    echo '<div class="accordion-item">';
    echo '  <div class="text-muted small px-3 py-2">No tienes permisos asignados.</div>';
    echo '</div>';
}
?>