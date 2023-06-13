const registerForm = document.getElementById("register-form");

registerForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const Form_data = new FormData(registerForm);

    fetch("app/controllers/UserController.php?action=register", {
        method: "POST",
        body: Form_data,
    })
        .then((response) => {
            if (response.ok) {
                response.json().then((data) => {
                    alert(data.data);
                    window.location.href = 'login';
                })
            } else {
                if (response.status == 409) {
                    response.json().then((data) => {
                        alert(data.data.error)
                    });
                    return;
                }
                response.json().then((data) => {

                    const errors = data.data.error;

                    const inputs = registerForm.querySelectorAll('input');

                    // Remove previous error messages
                    inputs.forEach(input => {

                        const errorContainer = input.nextElementSibling; // Obtenemos el siguiente elemento despues del input
                        if (errorContainer) {
                            if (errorContainer.classList.contains('error-message')) {
                                errorContainer.remove();
                            }
                        }
                    });

                    // Add new error messages
                    for (const error in errors) {
                        const input = registerForm.querySelector(`#${error}`);
                        const errorContainer = document.createElement('div');

                        errorContainer.className = 'error-message';
                        errorContainer.innerHTML = errors[error];

                        // En la segunda posicion null indica que se inserte al final.
                        input.parentNode.insertBefore(errorContainer, null); // Insertando mensaje debajo del input.
                    }
                });
            }

        })
        .catch(function (error) {
            alert("Error en el sistema.")
        });
})
