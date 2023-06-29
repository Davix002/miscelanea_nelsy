function scrollToBottom() {
  let lastRow = document.getElementById('productRow_new');
  lastRow.scrollIntoView({behavior: 'smooth'});
  
  let inputNombreProducto = document.getElementById('nombre_producto_nuevo');
  inputNombreProducto.focus();
}

function deleteNewRow() {
  const newRow = document.getElementById('productRow_new');

  const dataCells = newRow.querySelectorAll('.editable');

  dataCells.forEach(dataCell => {
    if (dataCell.classList.contains("categoria_id") || dataCell.classList.contains("proveedor_id")) {
      const dataSelect = dataCell.querySelector("select");
      dataSelect.value = '';
    } else {
      dataCell.textContent = '';
    }
  });

  removeErrorMessages(newRow);
}

function enableEditing(id) {
  // Obtén todas las celdas de la fila por el id del producto
  const row = document.querySelector(`#productRow_${id}`);
  const dataCells = row.querySelectorAll('.editable');

  //guarda los valores originales del producto y habilita la edición de los campos
  dataCells.forEach(dataCell => {
    if (dataCell.classList.contains("categoria_id") || dataCell.classList.contains("proveedor_id")) {
      const dataSelect = dataCell.querySelector('select');

      dataSelect.setAttribute('data-original', dataSelect.value);
      dataSelect.removeAttribute('disabled');
    } else {
      dataCell.setAttribute('data-original', dataCell.textContent);
      dataCell.setAttribute('contenteditable', true);
    }
  });

  // Muestra el botón de guardar y esconde el de editar
  const editBtn = row.querySelector('.editBtn');
  const saveBtn = row.querySelector('.saveBtn');
  editBtn.classList.add('d-none');
  saveBtn.classList.remove('d-none');

  // Muestra el botón de cancelar y esconde el de eliminar
  const deleteBtn = row.querySelector('.deleteBtn');
  const cancelBtn = row.querySelector('.cancelBtn');
  deleteBtn.classList.add('d-none');
  cancelBtn.classList.remove('d-none');
}

function fnCancel(id){
  // Obtén todas las celdas de la fila por el id del producto
  const row = document.querySelector(`#productRow_${id}`);
  const dataCells = row.querySelectorAll('.editable');

  // Debe poner el atributo readonly para los inputs y disabled para los select
  dataCells.forEach(dataCell => {
    if (dataCell.classList.contains("categoria_id") || dataCell.classList.contains("proveedor_id")) {
      const dataSelect = dataCell.querySelector('select');

      dataSelect.value = dataSelect.getAttribute('data-original');
      dataSelect.setAttribute('disabled', true);
    } else {
      dataCell.textContent = dataCell.getAttribute('data-original');
      dataCell.setAttribute('contenteditable', false);
    }
  });

  // Muestra el botón de editar y esconde el de guardar
  const editBtn = row.querySelector('.editBtn');
  const saveBtn = row.querySelector('.saveBtn');
  editBtn.classList.remove('d-none');
  saveBtn.classList.add('d-none');

  // Muestra el botón de eliminar y esconde el de cancelar
  const deleteBtn = row.querySelector('.deleteBtn');
  const cancelBtn = row.querySelector('.cancelBtn');
  deleteBtn.classList.remove('d-none');
  cancelBtn.classList.add('d-none');

  removeErrorMessages(row);
}

