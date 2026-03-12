<?php
namespace App\Controllers;

use Exception;
use App\Models\UserModel;

class AuthController extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }

    // Muestra el formulario de login
    public function loginAction(): void
    {
        $this->renderHTML(VIEWS_DIR . '/auth/login_view.php', ['titulo' => 'Acceder al sistema']);
    }

    // Mostrar y procesar registro
    public function registerAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->renderHTML(VIEWS_DIR . '/auth/register_view.php', ['titulo' => 'Registro de usuario']);
            return;
        }

        // POST: procesar registro
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password_confirm'] ?? '';

        $errors = [];
        if ($username === '' || strlen($username) < 3) {
            $errors[] = 'El nombre de usuario debe tener al menos 3 caracteres.';
        }
        if ($password === '' || strlen($password) < 4) {
            $errors[] = 'La contraseña debe tener al menos 4 caracteres.';
        }
        if ($password !== $password2) {
            $errors[] = 'Las contraseñas no coinciden.';
        }

        if (!empty($errors)) {
            $this->renderHTML(VIEWS_DIR . '/auth/register_view.php', ['titulo' => 'Registro de usuario', 'errors' => $errors, 'username' => htmlspecialchars($username)]);
            return;
        }

        try {
            $um = new UserModel();
            $existing = $um->getByUsername($username);
            if ($existing) {
                $this->renderHTML(VIEWS_DIR . '/auth/register_view.php', ['titulo' => 'Registro de usuario', 'errors' => ['El usuario ya existe.'], 'username' => htmlspecialchars($username)]);
                return;
            }

            $um->setUsername($username);
            $um->setPassword(password_hash($password, PASSWORD_DEFAULT));
            $um->set();

            // Redirigimos al login con parámetro success
            $this->redirect('/login?registered=1');
        } catch (\Exception $e) {
            $this->renderHTML(VIEWS_DIR . '/auth/register_view.php', ['titulo' => 'Registro de usuario', 'errors' => ['Error al crear el usuario: ' . $e->getMessage()], 'username' => htmlspecialchars($username)]);
        }
    }

    // Procesa el formulario de login
    public function authenticateAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
            return;
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Comprobamos credenciales contra la tabla users
        try {
            $um = new UserModel();
            $user = $um->getByUsername($username);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = ['username' => $user['username'], 'id' => $user['id']];
                $this->redirect('/contactos');
                return;
            }
        } catch (Exception $e) {
            // Si hay error de BD, mostramos mensaje genérico
            $this->renderHTML(VIEWS_DIR . '/auth/login_view.php', [
                'titulo' => 'Acceder al sistema',
                'error'  => 'Error al verificar credenciales',
                'username' => htmlspecialchars($username)
            ]);
            return;
        }

        // Fallo de autenticación
        $this->renderHTML(VIEWS_DIR . '/auth/login_view.php', [
            'titulo' => 'Acceder al sistema',
            'error'  => 'Usuario o contraseña incorrectos',
            'username' => htmlspecialchars($username)
        ]);
    }

    // Cierra la sesión y redirige a la página de login
    public function logoutAction(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
        $this->redirect('/login');
    }

}
