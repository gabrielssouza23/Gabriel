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
    $query = "SELECT *, i.name 'name', b.name 'brandName' FROM itens i JOIN brand b ON i.brand_id = b.id
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

  $query = "Update itens set name = '" . $item["inputNome"] . "', amount = '" . $item["inputQuantidade"] . "', place = '" . $item["inputPrateleira"]  . "', brand_id = '". $item["brand_id"] ."' where id = '" . $item["id"] . "';";

  return $conn->query($query);
}

function addItem($item)
{
  $conn = new Connection();

  $query = "INSERT INTO itens (name, amount, place, brand_id) VALUES ('" . $item["inputNome"] . "', '" . $item["inputQuantidade"] . "', '" . $item["inputPrateleira"] . "', '" . $item["brand_id"] . "');";

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
  default:
    break;
}