function fnCreateUpdate(action = "CREATE", id = "") {

  const row = document.querySelector(`#productRow_${id}`);

  const formulario = document.createElement("form");
  formulario.id = `form_${id}`;

  const inputName = document.createElement("input");
  inputName.type= "text";
  inputName.name = "nombre_producto"
  const nameData = row.querySelector('.nombre_producto');
  inputName.value =  nameData.textContent;
  formulario.appendChild(inputName);

  const inputBarcode = document.createElement("input");
  inputBarcode.type= "text";
  inputBarcode.name = "codigo_barras"
  const barcodeData = row.querySelector('.codigo_barras');
  inputBarcode.value =  barcodeData.textContent;
  formulario.appendChild(inputBarcode);

  const inputPurchasePrice = document.createElement("input");
  inputPurchasePrice.type= "number";
  inputPurchasePrice.name = "precio_compra"
  const purchasePriceData = row.querySelector('.precio_compra');
  inputPurchasePrice.value = cleanAndConvertToNumber(purchasePriceData.textContent);
  formulario.appendChild(inputPurchasePrice);

  const inputSellingPrice = document.createElement("input");
  inputSellingPrice.type= "number";
  inputSellingPrice.name = "precio_venta"
  const sellingPriceData = row.querySelector('.precio_venta');
  inputSellingPrice.value = cleanAndConvertToNumber(sellingPriceData.textContent);
  formulario.appendChild(inputSellingPrice);

  const inputWholesalePrice = document.createElement("input");
  inputWholesalePrice.type= "number";
  inputWholesalePrice.name = "precio_mayoreo"
  const wholesalePriceData = row.querySelector('.precio_mayoreo');
  inputWholesalePrice.value = cleanAndConvertToNumber(wholesalePriceData.textContent);
  formulario.appendChild(inputWholesalePrice);

  const inputUnit = document.createElement("input");
  inputUnit.type= "text";
  inputUnit.name = "unidad"
  const unitData = row.querySelector('.unidad');
  inputUnit.value =  unitData.textContent;
  formulario.appendChild(inputUnit);

  const inputStock = document.createElement("input");
  inputStock.type= "number";
  inputStock.name = "existencias"
  const stockData = row.querySelector('.existencias');
  inputStock.value =  stockData.textContent;
  formulario.appendChild(inputStock);

  const categoryData = row.querySelector('.categoria_id');
  const selectedCategory = categoryData.querySelector('select').value;
  const inputCategory = document.createElement("input");
  inputCategory.type = "number";
  inputCategory.name = "categoria_id";
  inputCategory.value = selectedCategory;
  formulario.appendChild(inputCategory);

  const supplierData = row.querySelector('.proveedor_id');
  const selectedSupplier = supplierData.querySelector('select').value;
  const inputSupplier = document.createElement("input");
  inputSupplier.type = "number";
  inputSupplier.name = "proveedor_id";
  inputSupplier.value = selectedSupplier;
  formulario.appendChild(inputSupplier);

   let url = "";
   let formElement;
  if (action === "CREATE") {
    url = "app/controllers/producto_controller.php?action=store";
    formElement = formulario;
  } else {
    url = "app/controllers/producto_controller.php?action=update";
    formElement = formulario;
  }

  let formData = new FormData(formElement);

  // Añade el id si es una actualización
  if (action === "UPDATE") {
    formData.append("id", id);
  }

    // Aquí va el código de fetch para enviar los datos al servidor
  fetch(`${url}`, {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (response.ok) {
        response.json().then((data) => {
          alert(data.data);
          window.location.href = "productos";
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
  if (errorRow && errorRow.querySelector('.error-message')) {
    errorRow.remove();
  }
}


function addErrorMessages(row, errors) {

  const tableRow = document.createElement("tr");

  // Crear un arreglo con las clases de las celdas en el orden en que aparecen
  const cellClasses = [
    'nombre_producto', 'codigo_barras', 'precio_compra', 'precio_venta',
    'precio_mayoreo', 'unidad', 'existencias', 'categoria_id', 'proveedor_id'
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

  row.insertAdjacentElement('afterend', tableRow);
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
          response.json().then((data) => {
            alert(data.data);
            window.location.href = "productos";
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
  return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
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

function cleanAndConvertToNumber(text) {
  text = text.replace(/\./g, ''); // Eliminar puntos de miles
  text = text.replace(/,/g, '.'); // Cambiar comas por puntos
  text = text.replace(/[$]/g, ''); // Eliminar el signo de la moneda
  return parseFloat(text) || '';
}

function roundToMultipleOf100(value) {
  return Math.round(value / 100) * 100;
}

function calcular_precio() {
  let precio_compra_text = document.getElementById("precio_compra_nuevo").textContent;
  let precio_compra = cleanAndConvertToNumber(precio_compra_text);

  console.log(precio_compra);

  if (precio_compra <= 0 || precio_compra == "") {
    document.getElementById("precio_venta_nuevo").textContent = "";
    document.getElementById("precio_mayoreo_nuevo").textContent = "";
  } else {
    let precio_venta = precio_compra * 1.25;
    precio_venta = roundToMultipleOf100(precio_venta) + 100;

    let precio_mayoreo = precio_venta * 0.9;
    precio_mayoreo = roundToMultipleOf100(precio_mayoreo);

    // Guarda los valores antes de darles formato
    let precio_venta_sin_formato = precio_venta;
    let precio_mayoreo_sin_formato = precio_mayoreo;

    // Dar formato a los valores
    precio_venta = formatCurrency(precio_venta);
    precio_mayoreo = formatCurrency(precio_mayoreo);

    // Actualizar la UI con los valores formateados
    document.getElementById("precio_venta_nuevo").textContent = precio_venta;
    document.getElementById("precio_mayoreo_nuevo").textContent = precio_mayoreo;

    // Usa las variables sin formato para cualquier cálculo adicional
    console.log(precio_venta_sin_formato, precio_mayoreo_sin_formato);
  }
}

function formatCurrency(value) {
  return new Intl.NumberFormat("es-CO", {
    style: "currency",
    currency: "COP",
    minimumFractionDigits: 0,
  }).format(value);
}

function formatCurrencyElement(element) {
  const valor = cleanAndConvertToNumber(element.textContent);

  if (valor === 0 || isNaN(valor)) {
    // Si no es un número, establecer el contenido del texto a un espacio en blanco
    element.textContent = '';
  } else {
    // Si es un número, darle formato de moneda
    element.textContent = formatCurrency(valor);
  }
  // Establecer la posición del cursor al final del contenido
  setCaretAtEnd(element);
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

document.addEventListener('DOMContentLoaded', () => {
  // Formatear inicialmente todos los elementos con la clase moneda
  const monedas = document.querySelectorAll(".moneda");
  monedas.forEach(formatCurrencyElement);

  // Agregar un event listener para el elemento 'precio_compra_nuevo'
  const precioCompraNuevo = document.getElementById('precio_compra_nuevo');
  precioCompraNuevo.addEventListener('input', () => {
    formatCurrencyElement(precioCompraNuevo);
    calcular_precio();
  }); 
});