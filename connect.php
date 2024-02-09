<?php

require_once "config.php";

if (!class_exists('Connection')) {
  class Connection
  {
    private $username  = DB_USER;
    private $servername = DB_HOST;
    private $password = DB_PASSWORD;
    private $database = DB_NAME;
    public $mysqli;
    public function __construct()
    {
      $this->mysqli = new mysqli($this->servername, $this->username, $this->password, $this->database);

      if ($this->mysqli->connect_error) {
        echo "Erro ao conectar!" . "<br>" . "Erro num = (" . $this->mysqli->connect_errno . ")" . "<br>" . $this->mysqli->connect_error . "<br>";
        echo "Finalizando execução";
        die();
      }
    }

    public function query($sql)
    {
      $result = $this->mysqli->query($sql);
      return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function queryAux($sql)
    {
      $result = $this->mysqli->query($sql);
      return $result;
    }

    public function prepare($sql)
    {
        return $this->mysqli->prepare($sql);
    }

    

    public function executeQuery($query, $params = [])
    {
      $stmt = mysqli_prepare($this->mysqli, $query);

      if ($stmt) {
        // Bind parameters
        if (!empty($params)) {
          $types = str_repeat('s', count($params)); // ajuste conforme necessário
          mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        // Execute statement
        mysqli_stmt_execute($stmt);

        // Você pode retornar resultados ou realizar outras operações conforme necessário
        $result = mysqli_stmt_get_result($stmt);

        // Feche o statement
        mysqli_stmt_close($stmt);

        return $result;
      } else {
        echo "Erro na preparação da declaração: " . mysqli_error($this->mysqli);
        return false;
      }
    }
  }
}
