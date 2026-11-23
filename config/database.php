<?php
/**
 * Clase de conexión a la base de datos usando PDO.
 *
 * LOCAL (XAMPP): deja los valores como están.
 * REMOTO (InfinityFree): cambia las 4 variables privadas por las credenciales
 * que te da el panel (MySQL Databases) antes de subir el proyecto.
 */
class Database
{
    private string $host    = 'localhost';
    private string $dbname  = 'db_libreria';
    private string $user    = 'root';
    private string $pass    = '';
    private string $charset = 'utf8mb4';

    private ?PDO $conn = null;

    /** Devuelve una instancia de PDO (lanza PDOException si falla). */
    public function getConnection(): PDO
    {
        if ($this->conn === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            $this->conn = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return $this->conn;
    }
}
