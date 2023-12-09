<?php



/**
 * Clase TaskDatabase para interactuar con la base de datos de tareas.
 */
class TaskDatabase
{
    /**
     * @var TaskDatabase|null Instancia única de la clase.
     */
    static private $instance = null;

    /**
     * @var PDO|null Conexión a la base de datos.
     */
    private $connection = null;

    /**
     * Constructor privado para evitar instanciación directa.
     */
    private function __construct()
    {
        // Establecer la conexión a la base de datos.
        try {
            $this->connection = new PDO("mysql:host=localhost;dbname=bungle_db", "root", "") or die();
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    /**
     * Obtener la instancia única de la clase TaskDatabase.
     *
     * @return TaskDatabase Instancia única de la clase.
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Impedir la clonación de la instancia.
     */
    private function __clone()
    {
    }

    /**
     * Cerrar la conexión y resetear la instancia.
     */
    public function close()
    {
        $this->connection = null;
        self::$instance = null;
    }

    /**
     * Ejecutar una consulta personalizada y obtener los resultados.
     *
     * @param string $sql Consulta SQL.
     * @return array Resultados de la consulta.
     */
    public function customQuery($sql)
    {
        $rs = $this->connection->query($sql);
        return $rs->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener el número de filas afectadas por una consulta.
     *
     * @param string $sql Consulta SQL.
     * @return int Número de filas afectadas.
     */
    public function numRows($sql)
    {
        return $this->connection->query($sql)->fetch(PDO::FETCH_ASSOC)['num'];
    }

    /**
     * Ejecutar una consulta que no devuelve resultados (INSERT, UPDATE, DELETE, etc.).
     *
     * @param string $sql Consulta SQL.
     */
    public function exec($sql)
    {
        $this->connection->query($sql);
    }

    /**
     * Preparar y ejecutar una consulta preparada con parámetros.
     *
     * @param string $sql Consulta SQL preparada.
     * @param array $params Parámetros para la consulta.
     */
    public function prep($sql, $params)
    {
        $operation = $this->connection->prepare($sql);
        $operation->execute($params);
    }
}
