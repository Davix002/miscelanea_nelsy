function fnCreateRow() {
  document.getElementById('productRow_new').classList.remove('d-none');
}

function deleteNewRow() {
  document.getElementById('productRow_new').classList.add('d-none');
}

function enableEditing(id) {
  // Obtén todas las celdas de la fila por el id del producto
  const row = document.querySelector(`#productRow_${id}`);
  const inputs = row.querySelectorAll('.editable');

  // Quita el atributo readonly para los inputs y disabled para los select
  inputs.forEach(input => {
    if (input.tagName === "SELECT") {
      input.removeAttribute('disabled');
    } else {
      input.removeAttribute('readonly');
    }
  });

  // Muestra el botón de guardar y esconde el de editar
  const editBtn = row.querySelector('.editBtn');
  const saveBtn = row.querySelector('.saveBtn');
  editBtn.classList.add('d-none');
  saveBtn.classList.remove('d-none');
}

function fnCreateUpdate(action = "CREATE", id = "") {
   let url = "";
   let formElement;
  if (action === "CREATE") {
    url = "app/controllers/producto_controller.php?action=store";
    formElement = document.getElementById("form_new");
  } else {
    url = "app/controllers/producto_controller.php?action=update";
    formElement = document.getElementById("form_" + id);
  }

  //var formElement = document.getElementById('form_' + id); // idProducto es el id del producto que deseas enviar
  var formData = new FormData(formElement);

  
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

          const inputs = formElement.querySelectorAll("input");

          const selects = formElement.querySelectorAll("select");
    
          // Remove previous error messages
          removeErrorMessages(inputs);
          removeErrorMessages(selects);
          // Add new error messages
          addErrorMessages(formElement, errors);
        });
      }
    })
    .catch(function (error) {
      alert("Error en el sistema.");
    });
}

function removeErrorMessages(inputs) {
  inputs.forEach((input) => {
    const errorContainer = input.nextElementSibling;
    if (errorContainer && errorContainer.classList.contains("error-message")) {
      errorContainer.remove();
    }
  });
}

function addErrorMessages(form, errors) {
  for (const error in errors) {
    const input = form.querySelector(`#${error}`);
    const errorContainer = document.createElement("div");

    errorContainer.className = "error-message";
    errorContainer.innerHTML = errors[error];

    input.parentNode.insertBefore(errorContainer, null);
  }
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

let precio_compra = document.getElementById("precio_compra");
precio_compra.addEventListener("input", calcular_precio);

function roundToMultipleOf100(value) {
  return Math.round(value / 100) * 100;
}

function calcular_precio() {
  let precio_compra_value = document.getElementById("precio_compra").value;

  if (precio_compra_value <= 0 || precio_compra_value == "") {
    document.getElementById("precio_venta").value = "";
    document.getElementById("precio_mayoreo").value = "";
  } else {
    let precio_venta = precio_compra_value * 1.25;
    precio_venta = roundToMultipleOf100(precio_venta) + 100;
    document.getElementById("precio_venta").value = precio_venta;

    let precio_mayoreo = document.getElementById("precio_venta").value * 0.9;
    precio_mayoreo = roundToMultipleOf100(precio_mayoreo);
    document.getElementById("precio_mayoreo").value = precio_mayoreo;
  }
}

const monedas = document.querySelectorAll(".moneda");
monedas.forEach((moneda) => {
  moneda.textContent = new Intl.NumberFormat("es-CO", {
    style: "currency",
    currency: "COP",
    minimumFractionDigits: 0,
  }).format(parseFloat(moneda.textContent));
});
