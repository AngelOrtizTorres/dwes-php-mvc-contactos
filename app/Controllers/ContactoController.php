<?php
/*****************************************************
 * TAREA 1: Bloque de documentación del archivo
 *
 * CLASE CONTACTOCONTROLLER
 * 
 * Gestiona todas las acciones relacionadas con los contactos de la agenda:
 * - Listar, ver, crear, editar y eliminar contactos.
 * - Interactúa con ContactoService para la lógica de negocio y con
 *   ContactoForm para la validación de datos del formulario.
 * - Hereda de BaseController para reutilizar la lógica de renderizado y redirección.
 * 
 *****************************************************/

/*****************************************************
 * TAREA 3: Espacio de nombres y uso de clases
 *****************************************************/
namespace App\Controllers;

use App\Services\ContactoService;
use App\Forms\ContactoForm;
use App\Models\DatabaseException;
use Exception;

class ContactoController extends BaseController 
{
    private ContactoService $contactoService;
    private ContactoForm $contactoForm;
    
    /*****************************************************
     * TAREA 2: Documentación de métodos
     *****************************************************/

    /**
     * Constructor. Inicializa los servicios y formularios necesarios.
     */
    public function __construct() 
    {
        parent::__construct();
        // Requiere que el usuario esté autenticado para todas las acciones de contactos
        $this->requireLogin();
        $this->contactoService = new ContactoService();
        $this->contactoForm    = new ContactoForm();
    }

    /**
    * Muestra la página principal con el listado de todos los contactos.
    * Permite filtrar los resultados mediante un parámetro de búsqueda.
     */
    public function indexAction(): void
    {
        $filtros = ['q' => $_GET['q'] ?? null];
    
        try {
            $contactos = $this->contactoService->obtenerListado($filtros);
            $this->renderHTML(VIEWS_DIR . '/contactos/listar_view.php', [
                'titulo'    => 'Listado de Contactos',
                'contactos' => $contactos,
                'filtros'   => $filtros,
            ]);
        } catch (DataBaseException $e) {
            $this->mostrarError("Error al conectar con la base de datos: " . $e->getMessage(), 500);
        }
    }

    /**
    * Muestra la ficha detallada de un contacto específico.
    *
     */
    public function showAction(int $id): void
    {
        try {
            $contacto = $this->contactoService->obtenerContacto($id);
            
            if (!$contacto) {
                $this->mostrarError("El contacto solicitado no existe.", 404);
                return;
            }
        
            $this->renderHTML(VIEWS_DIR . '/contactos/ver_view.php', [
                'titulo'   => "Ficha de Contacto",
                'contacto' => $contacto['contacto']
            ]);
        } catch (DataBaseException $e) {
            $this->mostrarError("Error al conectar con la base de datos: " . $e->getMessage(), 500);
        }
    }

    /**
    * Muestra el formulario para crear un nuevo contacto.
     */
    public function createAction(): void
    {
        $form = $this->contactoForm->getDefaultData();
        $form = $this->contactoForm->sanitizeForOutput($form);
   
        $this->renderHTML(VIEWS_DIR . '/contactos/agregar_view.php', [
            'titulo' => 'Agregar nuevo contacto',
            'form'   => $form
        ]);
    }

    /**
    * Procesa los datos enviados desde el formulario de creación.
    * Valida los datos y, si son correctos, crea el nuevo contacto.
     */
    public function storeAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/contactos');
            return;
        }

        $datos = $_POST;
        $validacion = $this->contactoForm->validate($datos);

        if (!$validacion['is_valid']) {
            $this->renderHTML(VIEWS_DIR . '/contactos/agregar_view.php', [
                'titulo' => 'Corregir datos del contacto',
                'form'   => $this->contactoForm->sanitizeForOutput($validacion['form']),
                'errors' => $this->contactoForm->sanitizeForOutput($validacion['errors'])
            ]);
            return;
        }

        try {
            $this->contactoService->crearContacto($validacion['data']);
            $this->redirect('/contactos?success=created');
        } catch (DataBaseException $e) {
            $this->renderHTML(VIEWS_DIR . '/contactos/agregar_view.php', [
                'titulo'        => 'Error de persistencia',
                'form'          => $this->contactoForm->sanitizeForOutput($validacion['form']),
                'general_error' => 'No se pudo guardar el contacto. ' . $e->getMessage()
            ]);
        } catch (Exception $e) {
            $this->mostrarError("Ocurrió un error crítico: " . $e->getMessage(), 500);
        }
    }

    /**
    * Muestra el formulario para editar un contacto existente.
    *
     */
    public function editAction(int $id): void
    {
        try {
            $contacto = $this->contactoService->obtenerContacto($id);

            if (!$contacto) {
                $this->mostrarError("El contacto que intentas editar no existe.", 404);
                return;
            }

            $form = $this->contactoForm->getDefaultData();
            $form = array_merge($form, $contacto['contacto']);
            $form = $this->contactoForm->sanitizeForOutput($form);


            $this->renderHTML(VIEWS_DIR . '/contactos/editar_view.php', [
                'titulo' => 'Editar contacto',
                'form'   => $form,
                'contacto' => $contacto['contacto'],
                'id' => $id
            ]);

        } catch (DataBaseException $e) {
            $this->mostrarError("Error al cargar los datos del contacto: " . $e->getMessage(), 500);
        }
    }

    /**
    * Procesa los datos enviados desde el formulario de edición.
    *
     */
    public function updateAction(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/contactos');
            return;
        }

        $datos = $_POST;
        $validacion = $this->contactoForm->validate($datos);

        if (!$validacion['is_valid']) {
            // Si hay errores, volvemos a mostrar el formulario de edición con los errores.
            $this->renderHTML(VIEWS_DIR . '/contactos/editar_view.php', [
                'titulo' => 'Corregir datos del contacto',
                'form'   => $this->contactoForm->sanitizeForOutput($validacion['form']),
                'errors' => $this->contactoForm->sanitizeForOutput($validacion['errors']),
                'id'     => $id
            ]);
            return;
        }

        try {
            $this->contactoService->actualizarContacto($id, $validacion['data']);
            $this->redirect('/contactos?success=updated');
        } catch (DataBaseException $e) {
            $this->renderHTML(VIEWS_DIR . '/contactos/editar_view.php', [
                'titulo'        => 'Error al actualizar',
                'form'          => $this->contactoForm->sanitizeForOutput($validacion['form']),
                'general_error' => 'No se pudo actualizar el contacto. ' . $e->getMessage(),
                'id'            => $id
            ]);
        }
    }

    /**
    * Elimina un contacto de la base de datos.
    * La petición debe ser por POST para mayor seguridad.
     */
    public function deleteAction(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/contactos');
            return;
        }

        try {
            $this->contactoService->eliminarContacto($id);
            $this->redirect('/contactos?success=deleted');
        } catch (DataBaseException $e) {
            // Un error común podría ser que el contacto ya no exista.
            $this->mostrarError('No se pudo eliminar el contacto. ' . $e->getMessage(), 500);
        }
    }
}