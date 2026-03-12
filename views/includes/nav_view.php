<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>/">
            <i class="fas fa-address-book me-2"></i> Agenda de Contactos
        </a>
        
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/">Inicio</a>
                </li>
                <li class="nav-item">
                    <?php if (!empty($_SESSION['user'])): ?>
                        <a class="nav-link" href="<?= BASE_URL ?>/contactos">Contactos</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
        <div class="d-flex">
            <?php if (!empty($_SESSION['user'])): ?>
                <a class="nav-link text-light me-2" href="<?= BASE_URL ?>/logout">Salir</a>
            <?php else: ?>
                <a class="nav-link text-light me-2" href="<?= BASE_URL ?>/login">Acceder</a>
                <a class="nav-link text-light" href="<?= BASE_URL ?>/register">Registrarse</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
