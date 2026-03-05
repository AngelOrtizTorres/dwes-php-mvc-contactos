<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-plus-circle text-success"></i> <?= htmlspecialchars($data['titulo'] ?? 'Agregar nuevo contacto') ?></h1>
                <a href="<?= BASE_URL ?>/contactos" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>

            <?php if (!empty($data['general_error'])): ?>
                <div class="alert alert-danger shadow-sm">
                    <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($data['general_error']) ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/contactos/crear" method="POST" class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-address-book"></i> Nueva ficha de contacto</h5>
                </div>
                
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="nombre" class="font-weight-bold">Nombre Completo</label>
                        <input type="text" name="nombre" id="nombre" 
                               class="form-control <?= isset($data['errors']['nombre']) ? 'is-invalid' : '' ?>" 
                               placeholder="Ej: Juan Pérez"
                               value="<?= htmlspecialchars($data['form']['nombre'] ?? '') ?>">
                        <?php if (isset($data['errors']['nombre'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($data['errors']['nombre']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="font-weight-bold">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" 
                                   class="form-control <?= isset($data['errors']['telefono']) ? 'is-invalid' : '' ?>" 
                                   placeholder="600 000 000"
                                   value="<?= htmlspecialchars($data['form']['telefono'] ?? '') ?>">
                            <?php if (isset($data['errors']['telefono'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($data['errors']['telefono']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="font-weight-bold">Correo Electrónico</label>
                            <input type="email" name="email" id="email" 
                                   class="form-control <?= isset($data['errors']['email']) ? 'is-invalid' : '' ?>" 
                                   placeholder="ejemplo@correo.com"
                                   value="<?= htmlspecialchars($data['form']['email'] ?? '') ?>">
                            <?php if (isset($data['errors']['email'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($data['errors']['email']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar en Agenda
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>