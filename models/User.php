<?php
class User
{
    private $conn;
    private $table = "usuarios";  // Nombre de la tabla de usuarios

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Crear un nuevo usuario
    public function create($nombre, $cedula, $actividad, $zona, $rol_id = 2)
    {
        $sql = "INSERT INTO {$this->table} (nombre, cedula, actividad, zona, rol_id)
                VALUES (:nombre, :cedula, :actividad, :zona, :rol_id)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":cedula", $cedula);
        $stmt->bindParam(":actividad", $actividad);
        $stmt->bindParam(":zona", $zona);
        $stmt->bindParam(":rol_id", $rol_id);

        return $stmt->execute();
    }

    // Obtener todos los usuarios
    public function getAllUsers()
    {
        $sql = "SELECT u.*, (SELECT nombre FROM zonas z WHERE z.id = u.zona) AS zona_nombre, (SELECT numero FROM plazas p WHERE p.id = u.plaza) AS plaza_numero FROM {$this->table} AS u";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        // Array para almacenar los resultados
        $users = [];

        // Obtener los resultados
        $result = $stmt->get_result();
        while ($user = $result->fetch_assoc()) {
            $users[] = $user;
        }

        return $users;
    }
}
