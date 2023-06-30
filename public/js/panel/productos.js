function scrollToBottom() {
  let lastRow = document.getElementById("productRow_new");
  lastRow.scrollIntoView({ behavior: "smooth" });
  let inputNombreProducto = document.getElementById("nombre_producto_nuevo");
  inputNombreProducto.focus();
}

function deleteNewRow() {
  const newRow = document.getElementById("productRow_new");
  const dataCells = newRow.querySelectorAll(".editable");
  dataCells.forEach((dataCell) => {
    if (
      dataCell.classList.contains("categoria_id") ||
      dataCell.classList.contains("proveedor_id")
    ) {
      const dataSelect = dataCell.querySelector("select");
      dataSelect.value = "";
    } else {
      dataCell.textContent = "";
    }
  });
  removeErrorMessages(newRow);
}

function enableEditing(id) {
  const row = document.querySelector(`#productRow_${id}`);
  const dataCells = row.querySelectorAll(".editable");
  dataCells.forEach((dataCell) => {
    if (
      dataCell.classList.contains("categoria_id") ||
      dataCell.classList.contains("proveedor_id")
    ) {
      const dataSelect = dataCell.querySelector("select");
      dataSelect.setAttribute("data-original", dataSelect.value);
      dataSelect.removeAttribute("disabled");
    } else {
      dataCell.setAttribute("data-original", dataCell.textContent);
      dataCell.setAttribute("contenteditable", true);
    }
  });
  const editBtn = row.querySelector(".editBtn");
  const saveBtn = row.querySelector(".saveBtn");
  editBtn.classList.add("d-none");
  saveBtn.classList.remove("d-none");

  const deleteBtn = row.querySelector(".deleteBtn");
  const cancelBtn = row.querySelector(".cancelBtn");
  deleteBtn.classList.add("d-none");
  cancelBtn.classList.remove("d-none");
}

function fnCancel(id) {
  // Obtén todas las celdas de la fila por el id del producto
  const row = document.querySelector(`#productRow_${id}`);
  const dataCells = row.querySelectorAll(".editable");

  // Debe poner el atributo readonly para los inputs y disabled para los select
  dataCells.forEach((dataCell) => {
    if (
      dataCell.classList.contains("categoria_id") ||
      dataCell.classList.contains("proveedor_id")
    ) {
      const dataSelect = dataCell.querySelector("select");

      dataSelect.value = dataSelect.getAttribute("data-original");
      dataSelect.setAttribute("disabled", true);
    } else {
      dataCell.textContent = dataCell.getAttribute("data-original");
      dataCell.setAttribute("contenteditable", false);
    }
  });

  // Muestra el botón de editar y esconde el de guardar
  const editBtn = row.querySelector(".editBtn");
  const saveBtn = row.querySelector(".saveBtn");
  editBtn.classList.remove("d-none");
  saveBtn.classList.add("d-none");

  // Muestra el botón de eliminar y esconde el de cancelar
  const deleteBtn = row.querySelector(".deleteBtn");
  const cancelBtn = row.querySelector(".cancelBtn");
  deleteBtn.classList.remove("d-none");
  cancelBtn.classList.add("d-none");

  removeErrorMessages(row);
}

function createInputAndAppendToForm(form, type, name, value) {
  const input = document.createElement("input");
  input.type = type;
  input.name = name;
  input.value = value;
  form.appendChild(input);
}

function getElementValueFromRow(row, className, isSelect = false) {
  const elementData = row.querySelector(`.${className}`);
  if (isSelect) {
    return elementData.querySelector("select").value;
  }
  return elementData.textContent;
}

