function fn_modal_proveedor(action = "CREATE", id) {
  let titleModal = document.getElementById("staticBackdropLabel");
  if (action === "CREATE") {
    titleModal.textContent = "Nuevo proveedor";

    document.querySelector("#nombre_proveedor").value = "";
    document.querySelector("#nit").value = "";
    document.querySelector("#direccion").value = "";
    document.querySelector("#telefono").value = "";
    document.querySelector("#correo_electronico").value = "";

    const btnCreate = document.querySelector("#btnCreateUpdate");
    btnCreate.setAttribute("onclick", "fnCreateUpdate('CREATE', '')");
  } else {
    const btnCreate = document.querySelector("#btnCreateUpdate");
    btnCreate.setAttribute("onclick", "fnCreateUpdate('UPDATE'," + id + ")");
    // Consultando proveedor
    fetch(`app/controllers/proveedor_controller.php?action=show&id=${id}`, {
      method: "GET",
    })
      .then((response) => {
        if (response.ok) {
          response.json().then((data) => {
            const proveedor = data.data;

            document.querySelector("#nombre_proveedor").value =
              proveedor.nombre_proveedor;
            document.querySelector("#nit").value = proveedor.nit;
            document.querySelector("#direccion").value = proveedor.direccion;
            document.querySelector("#telefono").value = proveedor.telefono;
            document.querySelector("#correo_electronico").value =
              proveedor.correo_electronico;
          });
        } else {
          alert("Error en el sistema");
        }
      })
      .catch(function (error) {
        alert("Error en el sistema.");
      });

    titleModal.textContent = "Modificar proveedor";
  }

  const btnModal = document.querySelector("#btnAbrirModal");

  // Crear un objeto Modal
  const modal = new bootstrap.Modal(btnModal);
  // Mostrar el modal
  modal.show();
}

function fnCreateUpdate(action = "CREATE", id = "") {
  let url = "";
  if (action === "CREATE") {
    url = "app/controllers/proveedor_controller.php?action=store";
  } else {
    url = "app/controllers/proveedor_controller.php?action=update";
  }
  const form_modal_proveedor = document.querySelector("#form-modal-proveedor");

  const Form_data = new FormData(form_modal_proveedor);

  if (action === "UPDATE") {
    Form_data.append("id", id);
  }

  fetch(`${url}`, {
    method: "POST",
    body: Form_data,
  })
    .then((response) => {
      if (response.ok) {
        response.json().then((data) => {
          alert(data.data);
          window.location.href = "proveedores";
        });
      } else {
        response.json().then((data) => {
          const errors = data.data.error;

          const inputs = form_modal_proveedor.querySelectorAll("input");

          // Remove previous error messages
          inputs.forEach((input) => {
            const errorContainer = input.nextElementSibling;
            if (errorContainer) {
              if (errorContainer.classList.contains("error-message")) {
                errorContainer.remove();
              }
            }
          });

          // Add new error messages
          for (const error in errors) {
            const input = form_modal_proveedor.querySelector(`#${error}`);
            const errorContainer = document.createElement("div");

            errorContainer.className = "error-message";
            errorContainer.innerHTML = errors[error];

            input.parentNode.insertBefore(errorContainer, null);
          }
        });
      }
    })
    .catch(function (error) {
      alert("Error en el sistema.");
    });
}

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
          response.json().then((data) => {
            alert(data.data);
            window.location.href = "proveedores";
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