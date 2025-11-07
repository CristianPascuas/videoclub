<?php

namespace VideoClub\Database;

class Connection {
    public static function connect() {
        $servername = "localhost";
        $db = "videoclub";
        $username = "sena123";
        $password = $_ENV["DB_PASSWORD"];
        $conEctar = mysqli_connect($servername, $username, $password, $db);
        if (!$conEctar) {
            die("Error de Conexion: " . mysqli_connect_error());
        }
        return $conEctar;
    }
}

