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
        $sql = "INSERT INTO agente (nombre_completo, email, telefono, zona_asignada, activo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre_completo'],
            $data['email'],
            $data['telefono'],
            $data['zona_asignada'],
            $data['activo']
        ]);
    }
    
    // Actualizar agente
    public function update($id, $data) {
        $sql = "UPDATE agente SET nombre_completo = ?, email = ?, telefono = ?, zona_asignada = ?, activo = ? WHERE id_agente = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre_completo'],
            $data['email'],
            $data['telefono'],
            $data['zona_asignada'],
            $data['activo'],
        ]);
    }
    
    // Eliminar agente
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM agente WHERE id_agente = ?");
        return $stmt->execute([$id]);
    }
    
    // Eliminar o inactivar agente según relaciones
    public function eliminarOInactivar($id) {
        // Verificar si el agente tiene propiedades asociadas
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM propiedad WHERE id_agente = ?");
        $stmt->execute([$id]);
        $total = $stmt->fetch()['total'];
        if ($total > 0) {
            // Marcar como inactivo
            $stmt = $this->pdo->prepare("UPDATE agente SET activo = 0 WHERE id_agente = ?");
            $stmt->execute([$id]);
            return 'inactivado';
        } else {
            // Eliminar físicamente
            $stmt = $this->pdo->prepare("DELETE FROM agente WHERE id_agente = ?");
            $stmt->execute([$id]);
            return 'eliminado';
        }
    }
    
    // Contar total de agentes
    public function count() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM agente");
        return $stmt->fetch()['total'];
    }
    
    // Contar agentes por zona asignada
    public function countByZonaAsignada($zona_asignada) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM agente WHERE zona_asignada = ?");
        $stmt->execute([$zona_asignada]);
        return $stmt->fetch()['total'];
    }
    
    // Buscar agentes
    public function search($query) {
        $sql = "SELECT * FROM agente WHERE nombre_completo LIKE ? OR email LIKE ? OR zona_asignada LIKE ? ORDER BY nombre_completo";
        $searchTerm = "%$query%";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 