function fillDynamicForm(row, formulario) {
  createInputAndAppendToForm(
    formulario,
    "text",
    "nombre_producto",
    getElementValueFromRow(row, "nombre_producto")
  );
  createInputAndAppendToForm(
    formulario,
    "text",
    "codigo_barras",
    getElementValueFromRow(row, "codigo_barras")
  );
  createInputAndAppendToForm(
    formulario,
    "number",
    "precio_compra",
    removeCurrencyFormat(getElementValueFromRow(row, "precio_compra"))
  );
  createInputAndAppendToForm(
    formulario,
    "number",
    "precio_venta",
    removeCurrencyFormat(getElementValueFromRow(row, "precio_venta"))
  );
  createInputAndAppendToForm(
    formulario,
    "number",
    "precio_mayoreo",
    removeCurrencyFormat(getElementValueFromRow(row, "precio_mayoreo"))
  );
  createInputAndAppendToForm(
    formulario,
    "text",
    "unidad",
    getElementValueFromRow(row, "unidad")
  );
  createInputAndAppendToForm(
    formulario,
    "number",
    "existencias",
    getElementValueFromRow(row, "existencias")
  );
  createInputAndAppendToForm(
    formulario,
    "number",
    "categoria_id",
    getElementValueFromRow(row, "categoria_id", true)
  );
  createInputAndAppendToForm(
    formulario,
    "number",
    "proveedor_id",
    getElementValueFromRow(row, "proveedor_id", true)
  );
}

async function loadCategories(selectedCategoryId) {
  const response = await fetch(
    "app/controllers/categoria_controller.php?action=getAll"
  );
  const categoriesResponse = await response.json();
  const categories = categoriesResponse.data; // access the array inside the 'data' property
  let options = '<option value=""></option>';
  categories.forEach((category) => {
    const selected = category.id == selectedCategoryId ? "selected" : "";
    options += `<option value="${category.id}" ${selected}>${category.nombre_categoria}</option>`;
  });
  return options;
}

async function loadProviders(selectedProviderId) {
  const response = await fetch(
    "app/controllers/proveedor_controller.php?action=getAll"
  );
  const providersResponse = await response.json();
  const providers = providersResponse.data;
  let options = '<option value=""></option>';
  providers.forEach((provider) => {
    const selected = provider.id == selectedProviderId ? "selected" : "";
    options += `<option value="${provider.id}" ${selected}>${provider.nombre_proveedor}</option>`;
  });
  return options;
}

