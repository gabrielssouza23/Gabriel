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

  </style>
</head>

<body>
  <h2>Order Management</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Item</th>
        <th>Quantidade</th>
        <th>Ação</th>
        <th>Data</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>

  <section>
    <form action="">
      <label for="selectClient">Cliente:</label>
      <select name="selectClient">
        <option disabled selected value="0">Selecione</option>
      </select>

      <label for="selectItem">Item:</label>
      <select name="selectItem">
        <option disabled selected value="0">Selecione</option>
      </select>

      <label for="amountOrder">Quantidade:</label>
      <input type="text" name="amountOrder" />

      <label for="selectAction">Ação:</label>
      <select name="selectAction">
        <option value="0" disabled selected>Selecione</option>
        <option value="devolucao">Devolução</option>
        <option value="retirada">Retirada</option>
      </select>

      <input type="submit" value="Enviar">

      <div id="response"></div>
    </form>
  </section>

  <script type="module">
    import serializeFormData from "./scripts/utils/serializeFormData.js"
    import {
      useFetch
    } from "./scripts/utils/useFetch.js"

    const form = document.querySelector('form')
    document.addEventListener('DOMContentLoaded', async (e) => {
      const request = await fetch("Api/itens.php?action=get-itens");

      const json = await request.json();
      console.log(json);

      const getOrders = await fetch("Api/order.php?action=get-orders");

      const orders = await getOrders.json();

      const tbody = document.querySelector("tbody");

      tbody.innerHTML = "";
      orders.forEach((order) => {
        const tr = document.createElement("tr");
        tr.setAttribute("data-id", order.id);
        console.log(order);
        tr.innerHTML =
          `<td>${order.id}</td><td>${order.clientName}</td><td>${order.itemName}</td><td>${order.pedidosAmount}</td><td>${order.action}</td><td>${order.timeP}</td>`;
        tbody.appendChild(tr);
      });

      console.table(orders);

      listSelect('select[name="selectItem"]', json)


      const requestClients = await fetch("Api/clients.php?action=get-clients")

      const clients = await requestClients.json();


      listSelect('select[name="selectClient"]', clients);

    });

    form.addEventListener("submit", async (e) => {
      e.preventDefault();



      const orderSerialize = serializeFormData(form);
      const request = await fetch("./Api/order.php?action=post-order", {
        headers: {
          "Content-Type": "application/json"
        },
        method: "POST",
        body: JSON.stringify(orderSerialize)
      })

      const response = await request.json();

      const responseData = document.getElementById("response");

      // if (response ) {
      //   responseData.innerHTML = "Enviado com sucesso";
      //   responseData.setAttribute.style.color = "green";
      // } else {
      //   responseData.innerHTML = "Erro ao enviar";
      //   responseData.setAttribute.style.color = "red";
      // }

      const url = "./Api/order.php?action=send-order"
      const sendOrder = await useFetch(url, {
        method: "POST"
      });

      console.log(sendOrder);
      
    });

    function listSelect(selector, options) {
      const select = document.querySelector(selector);
      options.map(option => {
        const optionElement = document.createElement("option");
        optionElement.setAttribute('value', option.id);
        const optionText = document.createTextNode(option.name);
        optionElement.appendChild(optionText);
        select.appendChild(optionElement);
      })
    }
  </script>
</body>

</html>