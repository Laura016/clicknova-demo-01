<?php

class Database
{
    private $host = "sql206.infinityfree.com";
    private $db = "if0_42944935_catalogo_calzasport";
    private $user = "if0_42944935";
    private $password = "Calzasport2026";

    public function conectar()
    {
        try {

            $conexion = new PDO(
                "mysql:host={$this->host};dbname={$this->db};charset=utf8mb4",
                $this->user,
                $this->password
            );

            $conexion->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            $conexion->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );

            return $conexion;

        } catch (PDOException $e) {

            die(
                "Error de conexión a la base de datos: "
                . $e->getMessage()
            );
        }
    }
}