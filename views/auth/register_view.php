<div class="container mt-5" style="max-width:480px">
    <h1 class="h3 mb-3"><?= htmlspecialchars($data['titulo'] ?? 'Registro') ?></h1>

    <?php if (!empty($data['errors'])): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($data['errors'] as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>/register">
        <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input name="username" class="form-control" value="<?= $data['username'] ?? '' ?>" required />
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input name="password" type="password" class="form-control" required />
        </div>
        <div class="mb-3">
            <label class="form-label">Repetir Contraseña</label>
            <input name="password_confirm" type="password" class="form-control" required />
        </div>
        <div class="mb-3">
            <button class="btn btn-primary">Registrarse</button>
            <a class="btn btn-link" href="<?= BASE_URL ?>/login">Acceder</a>
        </div>
    </form>
</div>
