<?php
/**
 * IndexController
 *
 * Controlador principal encargado de mostrar la página de inicio del sistema de gestión de contactos.
 * Recupera información general como el número total de contactos y los contactos más recientes.
 *
 * @package App\Controllers
 */

namespace App\Controllers;

use App\Services\ContactoService;
use App\Exceptions\DataBaseException;

class IndexController extends BaseController
{
    /**
     * Servicio para operaciones con contactos.
     * @var ContactoService
     */
    private ContactoService $contactoService;

    /**
     * Constructor. Inicializa el servicio de contactos.
     */
    public function __construct()
    {
        parent::__construct();
        $this->contactoService = new ContactoService();
    }

    /**
     * Acción principal que muestra la página de inicio.
     * Obtiene el total de contactos y los más recientes.
     *
     * @return void
     */
    public function indexAction(): void
    {
        try {
            // Obtenemos el número total de contactos
            $totalContactos = $this->contactoService->getTotalContactos();

            $contactosRecientes = $this->contactoService->getUltimosContactos(RECENT_CONTACTS_LIMIT);

            $this->renderHTML(VIEWS_DIR . '/index/index_view.php', [
                'titulo'  => 'Inicio | Agenda Pro',
                'total'   => $totalContactos,
                'ultimos' => $contactosRecientes
            ]);

        } catch (DataBaseException $e) {
            $this->mostrarErrorDB($e->getMessage());

        } catch (\Exception $e) {
            $this->mostrarError("No se pudo cargar el panel de control: " . $e->getMessage(), 500);
        }
    }
}