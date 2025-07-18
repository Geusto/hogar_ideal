<?php
require_once 'config/database.php';

class Agente {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    // Obtener todos los agentes
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM agente ORDER BY nombre_completo");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener un agente por ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM agente WHERE id_agente = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Crear nuevo agente
    public function create($data) {
        $sql = "INSERT INTO agente (nombre_completo, email, telefono, especialidad) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre_completo'],
            $data['email'],
            $data['telefono'],
            $data['especialidad']
        ]);
    }
    
    // Actualizar agente
    public function update($id, $data) {
        $sql = "UPDATE agente SET nombre_completo = ?, email = ?, telefono = ?, especialidad = ? WHERE id_agente = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre_completo'],
            $data['email'],
            $data['telefono'],
            $data['especialidad'],
            $id
        ]);
    }
    
    // Eliminar agente
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM agente WHERE id_agente = ?");
        return $stmt->execute([$id]);
    }
    
    // Contar total de agentes
    public function count() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM agente");
        return $stmt->fetch()['total'];
    }
    
    // Contar agentes por especialidad
    public function countByEspecialidad($especialidad) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM agente WHERE especialidad = ?");
        $stmt->execute([$especialidad]);
        return $stmt->fetch()['total'];
    }
    
    // Buscar agentes
    public function search($query) {
        $sql = "SELECT * FROM agente WHERE nombre_completo LIKE ? OR email LIKE ? OR especialidad LIKE ? ORDER BY nombre_completo";
        $searchTerm = "%$query%";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 