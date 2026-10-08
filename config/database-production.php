<?php

class Database
{
    private $host = "sql110.infinityfree.com";
    private $db = "if0_43126128_auren_catalogo";
    private $user = "if0_43126128";
    private $password = "Auren2026";

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
            error_log("Error de conexión AUREN: " . $e->getMessage());
            die("No se pudo conectar con la base de datos. Intenta más tarde.");
        }
    }
}
