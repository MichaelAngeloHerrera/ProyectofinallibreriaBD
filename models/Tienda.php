<?php
/**
 * Modelo Tienda: encapsula la lógica de negocio y el acceso a la tabla `tiendas`.
 * Todas las consultas usan sentencias preparadas (sin concatenar variables en el SQL).
 */
class Tienda
{
    private PDO $db;

    // Propiedades privadas (una por columna)
    private $id_tienda;
    private $nombre_tienda;
    private $direcc_tienda;
    private $ciudad;
    private $estado;
    private $codigo_postal;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // ---------- Getters ----------
    public function getIdTienda()     { return $this->id_tienda; }
    public function getNombreTienda() { return $this->nombre_tienda; }
    public function getDireccTienda() { return $this->direcc_tienda; }
    public function getCiudad()       { return $this->ciudad; }
    public function getEstado()       { return $this->estado; }
    public function getCodigoPostal() { return $this->codigo_postal; }

    // ---------- Lógica interna ----------

    /** Carga y limpia los datos recibidos (por ejemplo $_POST) en las propiedades. */
    private function cargar(array $d): void
    {
        $this->id_tienda     = trim((string)($d['id_tienda'] ?? ''));
        $this->nombre_tienda = trim((string)($d['nombre_tienda'] ?? ''));
        $this->direcc_tienda = trim((string)($d['direcc_tienda'] ?? ''));
        $this->ciudad        = trim((string)($d['ciudad'] ?? ''));
        $this->estado        = strtoupper(trim((string)($d['estado'] ?? '')));
        $this->codigo_postal = trim((string)($d['codigo_postal'] ?? ''));
    }

    /** Valida las propiedades cargadas. Lanza InvalidArgumentException con los errores. */
    private function validar(bool $esNueva): void
    {
        $errores = [];

        if ($esNueva && !ctype_digit($this->id_tienda)) {
            $errores[] = 'El ID de la tienda debe ser un número entero positivo.';
        }
        if ($this->nombre_tienda === '') {
            $errores[] = 'El nombre de la tienda no puede estar vacío.';
        } elseif (mb_strlen($this->nombre_tienda) > 60) {
            $errores[] = 'El nombre de la tienda no puede superar 60 caracteres.';
        }
        if (mb_strlen($this->direcc_tienda) > 60) {
            $errores[] = 'La dirección no puede superar 60 caracteres.';
        }
        if (mb_strlen($this->ciudad) > 40) {
            $errores[] = 'La ciudad no puede superar 40 caracteres.';
        }
        if (mb_strlen($this->estado) > 2) {
            $errores[] = 'El estado debe tener máximo 2 letras (ej. CA).';
        }
        if (mb_strlen($this->codigo_postal) > 10) {
            $errores[] = 'El código postal no puede superar 10 caracteres.';
        }

        if ($errores) {
            throw new InvalidArgumentException(implode(' ', $errores));
        }
    }

    // ---------- CRUD ----------

    /** READ: todas las tiendas. */
    public function getAll(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM tiendas ORDER BY id_tienda");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** READ: una tienda por ID (o false si no existe). */
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tiendas WHERE id_tienda = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /** Cantidad total de tiendas (para el dashboard). */
    public function contar(): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM tiendas");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /** CREATE */
    public function create(array $datos): bool
    {
        $this->cargar($datos);
        $this->validar(true);

        $sql = "INSERT INTO tiendas (id_tienda, nombre_tienda, direcc_tienda, ciudad, estado, codigo_postal)
                VALUES (:id, :nombre, :direcc, :ciudad, :estado, :cp)";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id'     => (int)$this->id_tienda,
                ':nombre' => $this->nombre_tienda,
                ':direcc' => $this->direcc_tienda,
                ':ciudad' => $this->ciudad,
                ':estado' => $this->estado,
                ':cp'     => $this->codigo_postal,
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new RuntimeException('Ya existe una tienda con ese ID.');
            }
            throw $e;
        }
    }

    /** UPDATE */
    public function update($id, array $datos): bool
    {
        $datos['id_tienda'] = (string)$id;
        $this->cargar($datos);
        $this->validar(false);

        $sql = "UPDATE tiendas
                SET nombre_tienda = :nombre, direcc_tienda = :direcc, ciudad = :ciudad,
                    estado = :estado, codigo_postal = :cp
                WHERE id_tienda = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre' => $this->nombre_tienda,
            ':direcc' => $this->direcc_tienda,
            ':ciudad' => $this->ciudad,
            ':estado' => $this->estado,
            ':cp'     => $this->codigo_postal,
            ':id'     => (int)$id,
        ]);
    }

    /** DELETE (falla con un mensaje claro si la tienda tiene ventas asociadas). */
    public function delete($id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM tiendas WHERE id_tienda = :id");
            return $stmt->execute([':id' => (int)$id]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new RuntimeException('No se puede eliminar: la tienda tiene ventas o descuentos asociados.');
            }
            throw $e;
        }
    }
}
