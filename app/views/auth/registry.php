<section class="bg-primary bg-gradient">
    <div class="container d-flex align-items-center justify-content-center vh-100">
        <form method="post" class="card col-12 col-md-8 col-lg-6" id="register-form">
            <div class="card-body p-5 d-grid gap-3">
                <h2 class="mb-0">Registro de usuario</h2>
                <span>Crea tu cuenta de usuario</span>
                <div>
                    <input type="text" name="nombres" id="nombres" class="form-control" placeholder="Nombres">
                </div>
                <div>
                    <input type="text" name="apellidos" id="apellidos" class="form-control" placeholder="Apellidos">
                </div>
                <div>
                    <input type="email" class="form-control" placeholder="Correo" name="email" id="email"/>
                </div>
                <div>
                    <input type="password" class="form-control" placeholder="Contraseña" name="password" id="password" />
                </div>
                <input type="submit" class="btn btn-primary" name="registro" value="Registrarse" />
                <span>
                    ¿Ya tienes una cuenta?
                    <a class="link-secondary fw-medium" href="login">Login</a>
                </span>
            </div>
        </form>
    </div>
</section>
<script src="public/js/auth/registry.js"></script>