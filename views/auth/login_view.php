<div class="container mt-5" style="max-width:480px">
    <h1 class="h3 mb-3"><?= htmlspecialchars($data['titulo'] ?? 'Acceder') ?></h1>

    <?php if (!empty($data['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($data['error']) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>/login">
        <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input name="username" class="form-control" value="<?= $data['username'] ?? '' ?>" required />
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input name="password" type="password" class="form-control" required />
        </div>
        <div class="mb-3">
            <button class="btn btn-primary">Acceder</button>
            <a class="btn btn-link" href="<?= BASE_URL ?>/">Volver</a>
        </div>
    </form>
</div>