async function loadProducts() {
  try {
    // Obtener los productos del servidor
    const response = await fetch(
      "app/controllers/producto_controller.php?action=getAll"
    );
    const data = await response.json();

    // Obtener la tabla y limpiar su contenido actual
    const tableBody = document.querySelector("#productTable tbody");
    tableBody.innerHTML = "";

    // Iterar sobre cada producto y agregar una fila a la tabla
    for (let product of data.data) {
      const row = document.createElement("tr");
      row.id = `productRow_${product.id}`;

      const categoryOptions = await loadCategories(product.categoria_id);
      const providerOptions = await loadProviders(product.proveedor_id);

      row.innerHTML = `
        <td>${product.id}</td>
        <td class="nombre_producto editable" contenteditable="false">${product.nombre_producto}</td>
        <td class="codigo_barras editable" contenteditable="false">${product.codigo_barras}</td>
          <td class="precio_compra editable moneda" contenteditable="false">${product.precio_compra}</td>
          <td class="precio_venta editable moneda" contenteditable="false">${product.precio_venta}</td>
          <td class="precio_mayoreo editable moneda" contenteditable="false">${product.precio_mayoreo}</td>
          <td class="unidad editable" contenteditable="false">${product.unidad}</td>
          <td class="existencias editable" contenteditable="false">${product.existencias}</td>
          <td class="categoria_id editable">
            <select name="categoria_id" class="form-select border-0 bg-transparent w-auto" disabled>
              ${categoryOptions}
            </select>
          </td>
          <td class="proveedor_id editable">
            <select name="proveedor_id" class="form-select border-0 bg-transparent w-auto" disabled>
              ${providerOptions}
            </select>
          </td>
          <td>
            <button type='button' onclick='enableEditing(${product.id})' class='btn btn-primary editBtn'>
              <img src='../miscelanea_nelsy/public/images/pencil.svg'>
            </button>
            <button type='button' onclick='fnCreateUpdate("UPDATE", ${product.id})' class='btn btn-success d-none saveBtn'>
              <img src='../miscelanea_nelsy/public/images/check.svg'>
            </button>
          </td>
          <td>
            <button type='button' onclick='fnDelete(${product.id})' class='btn btn-danger deleteBtn'>
              <img src='../miscelanea_nelsy/public/images/trash.svg'>
            </button>
            <button type='button' onclick='fnCancel(${product.id})' class='btn btn-dark d-none cancelBtn'>
              <img src='../miscelanea_nelsy/public/images/cancel.svg'>
            </button>
          </td>
      `;

      // Agregar la fila a la tabla
      tableBody.appendChild(row);
    }

    // Aquí puedes agregar la fila para crear un nuevo producto.
    const newRow = document.createElement("tr");
    newRow.id = "productRow_new";

    const newCategoryOptions = await loadCategories(null);
    const newProviderOptions = await loadProviders(null);

    newRow.innerHTML = `
      <td>+</td>
      <td class="nombre_producto editable" id="nombre_producto_nuevo" contenteditable="true"></td>
        <td class="codigo_barras editable" contenteditable="true"></td>
        <td class="precio_compra editable" id='precio_compra_nuevo' contenteditable="true"></td>
        <td class="precio_venta editable" id='precio_venta_nuevo' contenteditable="true"></td>
        <td class="precio_mayoreo editable" id='precio_mayoreo_nuevo' contenteditable="true"></td>
        <td class="unidad editable" contenteditable="true"></td>
        <td class="existencias editable" contenteditable="true"></td>
        <td class="categoria_id editable">
            <select name="categoria_id" class="form-select border-0 w-auto">
              ${newCategoryOptions}
            </select>
          </td>
          <td class="proveedor_id editable">
            <select name="proveedor_id" class="form-select border-0 w-auto">
              ${newProviderOptions}
            </select>
          </td>
          <td>
            <button type='button' onclick='fnCreateUpdate("CREATE", "new")' class='btn btn-success saveBtn'>
              <img src='../miscelanea_nelsy/public/images/check.svg'>
            </button>
          </td>
          <td>
            <button type='button' onclick='deleteNewRow()' class='btn btn-dark'>
              <img src='../miscelanea_nelsy/public/images/cancel.svg'>
            </button>
          </td>
    `;

    tableBody.appendChild(newRow);

    // Formatear todos los elementos con la clase moneda
    const monedas = document.querySelectorAll(".moneda");
    monedas.forEach((element) => formatCurrencyElement(element, true));

    addInputListeners();
    
  } catch (error) {
    console.error("Error:", error);
    alert("Error al cargar los productos.");
  }
}

function fnCreateUpdate(action = "CREATE", id = "") {
  const row = document.querySelector(`#productRow_${id}`);
  const formulario = document.createElement("form");
  formulario.id = `form_${id}`;

  fillDynamicForm(row, formulario);

  let url = "";
  let formData = new FormData(formulario);
  if (action === "CREATE") {
    url = "app/controllers/producto_controller.php?action=store";
  } else {
    url = "app/controllers/producto_controller.php?action=update";
    formData.append("id", id);
  }

  /* formData.forEach((value, key) => {
    console.log(`key: ${key}, value: ${value}`);
  }); */

  // Aquí va el código de fetch para enviar los datos al servidor
  fetch(`${url}`, {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (response.ok) {
        response.json().then(async (data) => {
          //alert(data.data);
          await loadProducts();
        });
      } else {
        response.json().then((data) => {
          const errors = data.data.error;

          const row = document.querySelector(`#productRow_${id}`);

          removeErrorMessages(row);

          addErrorMessages(row, errors);
        });
      }
    })
    .catch(function (error) {
      alert("Error en el sistema.");
    });
}

function removeErrorMessages(row) {
  // Encuentra la fila adyacente que contiene los mensajes de error
  const errorRow = row.nextElementSibling;

  // Verificar si la fila adyacente existe y contiene mensajes de error
  if (errorRow && errorRow.querySelector(".error-message")) {
    errorRow.remove();
  }
}

