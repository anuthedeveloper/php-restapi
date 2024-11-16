<?php
// config/Database.php
namespace Config;

use InvalidArgumentException;
use PDO;
use PDOException;

class Database {
    private static ?PDO $connection = null;
    private static ?Database $instance = null;

    private $stmt;
    private array $parameters = [];

    private function __construct()
    {
        // Make the constructor private to prevent instantiation
    }

    public static function initialize(): void
    {
        // Load credentials from environment variables
        $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_CHARSET']}";
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASSWORD'];
        
        try {
            if (self::$connection === null) {
                self::$connection = new PDO($dsn, $user, $pass);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            } else {
                // error_log('Database connection already initialized.');
            }
        } catch (PDOException $e) {
              // Handle connection error
              error_log('Database Connection Error: ' . $e->getMessage());
              throw new \Exception("Database connection failed.");
        }
    }

    public static function getConnection(): PDO 
    {
        if (self::$connection === null) {
            throw new \Exception("Database not initialized. Call `Database::initialize()` first.");
        }
        return self::$connection;
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

   /**
    *	Wildcard method for all SQL query
    */	
    private function prepare(mixed $query, array $parameters = [])
    {
        try {
            # Prepare the statement
            $this->stmt = self::$connection->prepare($query);
            # Add parameters to the parameter array	
            $this->bindMore($parameters);
            # Bind parameters
            if(!empty($this->parameters)) {
                foreach($this->parameters as $param)
                {
                    $bindParams = explode("\x7F", $param);
                    $this->stmt->bindParam($bindParams[0], $bindParams[1]);
                }		
            }

            // Bind parameters directly
            // foreach ($parameters as $key => $value) {
            //     // Ensure parameter keys are prefixed with ':' if not already
            //     $paramKey = (str_starts_with($key, ':')) ? $key : ':' . $key;
            //     $this->stmt->bindValue($paramKey, $value);
            // }

            $this->stmt->execute();
        } catch(PDOException $e) {
            error_log("Query Exception: " . $e->getMessage() . " Query: " . $query);
            throw new \Exception("Error executing query: " . $e->getMessage());
        }

        $this->parameters = [];
    }
    
    /**
    * Add the parameter to the parameter array
    */	
    private function bind(string $param, string $value): void
    {
        $this->parameters[] = ":$param\x7F" . mb_convert_encoding($value, 'UTF-8');
    }

    private function bindMore(array $params = []): void
    {
        if(empty($this->parameters) && is_array($params)) {
            foreach ($params as $key => $value) {
                $this->bind($key, $value);
            }
        }
    }
        
    /**
     * Executes an SQL query and returns the result based on the query type.
     *
     * @param string $query The SQL query string.
     * @param array $params Parameters to bind to the query.
     * @param int $fetchMode The fetch mode for SELECT queries (default: PDO::FETCH_ASSOC).
     * @return mixed The result of the query:
     *               - For SELECT/SHOW: returns an array of results.
     *               - For INSERT/UPDATE/DELETE: returns the number of affected rows.
     *               - NULL for unsupported query types.
     * @throws InvalidArgumentException If the query type is not supported.    
     */			
    public function query(string $query, array $params = [], int $fetchMode = PDO::FETCH_ASSOC)
    {
        $this->prepare($query, $params);

        // Extract the SQL statement type (first word of the query).
        $queryType = strtolower(strtok(trim($query), " "));

        // Constants for different query types.
        $readQueries = ['select', 'show'];
        $writeQueries = ['insert', 'update', 'delete'];

        // Determine the action based on the query type.
        if (in_array($queryType, $readQueries, true)) {
            return $this->stmt->fetchAll($fetchMode);
        }

        if (in_array($queryType, $writeQueries, true)) {
            return $this->stmt->rowCount();
        }

        // Unsupported query type.
        throw new InvalidArgumentException("Unsupported query type: {$queryType}");
    }
    
    /**
     * $query = "SELECT * FROM table WHERE firstname = :firstname", array("firstname"=>"John","id"=>"1")
     */
    public function select(string $table, array $where = [], int $fetchMode = PDO::FETCH_OBJ): array
    {
        $whereClause = implode(' AND ', array_map(fn($field) => "$field = :$field", array_keys($where)));

        $sql = "SELECT * FROM {$table} WHERE {$whereClause}";
        $this->prepare($sql, $where);
        return $this->stmt->fetchAll($fetchMode);
    } 

    /**
     * Delete a row in the table
     * db::delete( 'table', array( $placeholder => 1 ) )
     */ 
    public function delete( string $table, array $where = [] ) 
    {
        $whereClause = implode(' AND ', array_map(fn($field) => "$field = :$field", array_keys($where)));

        $sql = "DELETE FROM {$table} WHERE {$whereClause}";
        $this->prepare($sql, $where);

        return $this->stmt->rowCount();
    }

    /**
     * Insert a row into a table.
     *
     *  db::insert('table', array('placeholder1' => 'foo', 'placeholder2' => 1337) )
     */
    public function insert( string $table, array $params = [] ) 
    {        
        $columns = implode(', ', array_keys($params));
        $placeholders = ':' . implode(', :', array_keys($params));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $this->prepare($sql, $params);

        return $this->stmt->rowCount();
    }

    /**
     * Last inserted ID
     */
    public function lastInsertId()
    {
        return self::$connection->lastInsertId();   
    }

    /**
     * Update a row into a table.
     *
     *  db::update('table', array('fname'=>'foo','age'=>28), array('id'=>1) )
     */
    public function update(string $table, array $fields = [], array $where = [])
    {
        $setClause = implode(', ', array_map(fn($field) => "$field = :$field", array_keys($fields)));
        $whereClause = implode(' AND ', array_map(fn($field) => "$field = :$field", array_keys($where)));

        $sql = "UPDATE {$table} SET {$setClause} WHERE {$whereClause}";
        $params = array_merge($fields, $where);

        $this->prepare($sql, $params);
        return $this->stmt->rowCount();
    } 

    /**
    *	Returns an array which represents a column from the result set 
    */	
    public function get_col(string $query, array $params = [])
    {
        $this->prepare($query, $params);
        $Columns = $this->stmt->fetchAll(PDO::FETCH_NUM);		
        $column = null;
        foreach($Columns as $cells) {
            $column[] = $cells[0];
        }
        return $column;
    }	

    /**
    *	Returns an array which represents a row from the result set 
    */	
    public function get_row(string $query, array $params = [], $fetchMode = PDO::FETCH_OBJ)
    {				
        $this->prepare($query, $params);
        return $this->stmt->fetch($fetchMode);			
    }

    /**
    *	Returns the value of one single field/column
    */	
    public function get_val(string $query, array $params = [])
    {
        $this->prepare($query, $params);
        return $this->stmt->fetchColumn();
    }

    public function __destruct()
    {
        $this->stmt = null;
        self::$connection = null;
    }
}
