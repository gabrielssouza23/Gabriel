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
    $rows = $conn->query($query);

    // Exibição dos resultados

    return $rows;
  } catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
  }
}


switch ($action) {
  case "get-clients":
    $getClientsResp = listClients();
    echo json_encode($getClientsResp);
    break;

  default:
    break;
}
