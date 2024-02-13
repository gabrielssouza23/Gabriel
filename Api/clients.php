<?php

include "../connect.php";

$_POST = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'];


function listClients()
{
  try {
    // Criação da instância da classe Connection
    $conn = new Connection();

    // Preparação e execução da consulta usando a classe Connection
    $query = "SELECT * FROM clients";

    return $conn->query($query);;
  } catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
  }
}

function getMovimentacao($clientId)
{
  $conn = new Connection();

  $query = "SELECT p.id, c.name clienteName,i.name itemName, p.action, p.amount, p.date FROM pedidos p JOIN clients c ON p.client_id = c.id JOIN itens i ON p.item_id = i.id WHERE c.id = '$clientId'";

  return $conn->query($query);
}

switch ($action) {
  case "get-clients":
    $getClientsResp = listClients();
    echo json_encode($getClientsResp);
    break;
  case "get-movimentacao":
    $resp = getMovimentacao($_GET['client-id']);
    echo json_encode($resp);
    break;
  default:
    break;
}
