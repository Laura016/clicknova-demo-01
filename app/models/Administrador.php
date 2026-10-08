<?php

require_once __DIR__ . "/../../config/database.php";

class Administrador
{
    private $conexion;

    public function __construct()
    {
        $database = new Database();
        $this->conexion = $database->conectar();
    }

    public function obtenerPorUsuario($usuario)
    {
        $sql = "SELECT *
                FROM administradores
                WHERE usuario = :usuario
                AND estado = 1
                LIMIT 1";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(
            ":usuario",
            $usuario,
            PDO::PARAM_STR
        );

        $stmt->execute();

        return $stmt->fetch();
    }
}