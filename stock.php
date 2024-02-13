<style>
  .content {
    flex-grow: 1;
    padding: 20px;
    background-color: #f4f4f4;
    margin-left: 250px;
    margin-top: 40px;
    
    /* Adicionei para deixar espaço para a barra horizontal fixa */
  }

  .container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    position: relative;
    margin-top: 15px;
    
  } 

  h1 {
    font-size: 24px;
    text-align: center;
    margin-bottom: 20px;
  }

  .filter {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
  }

  label {
    font-weight: bold;
  }

  select,
  input {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
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
    background-color: #f2f2f2;
  }

  tbody tr:nth-child(even) {
    background-color: #f2f2f2;
  }

  form {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  label,
  input,
  select,
  button {
    margin-bottom: 10px;
    font-size: 16px;
  }

  button {
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 5px;
  }

  button:hover {
    background-color: #45a049;
  }

  /* Adicione estilos para a modal aqui */
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
    margin: 10% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
  }

  .close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
  }

  .close:hover,
  .close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
  }

  
 
</style>
<link rel="stylesheet" href="./styles/nav.css" />
<title>Almoxarifado</title>
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
<div class="container" >
  <h1>Lista de itens</h1>
  <div class="filter">
    <label for="brand">Marca:</label>
    <select id="brand">
      <option value="0">Todas</option>
    </select>
  </div>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Quantidade</th>
        <th>Local na prateleira</th>
        <th>Marca</th>
        <th>Deletado s/n</th>
        <th>Delete</th>
        <th>Movimentação</th>
      </tr>
    </thead>
    <tbody id="itemList">
    </tbody>
  </table>
</div>

<div id="edit-modal" class="modal">
  <div class="modal-content">
    <span class="close" id="closeEditModal">&times;</span>
    <h2>Editar item</h2>
    <form id="edit-form">
      <input type="hidden" id="id" name="id">
      <label for="Nome" id="nome">Nome:</label>
      <input type="text" id="inputNome" name="inputNome">
      <label for="quantidade" id="quantidade">Quantidade:</label>
      <input type="text" id="inputQuantidade" name="inputQuantidade">
      <label for="LocalPrateleira" id="localPrateleira">Local Prateleira:</label>
      <input type="text" id="inputPrateleira" name="inputPrateleira">
      <input type="hidden" id="brand_id" name="brand_id">
      <div class="filter">
        <label for="brand">Marca:</label>
        <select id="brandModal">
          <option id="optionModal" value=""></option>

        </select>

      </div>
      <button type="submit">Salvar</button>
    </form>
  </div>
</div>

<button id="btnAdicionarItem">Adicionar Item</button>


<div id="add-item-modal" class="modal">
  <div class="modal-content">
    <span class="close" id="closeAddItemForm">&times;</span>
    <h2>Adicionar Novo Item</h2>
    <form id="add-item-form">
      <label for="addNome">Nome:</label>
      <input type="text" id="addNome" name="addNome" required>

      <label for="addQuantidade">Quantidade:</label>
      <input type="text" id="addQuantidade" name="addQuantidade" required>

      <label for="addPrateleira">Local na Prateleira:</label>
      <input type="text" id="addPrateleira" name="addPrateleira" required>

      <label for="addBrand">Marca:</label>
      <select id="addBrand" name="addBrand" required>
        <option value="">Selecione uma Marca</option>

      </select>

      <button type="submit">Adicionar Item</button>
    </form>
  </div>
</div>

<div id="item-historico-modal" class="modal">
  <div class="modal-content">
    <span class="close" id="closeHistoricoItemForm">&times;</span>
    <h2>Histórico de movimentação do <span id="modal-title-historico"></span></h2>
    <table>
      <thead>
        <tr>
          <th>Cliente</th>
          <th>Ação</th>
          <th>Quantidade</th>
          <th>Data</th>
        </tr>
      </thead>
      <tbody id="item-historico-table">

      </tbody>
    </table>
    </form>
  </div>
</div>

