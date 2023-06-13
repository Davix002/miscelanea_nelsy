var reconocimiento = new webkitSpeechRecognition();

reconocimiento.onresult = function (event) {
  var producto = "";
  for (var i = event.resultIndex; i < event.results.length; i++) {
    if (event.results[i].isFinal) {
      producto = event.results[i][0].transcript;
    } else {
      producto += event.results[i][0].transcript;
    }
  }
  document.getElementById("buscador").value = producto;
  filterProducts();
};

function iniciarGrabacion() {
  document.getElementById("buscador").value = "";
  document.getElementById("buscador").focus();
  reconocimiento.start();
}

function normalizeText(text) {
  return text
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .toLowerCase();
}

function filterProducts() {
  const input = document.getElementById("buscador");
  const filter = normalizeText(input.value);
  const table = document.getElementById("productTable");
  const rows = table.getElementsByTagName("tr");
  const header = table.querySelector("thead tr");

  for (let i = 0; i < rows.length; i++) {
    const cells = rows[i].getElementsByTagName("td");
    if (cells.length > 0) {
      const productName = cells[1].textContent || cells[1].innerText;
      if (input.value == "") {
        rows[i].style.display = "none";
        header.style.display = "none";
      } else {
        if (normalizeText(productName).indexOf(filter) > -1) {
          rows[i].style.display = "";
          header.style.display = "";
        } else {
          rows[i].style.display = "none";
        }
      }
    }
  }
}