function addErrorMessages(row, errors) {
  const tableRow = document.createElement("tr");

  // Crear un arreglo con las clases de las celdas en el orden en que aparecen
  const cellClasses = [
    "nombre_producto",
    "codigo_barras",
    "precio_compra",
    "precio_venta",
    "precio_mayoreo",
    "unidad",
    "existencias",
    "categoria_id",
    "proveedor_id",
  ];

  // Añadir una celda vacía al principio para "Id"
  tableRow.appendChild(document.createElement("td"));

  for (const cellClass of cellClasses) {
    const dataCell = row.querySelector(`.${cellClass}`);
    const errorContainer = document.createElement("td");

    if (errors[cellClass]) {
      errorContainer.classList.add("error-message");
      errorContainer.innerHTML = errors[cellClass];
    }

    // Añadir la celda de error a la nueva fila (esté vacía o contenga un mensaje de error)
    tableRow.appendChild(errorContainer);
  }

  // Añadir celdas vacías al final para "Editar" y "Eliminar"
  tableRow.appendChild(document.createElement("td"));
  tableRow.appendChild(document.createElement("td"));

  row.insertAdjacentElement("afterend", tableRow);
}

function fnDelete(id) {
  if (confirm("¿Está seguro de desea eliminar el registro?")) {
    const Form_data = new FormData();
    Form_data.append("id", id);

    fetch(`app/controllers/producto_controller.php?action=destroy`, {
      method: "POST",
      body: Form_data,
    })
      .then((response) => {
        if (response.ok) {
          response.json().then(async (data) => {
            //alert(data.data);
            await loadProducts();
          });
        } else {
          response.json().then((data) => {
            alert(data.data.error);
          });
        }
      })
      .catch(function (error) {
        alert("Error en el sistema.");
      });
  }
}

function printProductsToPDF() {
  window.open("config/generate_pdf_productos.php", "_blank");
}

function normalizeText(text) {
  return text
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .toLowerCase();
}

function filterProducts() {
  const input = document.getElementById("searchProduct");
  const filter = normalizeText(input.value);
  const table = document.getElementById("productTable");
  const rows = table.getElementsByTagName("tr");

  for (let i = 0; i < rows.length; i++) {
    const cells = rows[i].getElementsByTagName("td");
    if (cells.length > 0) {
      const productName = cells[1].textContent || cells[1].innerText;
      if (normalizeText(productName).indexOf(filter) > -1) {
        rows[i].style.display = "";
      } else {
        rows[i].style.display = "none";
      }
    }
  }
}

function roundToMultipleOf100(value) {
  return Math.round(value / 100) * 100;
}

function setCaretAtEnd(element) {
  const range = document.createRange();
  const selection = window.getSelection();
  range.selectNodeContents(element);
  range.collapse(false);
  selection.removeAllRanges();
  selection.addRange(range);
  element.focus();
}

function formatCurrencyElement(element, fromDatabase) {
  let valor;

  if (fromDatabase) {
    // Usar esta cuando se cargan los datos es decir en el evento DOMContentLoaded
    valor = cleanAndConvertToNumber(element.textContent);
  } else {
    // Usar este cuando se está escribiendo el texto es decir en el evento input
    valor = removeCurrencyFormat(element.textContent);
  }

  if (valor === 0 || valor === "" || isNaN(valor)) {
    // Si no es un número, establecer el contenido del texto a un espacio en blanco
    element.textContent = "";
  } else {
    // Si es un número, darle formato de moneda
    element.textContent = formatCurrency(valor);
  }
  // Establecer la posición del cursor al final del contenido
  setCaretAtEnd(element);
}

function cleanAndConvertToNumber(text) {
  return parseFloat(text.replace(/[$,]/g, "")) || "";
}

function removeCurrencyFormat(text) {
  text = text.replace(/\./g, ""); // Eliminar puntos de miles
  text = text.replace(/,/g, "."); // Cambiar comas por puntos
  text = text.replace(/[$]/g, ""); // Eliminar el signo de la moneda
  return parseFloat(text) || "";
}

function formatCurrency(value) {
  return new Intl.NumberFormat("es-CO", {
    style: "currency",
    currency: "COP",
    minimumFractionDigits: 0,
  }).format(value);
}

