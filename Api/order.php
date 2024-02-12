<?php

include "../connect.php";

$_POST = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'];


function getOrders()
{
  try {

    $conn = new Connection();

    $query = "SELECT p.id AS id,
    p.amount 'pedidosAmount',
    c.name 'clientName',
    c.email 'clientEmail',
    i.name 'itemName',
    p.action 'action',
    p.timeP 'timeP'
    FROM pedidos p
    JOIN clients c ON p.client_id = c.id
    JOIN itens i ON p.item_id = i.id
    order by p.id
   ;";

    $rows = $conn->query($query);

    return $rows;
  } catch (Exception $e) {
  }
}

// function postOrder($order)
// {
//   try {
//     // Criação da instância da classe Connection
//     $conn = new Connection();
//     $query = "SELECT * FROM pedidos WHERE client_id = '" . $order["selectClient"] . "' AND item_id = '" . $order['selectItem'] . "' AND action = '" . $order['selectAction'] . "';";
//     // Preparação e execução da consulta usando a classe Connection
//     $query = "INSERT INTO pedidos (client_id, item_id, amount, action)
//     VALUES ('" . $order["selectClient"] . "', '" . $order['selectItem'] . "', '" . $order['amountOrder'] . "', '" . $order['selectAction'] . "');";
//     $conn->query($query);

//     return true;
//   } catch (Exception $e) {
//     echo "Erro: " . $e->getMessage();
//     return false;
//   }
// }

function postOrder($order)
{
  try {
    // Criação da instância da classe Connection
    $conn = new Connection();

    // Consulta para obter a quantidade em estoque do item usando a subconsulta Quantidades
    $stockQuery = "WITH Quantidades AS (
      SELECT
        i.id,
        i.name,
        p.action,
        COALESCE(SUM(CASE WHEN p.action = 'devolucao' THEN -p.amount ELSE p.amount END), 0) AS qtdeComCliente,
        i.amount AS qtdeOriginalEstoque
      FROM
        itens i
        LEFT JOIN pedidos p ON p.item_id = i.id
      GROUP BY
        i.id, i.name, i.amount
    )

    SELECT
      q.qtdeOriginalEstoque - q.qtdeComCliente AS amount,
      q.action as selectAction
    FROM
      Quantidades q
    WHERE
      q.id = '" . $order['selectItem'] . "'";

    $stockResult = $conn->query($stockQuery);
    $stockAmount = $stockResult[0]['amount'];

    // Verifica se a quantidade solicitada é menor ou igual à quantidade em estoque
    if ($order['amountOrder'] <= $stockAmount || $order['selectAction'] == 'devolucao') {
      // Preparação e execução da consulta usando a classe Connection
      $insertQuery = "INSERT INTO pedidos (client_id, item_id, amount, action)
      VALUES ('" . $order["selectClient"] . "', '" . $order['selectItem'] . "', '" . $order['amountOrder'] . "', '" . $order['selectAction'] . "');";

      $conn->query($insertQuery);

      return true;
    } else {
      // Se a quantidade solicitada for maior que a quantidade em estoque, retorna falso
      return false;
    }
  } catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
    return false;
  }
}




switch ($action) {
  case "get-orders":
    $resp = getOrders();
    echo json_encode($resp);
    break;
  case "post-order":
    $getItemResp = postOrder($_POST);
    echo ($getItemResp);
    break;
  default:
    break;
}
