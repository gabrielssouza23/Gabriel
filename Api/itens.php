<?php

include "../connect.php";

$_POST = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'];

function listItens()
{
  try {
    // Criação da instância da classe Connection
    $conn = new Connection();

    // Preparação e execução da consulta usando a classe Connection
    $query = "WITH Quantidades AS (
      SELECT
          i.id,
          i.name,
          SUM(CASE WHEN p.action = 'devolucao' THEN -p.amount ELSE p.amount END) AS qtdeComCliente,
          i.amount AS qtdeOriginalEstoque
      FROM
          pedidos p
          JOIN itens i ON p.item_id = i.id
      GROUP BY
          i.id, i.name, i.amount
  )
  
  SELECT
      i.id,
      i.name,
      b.name AS brandName,
      i.place,
      i.deleted,
      i.amount AS totalAmount,
      COALESCE(q.qtdeOriginalEstoque - q.qtdeComCliente, i.amount) AS amount
  FROM
      itens i
      JOIN brand b ON i.brand_id = b.id
      LEFT JOIN Quantidades q ON i.id = q.id;
  ;
    ";
    $rows = $conn->query($query);
    // Exibição dos resultados

    return $rows;
  } catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
  }
}

function listBrands()
{
  try {
    // Criação da instância da classe Connection
    $conn = new Connection();

    // Preparação e execução da consulta usando a classe Connection
    $query = "SELECT * FROM brand";
    $rows = $conn->query($query);

    // Exibição dos resultados

    return $rows;
  } catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
  }
}

function get_itemById($id)
{
  // print_r($id);
  try {
    $conn = new Connection();

    $query = "SELECT i.id,
    i.name,
    i.amount,
    i.place,
    i.deleted,
    b.name AS brand_name,
    b.id AS brand_id
    FROM
    itens i
    JOIN
    brand b ON i.brand_id = b.id
    WHERE
    i.id LIKE '{$id}';";

    $result = $conn->query($query);
    return $result;
  } catch (Exception $e) {
    echo "Error: " . $e->getMessage();
  }
}

function editItem($item)
{
  $conn = new Connection();

  $query = "Update itens set name = '" . $item["inputNome"] . "', amount = '" . $item["inputQuantidade"] . "', place = '" . $item["inputPrateleira"]  . "', brand_id = '" . $item["brand_id"] . "' where id = '" . $item["id"] . "';";

  return $conn->query($query);
}

function addItem($item)
{
  $conn = new Connection();

  $query = "INSERT INTO itens (name, amount, place, brand_id) VALUES ('" . $item["addNome"] . "', '" . $item["addQuantidade"] . "', '" . $item["addPrateleira"] . "', '" . $item["addBrand"] . "');";

  return $conn->query($query);
}

function getHistorico($itemId)
{
  $conn = new Connection();

  $query = "SELECT p.id, c.id idClient, c.name clienteName, p.action, p.amount, p.date FROM pedidos p JOIN clients c ON p.client_id = c.id JOIN itens i ON p.item_id = i.id WHERE i.id = '$itemId'";

  return $conn->query($query);
}


function deleteItem($id)
{
  $conn = new Connection();

  $query = "UPDATE itens SET deleted = 1 WHERE id = '$id'";


  return $conn->query($query);
}

switch ($action) {
  case "get-item":
    $getItemResp = get_itemById($_GET['id']);
    echo json_encode($getItemResp);
    break;
  case "get-itens":
    $response = listItens();
    echo json_encode($response);
    break;
  case "get-itemByBrand":

    break;
  case "get-brand":
    $resp = listBrands();
    echo json_encode($resp);
    break;
  case "edit-item":
    $resp = editItem($_POST);
    echo json_encode($resp);
    break;
  case "add-item":
    $resp = addItem($_POST);
    echo json_encode($resp);
    break;
  case "get-historico":
    $resp = getHistorico($_GET['idMovimentacao']);
    echo json_encode($resp);
    break;
  case "delete-item":
    $resp = deleteItem($_GET['id']);
    echo json_encode($resp);
    break;
  default:
    break;
}
