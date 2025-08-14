<?php
require_once 'config/database.php';

class FotoPropiedad {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    // Obtener todas las fotos de una propiedad
    public function getByPropiedad($idPropiedad) {
        $stmt = $this->pdo->prepare("SELECT * FROM fotos_propiedad WHERE id_propiedad = ? ORDER BY orden ASC, id_foto ASC");
        $stmt->execute([$idPropiedad]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener la foto de portada de una propiedad
    public function getPortada($idPropiedad) {
        $stmt = $this->pdo->prepare("SELECT * FROM fotos_propiedad WHERE id_propiedad = ? AND es_portada = 1 LIMIT 1");
        $stmt->execute([$idPropiedad]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Obtener una foto específica por ID
    public function getById($idFoto) {
        $stmt = $this->pdo->prepare("SELECT * FROM fotos_propiedad WHERE id_foto = ?");
        $stmt->execute([$idFoto]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Crear nueva foto
    public function create($data) {
        $sql = "INSERT INTO fotos_propiedad (id_propiedad, nombre_archivo, descripcion, orden, es_portada) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['id_propiedad'],
            $data['nombre_archivo'],
            $data['descripcion'] ?? '',
            $data['orden'] ?? 0,
            $data['es_portada'] ?? 0
        ]);
    }
    
    // Actualizar foto
    public function update($idFoto, $data) {
        $sql = "UPDATE fotos_propiedad SET 
                nombre_archivo = ?, descripcion = ?, orden = ?, es_portada = ? 
                WHERE id_foto = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre_archivo'],
            $data['descripcion'] ?? '',
            $data['orden'] ?? 0,
            $data['es_portada'] ?? 0,
            $idFoto
        ]);
    }
    
    // Eliminar foto
    public function delete($idFoto) {
        $stmt = $this->pdo->prepare("DELETE FROM fotos_propiedad WHERE id_foto = ?");
        return $stmt->execute([$idFoto]);
    }
    
    // Eliminar todas las fotos de una propiedad
    public function deleteByPropiedad($idPropiedad) {
        $stmt = $this->pdo->prepare("DELETE FROM fotos_propiedad WHERE id_propiedad = ?");
        return $stmt->execute([$idPropiedad]);
    }
    
    // Cambiar foto de portada
    public function setPortada($idFoto, $idPropiedad) {
        // Primero quitar portada de todas las fotos de esta propiedad
        $stmt = $this->pdo->prepare("UPDATE fotos_propiedad SET es_portada = 0 WHERE id_propiedad = ?");
        $stmt->execute([$idPropiedad]);
        
        // Luego establecer la nueva portada
        $stmt = $this->pdo->prepare("UPDATE fotos_propiedad SET es_portada = 1 WHERE id_foto = ?");
        return $stmt->execute([$idFoto]);
    }
    
    // Quitar portada de una foto específica
    public function quitarPortada($idFoto, $idPropiedad) {
        $stmt = $this->pdo->prepare("UPDATE fotos_propiedad SET es_portada = 0 WHERE id_foto = ? AND id_propiedad = ?");
        return $stmt->execute([$idFoto, $idPropiedad]);
    }
    
    // Reordenar fotos
    public function reordenar($fotos) {
        foreach ($fotos as $orden => $idFoto) {
            $stmt = $this->pdo->prepare("UPDATE fotos_propiedad SET orden = ? WHERE id_foto = ?");
            $stmt->execute([$orden + 1, $idFoto]);
        }
        return true;
    }
    
    // Contar fotos de una propiedad
    public function countByPropiedad($idPropiedad) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM fotos_propiedad WHERE id_propiedad = ?");
        $stmt->execute([$idPropiedad]);
        return $stmt->fetch()['total'];
    }
}
?>