function calcular_precio() {
  let precio_compra_text = document.getElementById(
    "precio_compra_nuevo"
  ).textContent;
  let precio_compra = removeCurrencyFormat(precio_compra_text);

  if (precio_compra <= 0 || precio_compra == "") {
    document.getElementById("precio_venta_nuevo").textContent = "";
    document.getElementById("precio_mayoreo_nuevo").textContent = "";
  } else {
    let precio_venta = precio_compra * 1.25;
    precio_venta = roundToMultipleOf100(precio_venta) + 100;

    let precio_mayoreo = precio_venta * 0.9;
    precio_mayoreo = roundToMultipleOf100(precio_mayoreo);

    // Dar formato a los valores
    precio_venta = formatCurrency(precio_venta);
    precio_mayoreo = formatCurrency(precio_mayoreo);

    // Actualizar la UI con los valores formateados
    document.getElementById("precio_venta_nuevo").textContent = precio_venta;
    document.getElementById("precio_mayoreo_nuevo").textContent =
      precio_mayoreo;
  }
}

function recalcularPreciosFila(elementoPrecioCompra) {
  // Obtener el elemento de la fila
  const fila = elementoPrecioCompra.closest("tr");

  // Obtener los elementos de precio de venta y mayoreo en la misma fila
  const precioVenta = fila.querySelector(".precio_venta");
  const precioMayoreo = fila.querySelector(".precio_mayoreo");

  // Obtener el valor del precio de compra
  const precioCompra = removeCurrencyFormat(elementoPrecioCompra.textContent);

  // Calcular y actualizar los precios de venta y mayoreo
  if (precioCompra <= 0 || precioCompra == "") {
    precioVenta.textContent = "";
    precioMayoreo.textContent = "";
  } else {
    let precio_venta = precioCompra * 1.25;
    precio_venta = roundToMultipleOf100(precio_venta) + 100;

    let precio_mayoreo = precio_venta * 0.9;
    precio_mayoreo = roundToMultipleOf100(precio_mayoreo);

    // Dar formato a los valores
    precio_venta = formatCurrency(precio_venta);
    precio_mayoreo = formatCurrency(precio_mayoreo);

    // Actualizar la UI con los valores formateados
    precioVenta.textContent = precio_venta;
    precioMayoreo.textContent = precio_mayoreo;
  }
}

function addInputListeners() {
  // Agregar un event listener para el elemento 'precio_compra_nuevo'
  const precioCompraNuevo = document.getElementById("precio_compra_nuevo");
  if (precioCompraNuevo) {
    precioCompraNuevo.addEventListener("input", () => {
      formatCurrencyElement(precioCompraNuevo, false);
      calcular_precio();
    });
  }

  // Agregar event listeners a todos los elementos con la clase 'precio_compra'
  const preciosCompra = document.querySelectorAll(".precio_compra");
  preciosCompra.forEach((precioCompra) => {
    precioCompra.addEventListener("input", () => {
      formatCurrencyElement(precioCompra, false);
      recalcularPreciosFila(precioCompra);
    });
  });

  // Agregar event listeners a todos los elementos con la clase 'precio_venta'
  const preciosVenta = document.querySelectorAll(".precio_venta");
  preciosVenta.forEach((precioVenta) => {
    precioVenta.addEventListener("input", () => {
      formatCurrencyElement(precioVenta, false);
    });
  });

  // Agregar un event listener para el elemento 'precio_venta_nuevo'
  const precioVentaNuevo = document.getElementById("precio_venta_nuevo");
  if (precioVentaNuevo) {
    precioVentaNuevo.addEventListener("input", () => {
      formatCurrencyElement(precioVentaNuevo, false);
    });
  }

  // Agregar event listeners a todos los elementos con la clase 'precio_mayoreo'
  const preciosMayoreo = document.querySelectorAll(".precio_mayoreo");
  preciosMayoreo.forEach((precioMayoreo) => {
    precioMayoreo.addEventListener("input", () => {
      formatCurrencyElement(precioMayoreo, false);
    });
  });

  // Agregar un event listener para el elemento 'precio_mayoreo_nuevo'
  const precioMayoreoNuevo = document.getElementById("precio_mayoreo_nuevo");
  if (precioMayoreoNuevo) {
    precioMayoreoNuevo.addEventListener("input", () => {
      formatCurrencyElement(precioMayoreoNuevo, false);
    });
  }
}

document.addEventListener("DOMContentLoaded", async () => {
  await loadProducts();
});