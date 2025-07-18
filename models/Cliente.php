<?php
require_once 'config/database.php';

class Cliente {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    // Obtener todos los clientes
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM cliente ORDER BY nombre_completo");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener un cliente por ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM cliente WHERE id_cliente = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Crear nuevo cliente
    public function create($data) {
        $sql = "INSERT INTO cliente (nombre_completo, email, telefono, tipo) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre_completo'],
            $data['email'],
            $data['telefono'],
            $data['tipo']
        ]);
    }
    
    // Actualizar cliente
    public function update($id, $data) {
        $sql = "UPDATE cliente SET nombre_completo = ?, email = ?, telefono = ?, tipo = ? WHERE id_cliente = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre_completo'],
            $data['email'],
            $data['telefono'],
            $data['tipo'],
            $id
        ]);
    }
    
    // Eliminar cliente
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM cliente WHERE id_cliente = ?");
        return $stmt->execute([$id]);
    }
    
    // Contar total de clientes
    public function count() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM cliente");
        return $stmt->fetch()['total'];
    }
    
    // Contar clientes por tipo
    public function countByTipo($tipo) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM cliente WHERE tipo = ?");
        $stmt->execute([$tipo]);
        return $stmt->fetch()['total'];
    }
    
    // Buscar clientes
    public function search($query) {
        $sql = "SELECT * FROM cliente WHERE nombre_completo LIKE ? OR email LIKE ? ORDER BY nombre_completo";
        $searchTerm = "%$query%";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 