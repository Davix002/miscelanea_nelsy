   function fnDelete(id) {
    if (confirm("¿Está seguro de desea eliminar el registro?")) {
      const Form_data = new FormData();
      Form_data.append("id", id);
      fetch(`app/controllers/proveedor_controller.php?action=destroy`, {
        method: "POST",
        body: Form_data,
      })
        .then((response) => {
          if (response.ok) {
            response.json().then(async (data) => {
              //alert(data.data);
              await loadProveedores();
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
  
  function printProveedoresToPDF() {
    window.open("config/generate_pdf_proveedores.php", "_blank");
  }
  
  function normalizeText(text) {
    return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
  }
  
  function filterProveedores(){
    const input = document.getElementById("searchProveedor");
    const filter = normalizeText(input.value);
    const table = document.getElementById("proveedorTable");
    const rows = table.getElementsByTagName("tr");
  
    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName("td");
        if (cells.length > 0) {
            const proveedorName = cells[1].textContent || cells[1].innerText;
            if (normalizeText(proveedorName).indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
  }
  
  async function loadProveedores() {
    try {
      const response = await fetch("app/controllers/proveedor_controller.php?action=getAll");
      const data = await response.json();
  
      const tableBody = document.querySelector("#proveedorTable tbody");
      tableBody.innerHTML = "";
  
      for (let proveedor of data.data) {
        const row = document.createElement("tr");
        row.id = `proveedorRow_${proveedor.id}`;
  
        row.innerHTML = `
          <td class="bg-white shadow-none">${proveedor.id}</td>
          <td class="nombre_proveedor editable" contenteditable="false">${proveedor.nombre_proveedor}</td>
          <td class="nit editable" contenteditable="false">${proveedor.nit}</td>
          <td class="direccion editable" contenteditable="false">${proveedor.direccion}</td>
          <td class="telefono editable" contenteditable="false">${proveedor.telefono}</td>
          <td class="correo_electronico editable" contenteditable="false">${proveedor.correo_electronico}</td>
          <td class="bg-white shadow-none">
            <button type='button' onclick='enableEditing(${proveedor.id})' class='btn btn-primary editBtn'>
              <img src='${IMAGES_BASE_PATH}/pencil.svg'>
            </button>
            <button type='button' onclick='fnCreateUpdate("UPDATE", ${proveedor.id})' class='btn btn-success d-none saveBtn'>
              <img src='${IMAGES_BASE_PATH}/check.svg'>
            </button>
          </td>
          <td class="bg-white shadow-none">
            <button type='button' onclick='fnDelete(${proveedor.id})' class='btn btn-danger deleteBtn'>
              <img src='${IMAGES_BASE_PATH}/trash.svg'>
            </button>
            <button type='button' onclick='fnCancel(${proveedor.id})' class='btn btn-dark d-none cancelBtn'>
              <img src='${IMAGES_BASE_PATH}/cancel.svg'>
            </button>
          </td>
        `;
        tableBody.appendChild(row);
      }

      const newRow = document.createElement("tr");
      newRow.id = "proveedorRow_new";

      newRow.innerHTML = `
          <td class="bg-white shadow-none">+</td>
          <td class="nombre_proveedor editable" id="nombre_proveedor_nuevo" contenteditable="true"></td>
          <td class="nit editable" contenteditable="true"></td>
          <td class="direccion editable" contenteditable="true"></td>
          <td class="telefono editable" contenteditable="true"></td>
          <td class="correo_electronico editable" contenteditable="true"></td>
          <td class="bg-white shadow-none">
            <button type='button' onclick='fnCreateUpdate("CREATE", "new")' class='btn btn-success saveBtn'>
              <img src='${IMAGES_BASE_PATH}/check.svg'>
            </button>
          </td>
          <td class="bg-white shadow-none">
            <button type='button' onclick='deleteNewRow()' class='btn btn-dark cancelBtn'>
              <img src='${IMAGES_BASE_PATH}/cancel.svg'>
            </button>
          </td>
        `;
        tableBody.appendChild(newRow);


        addValidationListeners();
    } catch (error) {
      console.error("Error:", error);
      alert("Error al cargar los proveedores.");
    }
    scrollToBottom();
  }

  function fnCreateUpdate(action = "CREATE", id = "") {
    const row = document.querySelector(`#proveedorRow_${id}`);
    const formulario = document.createElement("form");
    formulario.id = `form_${id}`;
    fillDynamicForm(row, formulario);
  
    let url = "";
    let formData = new FormData(formulario);
    if (action === "CREATE") {
      url = "app/controllers/proveedor_controller.php?action=store";
    } else {
      url = "app/controllers/proveedor_controller.php?action=update";
      formData.append("id", id);
    }
  
    fetch(`${url}`, {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (response.ok) {
          response.json().then(async (data) => {
            //alert(data.data);
            await loadProveedores();
          });
        } else {
          response.json().then((data) => {
            const errors = data.data.error;
            const row = document.querySelector(`#proveedorRow_${id}`);
            removeErrorMessages(row);
            addErrorMessages(row, errors);
          });
        }
      })
      .catch(function (error) {
        alert("Error en el sistema.");
      });
  }

  function createInputAndAppendToForm(form, type, name, value) {
    const input = document.createElement("input");
    input.type = type;
    input.name = name;
    input.value = value;
    form.appendChild(input);
  }

  function fillDynamicForm(row, formulario) {
    createInputAndAppendToForm(
      formulario,
      "text",
      "nombre_proveedor",
      getElementValueFromRow(row, "nombre_proveedor")
    );
    createInputAndAppendToForm(
      formulario,
      "text",
      "nit",
      getElementValueFromRow(row, "nit")
    );
    createInputAndAppendToForm(
      formulario,
      "text",
      "direccion",
      getElementValueFromRow(row, "direccion")
    );
    createInputAndAppendToForm(
      formulario,
      "text",
      "telefono",
      getElementValueFromRow(row, "telefono")
    );
    createInputAndAppendToForm(
      formulario,
      "text",
      "correo_electronico",
      getElementValueFromRow(row, "correo_electronico")
    );
  }

  function getElementValueFromRow(row, className) {
    const elementData = row.querySelector(`.${className}`);
    return elementData.textContent;
  }
  
  function removeErrorMessages(row) {
    const errorRow = row.nextElementSibling;
    if (errorRow && errorRow.querySelector(".error-message")) {
      errorRow.remove();
    }
  }
  
  function addErrorMessages(row, errors) {
    const tableRow = document.createElement("tr");
    const cellClasses = [
      "nombre_proveedor",
      "nit",
      "direccion",
      "telefono",
      "correo_electronico",
    ];
    tableRow.appendChild(document.createElement("td"));
  
    for (const cellClass of cellClasses) {
      const errorContainer = document.createElement("td");
      if (errors[cellClass]) {
        errorContainer.classList.add("error-message");
        errorContainer.innerHTML = errors[cellClass];
      }
      tableRow.appendChild(errorContainer);
    }
    tableRow.appendChild(document.createElement("td"));
    tableRow.appendChild(document.createElement("td"));
    row.insertAdjacentElement("afterend", tableRow);
  }

  function enableEditing(id) {
    const row = document.querySelector(`#proveedorRow_${id}`);
    row.classList.add("editable-row");
    const dataCells = row.querySelectorAll(".editable");
    dataCells.forEach((dataCell) => {
        dataCell.setAttribute("data-original", dataCell.textContent);
        dataCell.setAttribute("contenteditable", true);
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
    const row = document.querySelector(`#proveedorRow_${id}`);
    row.classList.remove("editable-row");
    const dataCells = row.querySelectorAll(".editable");
    dataCells.forEach((dataCell) => {
        dataCell.textContent = dataCell.getAttribute("data-original");
        dataCell.setAttribute("contenteditable", false);
    });
  
    const editBtn = row.querySelector(".editBtn");
    const saveBtn = row.querySelector(".saveBtn");
    editBtn.classList.remove("d-none");
    saveBtn.classList.add("d-none");
  
    const deleteBtn = row.querySelector(".deleteBtn");
    const cancelBtn = row.querySelector(".cancelBtn");
    deleteBtn.classList.remove("d-none");
    cancelBtn.classList.add("d-none");
  
    removeErrorMessages(row);
  }

  function scrollToBottom() {
    let lastRow = document.getElementById("proveedorRow_new");
    lastRow.scrollIntoView({ behavior: "smooth" });
    let inputNombreProveedor = document.getElementById("nombre_proveedor_nuevo");
    inputNombreProveedor.focus();
  }
  
  function deleteNewRow() {
    const newRow = document.getElementById("proveedorRow_new");
    const dataCells = newRow.querySelectorAll(".editable");
    dataCells.forEach((dataCell) => {
        dataCell.textContent = "";
    });
    removeErrorMessages(newRow);
  }
  
  function addValidationListeners() {
    // Selector para los elementos td que deben contener solo números
    const numericTds = document.querySelectorAll(".nit, .telefono");
    numericTds.forEach(td => {
      td.addEventListener("input", function (event) {
        // Reemplaza cualquier caracter que no sea un dígito con una cadena vacía
        this.textContent = this.textContent.replace(/[^0-9]/g, "");
      });
    });
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

function generateProveedoresExcel() {
  window.open("config/generate_excel_proveedores.php", "_blank");
}

// Llama a la función cuando se carga la página
document.addEventListener("DOMContentLoaded", loadProveedores);