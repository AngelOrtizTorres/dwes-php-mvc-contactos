<?php
/**
 * ContactoController
 * 
 * Controlador encargado de gestionar las operaciones CRUD de contactos.
 * 
 * Herencia:
 * Todos los controladores heredan de BaseController para reutilizar funcionalidades comunes como renderizado de vistas, manejo de errores y redirecciones. Así evitamos repetir código en IndexController y ContactoController, centralizando la lógica compartida.
 * 
 * Buffers de salida:
 * ob_start() se utiliza para capturar la salida de las vistas antes de enviarla al navegador, permitiendo modificar o gestionar el contenido antes de mostrarlo. Si incluyéramos la vista directamente sin buffer, cualquier error o contenido se enviaría inmediatamente al navegador, dificultando el control de la respuesta.
 * 
 * Seguridad en POST:
 * Es fundamental comprobar que el método de la petición sea POST en storeAction y updateAction para evitar que datos sensibles sean enviados mediante GET, lo que podría exponer información en la URL y permitir ataques CSRF.
 * 
 * Limpieza de datos:
 * El método sanitizeForOutput se usa para limpiar los datos antes de mostrarlos en el HTML, previniendo ataques XSS. Nunca debemos mostrar directamente lo que el usuario escribió, ya que podría incluir scripts maliciosos.
 * 
 * @package App\Controllers
 */

// ...existing code...
    private ContactoService $contactoService;
    private ContactoForm $contactoForm;
    
    public function __construct() 
    {
        parent::__construct();
        $this->contactoService = new ContactoService();
        $this->contactoForm    = new ContactoForm();
    }

    public function indexAction(): void
    {
        $filtros = [
            'q' => $_GET['q'] ?? null,
        ];
    
        try {
            $contactos = $this->contactoService->obtenerListado($filtros);
            $this->renderHTML(VIEWS_DIR . '/contactos/listar_view.php', [
                'titulo'    => 'Listado de Contactos',
                'contactos' => $contactos,
                'filtros'   => $filtros,
            ]);
        } catch (\App\Exceptions\DataBaseException $e) {
            $this->mostrarErrorDB($e->getMessage());
        }
    }


    public function showAction(int $id): void
    {
        try {
            $detalle = $this->contactoService->obtenerContacto($id);
            
            if (!$detalle) {
                $this->mostrarError("El contacto solicitado no existe.",404);
                return;
            }
        
            $this->renderHTML(VIEWS_DIR . '/contactos/ver_view.php', [
                'titulo'   => "Ficha de Contacto",
                'contacto' => $detalle['contacto']
            ]);
        } catch (\App\Exceptions\DataBaseException $e) {
            $this->mostrarErrorDB($e->getMessage());
        }
    }

    public function createAction(): void
    {
        $form = $this->contactoForm->getDefaultData();
        $form = $this->contactoForm->sanitizeForOutput($form);
   
        $this->renderHTML(VIEWS_DIR . '/contactos/agregar_view.php', [
            'titulo' => 'Agregar nuevo contacto',
            'form'   => $form
        ]);
    }


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
        } catch (\App\Exceptions\DataBaseException $e) {
            $this->renderHTML(VIEWS_DIR . '/contactos/agregar_view.php', [
                'titulo'        => 'Error de persistencia',
                'form'          => $this->contactoForm->sanitizeForOutput($validacion['form']),
                'general_error' => 'No se pudo guardar el contacto. Intente de nuevo más tarde.'
            ]);
        } catch (\Exception $e) {
            $this->mostrarError("Ocurrió un error crítico: " . $e->getMessage(), 500);
        }
    }


    public function editAction(int $id): void
    {
        
    /*****************************************************
     * TAREA 3
     * 
     * Implementa el método
     * 
     * FIN TAREA
    */
    }


    public function updateAction(int $id): void
    {
    /*****************************************************
     * TAREA 4
     * 
     * Implementa el método
     * 
     * FIN TAREA
    */
    }

    public function deleteAction(int $id): void
    {
    /*****************************************************
     * TAREA 5
     * 
     * Implementa el método
     * 
     * FIN TAREA
    */
    }
}