<?php
/**
 * Modelo Titulo (libro): lógica de negocio y acceso a la tabla `titulos`.
 * Todas las consultas usan sentencias preparadas.
 */
class Titulo
{
    private PDO $db;

    // Propiedades privadas
    private $id_titulo;
    private $titulo;
    private $tipo;
    private $id_pub;
    private $precio;
    private $notas;
    private $fecha_pub;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // ---------- Getters ----------
    public function getIdTitulo() { return $this->id_titulo; }
    public function getTitulo()   { return $this->titulo; }
    public function getTipo()     { return $this->tipo; }
    public function getIdPub()    { return $this->id_pub; }
    public function getPrecio()   { return $this->precio; }
    public function getNotas()    { return $this->notas; }
    public function getFechaPub() { return $this->fecha_pub; }

    // ---------- Lógica interna ----------

    private function cargar(array $d): void
    {
        $this->id_titulo = strtoupper(trim((string)($d['id_titulo'] ?? '')));
        $this->titulo    = trim((string)($d['titulo'] ?? ''));
        $this->tipo      = trim((string)($d['tipo'] ?? ''));
        $this->id_pub    = trim((string)($d['id_pub'] ?? ''));
        $this->precio    = trim((string)($d['precio'] ?? ''));
        $this->notas     = trim((string)($d['notas'] ?? ''));
        $this->fecha_pub = trim((string)($d['fecha_pub'] ?? ''));
    }

    private function existePublicador($id): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM publicadores WHERE id_pub = :id");
        $stmt->execute([':id' => $id]);
        return (int)$stmt->fetchColumn() > 0;
    }

    /** Valida las propiedades cargadas. Lanza InvalidArgumentException con los errores. */
    private function validar(bool $esNuevo): void
    {
        $errores = [];

        if ($esNuevo && !preg_match('/^[A-Z0-9]{1,6}$/', $this->id_titulo)) {
            $errores[] = 'El ID del título es obligatorio: máximo 6 letras o números (ej. BU1032).';
        }
        if ($this->titulo === '') {
            $errores[] = 'El título no puede estar vacío.';
        } elseif (mb_strlen($this->titulo) > 100) {
            $errores[] = 'El título no puede superar 100 caracteres.';
        }
        if (mb_strlen($this->tipo) > 20) {
            $errores[] = 'El tipo no puede superar 20 caracteres.';
        }
        if ($this->precio === '' || !is_numeric($this->precio)) {
            $errores[] = 'El precio es obligatorio y debe ser un número.';
        } elseif ((float)$this->precio < 0) {
            $errores[] = 'El precio no puede ser negativo.';
        } elseif ((float)$this->precio > 999999.99) {
            $errores[] = 'El precio es demasiado alto.';
        }
        if ($this->id_pub === '' || !ctype_digit($this->id_pub) || !$this->existePublicador((int)$this->id_pub)) {
            $errores[] = 'Debes seleccionar una editorial válida.';
        }
        if ($this->fecha_pub !== '') {
            $f = DateTime::createFromFormat('Y-m-d', $this->fecha_pub);
            if (!$f || $f->format('Y-m-d') !== $this->fecha_pub) {
                $errores[] = 'La fecha de publicación no es válida.';
            }
        }
        if (mb_strlen($this->notas) > 200) {
            $errores[] = 'Las notas no pueden superar 200 caracteres.';
        }

        if ($errores) {
            throw new InvalidArgumentException(implode(' ', $errores));
        }
    }

    // ---------- Editoriales (para el <select>) ----------

    /** Carga las editoriales desde la tabla publicadores. */
    public function getPublicadores(): array
    {
        $stmt = $this->db->prepare("SELECT id_pub, nombre_pub FROM publicadores ORDER BY nombre_pub");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ---------- CRUD ----------

    /** READ: todos los títulos con el nombre de su editorial. */
    public function getAll(): array
    {
        $sql = "SELECT t.id_titulo, t.titulo, t.tipo, t.id_pub, t.precio, t.notas, t.fecha_pub,
                       p.nombre_pub
                FROM titulos t
                LEFT JOIN publicadores p ON p.id_pub = t.id_pub
                ORDER BY t.titulo";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** READ: un título por ID (o false si no existe). */
    public function getById($id)
    {
        $sql = "SELECT t.*, p.nombre_pub
                FROM titulos t
                LEFT JOIN publicadores p ON p.id_pub = t.id_pub
                WHERE t.id_titulo = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /** Cantidad total de títulos (para el dashboard). */
    public function contar(): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM titulos");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /** CREATE */
    public function create(array $datos): bool
    {
        $this->cargar($datos);
        $this->validar(true);

        $sql = "INSERT INTO titulos (id_titulo, titulo, tipo, id_pub, precio, notas, fecha_pub)
                VALUES (:id, :titulo, :tipo, :pub, :precio, :notas, :fecha)";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id'     => $this->id_titulo,
                ':titulo' => $this->titulo,
                ':tipo'   => $this->tipo !== '' ? $this->tipo : null,
                ':pub'    => (int)$this->id_pub,
                ':precio' => $this->precio,
                ':notas'  => $this->notas !== '' ? $this->notas : null,
                ':fecha'  => $this->fecha_pub !== '' ? $this->fecha_pub : null,
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new RuntimeException('Ya existe un título con ese ID.');
            }
            throw $e;
        }
    }

    /** UPDATE */
    public function update($id, array $datos): bool
    {
        $datos['id_titulo'] = (string)$id;
        $this->cargar($datos);
        $this->validar(false);

        $sql = "UPDATE titulos
                SET titulo = :titulo, tipo = :tipo, id_pub = :pub, precio = :precio,
                    notas = :notas, fecha_pub = :fecha
                WHERE id_titulo = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titulo' => $this->titulo,
            ':tipo'   => $this->tipo !== '' ? $this->tipo : null,
            ':pub'    => (int)$this->id_pub,
            ':precio' => $this->precio,
            ':notas'  => $this->notas !== '' ? $this->notas : null,
            ':fecha'  => $this->fecha_pub !== '' ? $this->fecha_pub : null,
            ':id'     => $id,
        ]);
    }

    /** DELETE (falla con un mensaje claro si el título tiene ventas o autores asociados). */
    public function delete($id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM titulos WHERE id_titulo = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new RuntimeException('No se puede eliminar: el título tiene ventas, autores o derechos asociados.');
            }
            throw $e;
        }
    }
}
