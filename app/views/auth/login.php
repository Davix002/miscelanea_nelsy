<section class="bg-primary bg-gradient">
    <div class="container d-flex align-items-center justify-content-center vh-100">
        <form method="post" class="card col-12 col-md-8 col-lg-6" id="form-login">
            <div class="card-body p-5 d-grid gap-3">
                <h2 class="mb-0">Login</h2>
                <span>Introduce tu correo y contraseña</span>
                <input type="email" class="form-control" placeholder="Correo" name="email" />
                <input type="password" class="form-control" placeholder="Contraseña" name="password" />
                <input type="submit" class="btn btn-primary" name="btn_login" value="Login" />
                <span>¿Aún no tienes una cuenta?
                    <a class="link-secondary" href="register">Registrate</a>
                </span>
            </div>
        </form>
    </div>
</section>
<script src="public/js/auth/login.js"></script>