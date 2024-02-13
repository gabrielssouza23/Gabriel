<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    h2 {
      background-color: #333;
      color: #fff;
      padding: 10px;
      margin: 0;
    }

    form,
    table {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    form label,
    form input[type="text"],
    form select,
    form input[type="submit"] {
      width: 100%;
      margin-bottom: 10px;
    }

    input[type="text"],
    select {
      width: 100%;
      padding: 8px;
      box-sizing: border-box;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    form input[type="submit"] {
      background-color: #333;
      color: #fff;
      padding: 10px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    form input[type="submit"]:hover {
      background-color: #555;
    }

    table,
    th,
    td {
      border: 1px solid #ddd;
    }

    th,
    td {
      padding: 12px;
      text-align: left;
    }

    th {
      background-color: #333;
      color: #fff;
    }


    h2 {
      background-color: #333;
      color: #fff;
      padding: 10px;
      margin: 0;
    }

    table {
      width: 80%;
      border-collapse: collapse;
      justify-content: center;
      margin: 0 auto;
      margin-top: 20px;
    }

    form {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      width: 300px;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    label {
      display: block;
      margin-bottom: 5px;
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    #response {
      margin-top: 10px;
      color: green;
    }


    section {
      margin: 20px;
      display: flex;
      justify-content: center;

    }

    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
      background-color: #fefefe;
      position: absolute;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
      padding: 20px;
      border: 1px solid #888;
      width: 400px;
    }

    #botaoEnviarCliente {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
  </style>
  <link rel="stylesheet" href="./styles/nav.css" />
</head>
<nav>
  <div class="logo">
    <img src="./assets/tmwLogo.png" alt="Logo Image">
  </div>
  <ul class="nav-links">
    <li><a href="stock.php">Estoque</a></li>
    <li><a href="order.php">Pedidos</a></li>
    <li><a href="home.php" style="color: turquoise;">Home</a></li>
  </ul>
</nav>
<body>
  <h2 id="title-cliente"></h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Item</th>
        <th>Quantidade</th>
        <th>Ação</th>
        <th>Data</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>

  <div id="cliente-nao-encontrado" class="modal">
    <div class="modal-content">
      <!-- <span class="close" name="closeModalClienteindefinido">&times;</span> -->
      <h2>Selecione um cliente</h2>

      <select name="clientesSelect" id="clientesSelect">
        <option value="0" disable selected>Selecione</option>
      </select>

      <button id="botaoEnviarCliente">Enviar</button>

    </div>
  </div>


  <script type="module">
    import serializeFormData from "./scripts/utils/serializeFormData.js"
    import {
      useFetch
    } from "./scripts/utils/useFetch.js";
    import listSelect from "./scripts/utils/listSelect.js";
    import openModal from "./scripts/utils/openModal.js";


    const urlParam = new URLSearchParams(window.location.search).get("cliente-id");
    console.log("🚀 ~ urlParam:", urlParam)

    const tbody = document.querySelector('tbody');
    document.addEventListener('DOMContentLoaded', async (e) => {
      openModal("cliente-nao-encontrado", false);

      if (!urlParam) {

        openModal("cliente-nao-encontrado", true);


        const requestClientes = await useFetch("Api/clients.php?action=get-clients")
        console.log("🚀 ~ document.addEventListener ~ requestClientes:", requestClientes)

        listSelect('select[name="clientesSelect"]', requestClientes);

        const botaoEnviarClientes = document.getElementById('botaoEnviarCliente');
        botaoEnviarClientes.addEventListener('click', (e) => {

          const select = document.querySelector('select[name="clientesSelect"]');

          location.href = "client.php?cliente-id=" + select.value;
        });
        return
      }
      const movimentacoes = await useFetch("Api/clients.php?action=get-movimentacao&client-id=" + urlParam);
      console.log("🚀 ~ document.addEventListener ~ movimentacoes:", movimentacoes)

      tbody.innerHTML = "";
      movimentacoes.forEach((movimentacao) => {
        const tr = document.createElement("tr");
        tr.setAttribute("data-id", movimentacao.id);
        tr.innerHTML = `
      <td>${movimentacao.id}</td>
      <td>${movimentacao.itemName}</td>
        <td>${movimentacao.amount}</td>
        <td>${movimentacao.action}</td>
        <td>${new Date(movimentacao.date).toLocaleString()}</td>       
        `

        tbody.appendChild(tr);
      });

      const titleCliente = document.getElementById('title-cliente');

      titleCliente.innerText = "Movimentações de " + movimentacoes[0].clienteName;

      console.log("🚀 ~ document.addEventListener ~ movimentacoes[0].clienteName:", movimentacoes[0].clienteName)

    });
  </script>
</body>

</html>