<script type="module">
  import serializeFormData from "./scripts/utils/serializeFormData.js"
  import {
    useFetch
  } from "./scripts/utils/useFetch.js";
  import openModal from "./scripts/utils/openModal.js";

  const tableBrands = document.querySelector("table");
  // Seletor para a modal
  const modal = document.querySelector("#edit-modal");
  // Seletor para o botão de fechar a modal
  const closeModalButton = document.querySelector(".close");

  const selectBrand = document.querySelector("#brand");
  const selectBrandModal = document.querySelector("#brandModal");

  const selectAddBrand = document.querySelector("#addBrand");

  const allBrands = [];


  // bootstrap
  document.addEventListener("DOMContentLoaded", async (e) => {

    // pega as marcas e coloca no select
    const requestBrand = await fetch("./Api/itens.php?action=get-brand");

    const brands = await requestBrand.json();

    brands.forEach((brand) => {
      const brandOption = document.createElement("option");
      brandOption.setAttribute('value', brand.id);
      const optionText = document.createTextNode(brand.name);
      brandOption.appendChild(optionText);
      selectBrand.appendChild(brandOption);
      selectAddBrand.appendChild(brandOption);
      allBrands.push({
        id: brand.id,
        name: brand.name
      });

    });
    // 


    // pega os itens e coloca na tabela
    const request = await fetch("./Api/itens.php?action=get-itens");

    const itens = await request.json();

    const itemList = document.querySelector("#itemList");
    itemList.innerHTML = "";
    itens.forEach((item) => {
      const tr = document.createElement("tr");
      tr.setAttribute("data-id", item.id);

      if (item.deleted == 1) {
        tr.setAttribute("style", "display: none;");
      }
      if (item.amount == 0) {
        tr.setAttribute("style", "background-color: #ff0000;");
      }
      console.log(item);
      tr.classList.add("tbodyChild");
      tr.setAttribute("data-brand", item.brand_id);
      tr.innerHTML =
        `<td>${item.id}</td>
        <td>${item.name}</td>
        <td>${item.amount}</td>
        <td>${item.place}</td>
        <td class="brandNameItem">${item.brandName}</td>
        <td>${item.deleted}</td>
        <td><button>X</button></td>
        <td><button class="botaoMovimentacaoHistorico">Histórico</button
        `;
      itemList.appendChild(tr);
    });
    // 



    document.addEventListener("keyup", (event) => {
      if (event.keyCode == 27) {
        const modais = document.querySelectorAll(".modal");
        modais.forEach((modal) => {
          openModal(modal.getAttribute('id'), false)
        });
      }
    });

    selectBrand.addEventListener("change", async (e) => {
      const itens = Array.from(document.getElementById("itemList").children);

      itens.forEach((element) => {

        if (selectBrand.value == 0) {
          element.style.display = "table-row";
        } else if (element.getAttribute("data-brand") != selectBrand.value) {
          element.style.display = "none";
        } else {
          element.style.display = "table-row";
        }

      });

    });


    tableBrands.addEventListener("dblclick", (event) => {
      if (event.target.tagName === "TD") {
        const urlGetItem = "./Api/itens.php?action=get-item&id=" + event.target.parentNode.getAttribute(
          "data-id");
        fetch(urlGetItem).then((response) => {
          response.json().then((brand) => {
            // carregar os dados no formulário
            const form = document.querySelector("#edit-form");
            form.querySelector("#id").value = brand[0].id;
            form.querySelector("#inputNome").value = brand[0].name;
            form.querySelector("#inputQuantidade").value = brand[0].amount;
            form.querySelector("#inputPrateleira").value = brand[0].place;
            form.querySelector("#optionModal").innerHTML = brand[0].brand_name;
            form.querySelector("#optionModal").value = brand[0].brand_id;
            // coloca as marcas no select
            allBrands.forEach((element) => {
              const brandEditOption = document.createElement("option");
              brandEditOption.setAttribute('value', element.id);
              const optionEditText = document.createTextNode(element.name);
              if (element.id == brand[0].brand_id) {
                brandEditOption.style.display = "none";
              }
              brandEditOption.appendChild(optionEditText);
              selectBrandModal.appendChild(brandEditOption);
            });
            // 
          });
        });
        openModal('edit-modal', true);
      }
    });

    const openMovimentacaoHistorico = document.getElementsByClassName('botaoMovimentacaoHistorico');
    console.log("🚀 ~ openMovimentacaoHistorico:", openMovimentacaoHistorico)


    Array.from(openMovimentacaoHistorico).forEach(function(element) {
      element.addEventListener('click', async (e) => {
        const itemId = element.parentNode.parentNode
          .getAttribute('data-id')
        const itemName = element.parentNode.parentNode.children[1].textContent;
        const url = "./Api/itens.php?action=get-historico&idMovimentacao=" + itemId
        const getHistorico = await useFetch(url);
        console.log("🚀 ~ element.addEventListener ~ getHistorico:", getHistorico)

        const tbody = document.getElementById("item-historico-table")
        tbody.innerHTML = "";

        const titleModal = document.getElementById("modal-title-historico")

        titleModal.innerText = itemName;

        getHistorico.map(movimentacao => {
          const tr = document.createElement("tr");
          tr.setAttribute("data-movimentacao-id", movimentacao.id);
          const trContent = `
          <td><a href='client.php?cliente-id=${movimentacao.idClient}' target='BLANK'>${movimentacao.clienteName}</a></td>
          <td>${movimentacao.action}</td>
          <td>${movimentacao.amount}</td>
          <td>${new Date(movimentacao.date).toLocaleString()}</td>`

          tr.innerHTML = trContent;

          tbody.appendChild(tr);
        });

        openModal("item-historico-modal", true);
      });
    });

    const closeHistoricoItemForm = document.getElementById("closeHistoricoItemForm")
    closeHistoricoItemForm.addEventListener("click",
      () => openModal("item-historico-modal", false));


    const closeEditModal = document.getElementById('closeEditModal');
    closeEditModal.addEventListener('click',
      () => openModal("edit-modal", false));


    //  modal adicionar novo item
    const addButtonNewItem = document.getElementById('btnAdicionarItem');
    addButtonNewItem.addEventListener('click',
      () => openModal("add-item-modal", true));

    const addItem = document.getElementById('add-item-form');
    addItem.addEventListener('submit', (e) => {
      e.preventDefault();
      document.getElementById('addBrand').value;

      const seliarizedForm = serializeFormData(addItem);
      console.log(seliarizedForm);
      const url = "./Api/itens.php?action=add-item";
      const options = {
        headers: {
          "Content-Type": "application/json"
        },
        method: "post",
        body: JSON.stringify(seliarizedForm)
      }
      fetch(url, options).then(response => response.json()).then(json => console.log(json));
      location.href = "stock.php";

    });

    tableBrands.addEventListener("click", async (event) => {
      if (event.target.tagName === "BUTTON") {

        const url = "./Api/itens.php?action=delete-item&id=" + event.target.parentNode.parentNode.getAttribute(
          "data-id");
        const deleteItem = await useFetch(url);
        console.log("🚀 ~ deleteItem", deleteItem)

      }
    });




    const closeNewItemModal = document.getElementById('closeAddItemForm');
    closeNewItemModal.addEventListener('click',
      () => openModal("add-item-modal", false));

    const editForm = document.getElementById('edit-form');
    editForm.addEventListener('submit', (e) => {
      e.preventDefault();
      document.getElementById('brand_id').value = selectBrandModal.value;

      const seliarizedForm = serializeFormData(editForm);
      console.log(seliarizedForm);
      const url = "./Api/itens.php?action=edit-item";
      const options = {
        headers: {
          "Content-Type": "application/json"
        },
        method: "post",
        body: JSON.stringify(seliarizedForm)
      }
      fetch(url, options).then(response => response.json()).then(json => console.log(json));

    });
  });
</script>