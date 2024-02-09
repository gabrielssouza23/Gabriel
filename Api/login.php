<?php
$_POST = json_decode(file_get_contents('php://input'), true);
include "../connect.php";

// $conn = new Connection();

// $conn->("INSERT INTO user (username, password) VALUES ('$username', '$password')");
// print_r($_POST);

$action = $_POST['action']; // login, logoff

session_start();
function loginUsuario($email, $password)
{
  $conn = new Connection();

  $query = "SELECT * FROM user WHERE email = '$email' LIMIT 1;";

  // print_r($conn->query($query));

  // $stm = $conn->query('SELECT * FROM user');

  // fetch all rows into array, by default PDO::FETCH_BOTH is used
  // $rows = $stm->fetchAll();
  // print_r($rows);



  if (empty($conn->query($query))) {

    print_r("usuario não encontrado");
  } else {
    foreach ($conn->query($query) as $row) {

      if ($password == $row['password']) {
        // print_r($row);
        return $row;
      }
    }
  }
  exit;
}




switch ($action) {
  case 'login':
    $response = loginUsuario($_POST['email'], $_POST['password']);
    $_SESSION["userLogin"] = $response;
    echo json_encode($response);
    // print_r($response);
    break;
  case 'logff':
    break;
  default:
    break;
}
