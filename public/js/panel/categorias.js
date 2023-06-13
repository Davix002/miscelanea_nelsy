function fn_modal_categoria(action = "CREATE", id) {
  let titleModal = document.getElementById("staticBackdropLabel");
  if (action === "CREATE") {
    titleModal.textContent = "Nueva categoría";

    document.querySelector("#nombre_categoria").value = "";
    document.querySelector("#descripcion").value = "";

    const btnCreateUpdate = document.querySelector("#btnCreateUpdate");
    btnCreateUpdate.setAttribute("onclick", "fnCreateUpdate('CREATE', '')");
  } else {
    const btnCreateUpdate = document.querySelector("#btnCreateUpdate");
    btnCreateUpdate.setAttribute(
      "onclick",
      "fnCreateUpdate('UPDATE'," + id + ")"
    );
    // Consultando categoría
    fetch(`app/controllers/categoria_controller.php?action=show&id=${id}`, {
      method: "GET",
    })
      .then((response) => {
        if (response.ok) {
          response.json().then((data) => {
            const categoria = data.data;

            document.querySelector("#nombre_categoria").value =
              categoria.nombre_categoria;
            document.querySelector("#descripcion").value =
              categoria.descripcion;
          });
        } else {
          alert("Error en el sistema");
        }
      })
      .catch(function (error) {
        alert("Error en el sistema.");
      });

    titleModal.textContent = "Modificar categoría";
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
    url = "app/controllers/categoria_controller.php?action=store";
  } else {
    url = "app/controllers/categoria_controller.php?action=update";
  }
  const form_modal_categoria = document.querySelector("#form-modal-categoria");

  const Form_data = new FormData(form_modal_categoria);

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
          window.location.href = "categorias";
        });
      } else {
        response.json().then((data) => {
          const errors = data.data.error;

          const inputs = form_modal_categoria.querySelectorAll("input");

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
            const input = form_modal_categoria.querySelector(`#${error}`);
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

    fetch(`app/controllers/categoria_controller.php?action=destroy`, {
      method: "POST",
      body: Form_data,
    })
      .then((response) => {
        if (response.ok) {
          response.json().then((data) => {
            alert(data.data);
            window.location.href = "categorias";
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

function printCategoriesToPDF() {
  window.open("config/generate_pdf_categorias.php", "_blank");
}

function normalizeText(text) {
  return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
}

function filterCategories(){
  const input = document.getElementById("searchCategorie");
  const filter = normalizeText(input.value);
  const table = document.getElementById("categorieTable");
  const rows = table.getElementsByTagName("tr");

  for (let i = 0; i < rows.length; i++) {
      const cells = rows[i].getElementsByTagName("td");
      if (cells.length > 0) {
          const categorieName = cells[1].textContent || cells[1].innerText;
          if (normalizeText(categorieName).indexOf(filter) > -1) {
              rows[i].style.display = "";
          } else {
              rows[i].style.display = "none";
          }
      }
  }
}