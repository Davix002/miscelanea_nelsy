const formLogin = document.getElementById("form-login");

formLogin.addEventListener("submit", function (e) {
    e.preventDefault();
    const Form_data = new FormData(formLogin);
    fetch("app/controllers/UserController.php?action=login", {
        method: "POST",
        body: Form_data,
    })
        .then((response) => {
            if (response.ok) {
                window.location.href = "panel";
            } else {
                response.json().then((data) => {
                    alert(data.data.error)
                });
            }
        })
        .catch(function (error) {
            alert("Error en el sistema.");
        });
});
