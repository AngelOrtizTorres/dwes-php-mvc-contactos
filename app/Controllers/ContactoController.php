<?php
namespace App\Controllers;

use App\Services\ContactoService;
use App\Forms\ContactoForm;
use App\Models\DatabaseException;

class ContactoController extends BaseController
{
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
        } catch (DatabaseException $e) {
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
        } catch (DatabaseException $e) {
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
        } catch (DatabaseException $e) {
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
        try {
            $detalle = $this->contactoService->obtenerContacto($id);

            if (!$detalle) {
                $this->mostrarError("El contacto solicitado no existe.", 404);
                return;
            }

            $form = $this->contactoForm->getDefaultData();
            $form = array_merge($form, $detalle['contacto']);
            $form = $this->contactoForm->sanitizeForOutput($form);

            $this->renderHTML(VIEWS_DIR . '/contactos/editar_view.php', [
                'titulo' => 'Editar contacto',
                'form'   => $form,
                'contacto' => $detalle['contacto']
            ]);
        } catch (DatabaseException $e) {
            $this->mostrarErrorDB($e->getMessage());
        }
    }


    public function updateAction(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/contactos');
            return;
        }

        $datos = $_POST;
        $validacion = $this->contactoForm->validate($datos);

        if (!$validacion['is_valid']) {
            $this->renderHTML(VIEWS_DIR . '/contactos/editar_view.php', [
                'titulo' => 'Corregir datos del contacto',
                'form'   => $this->contactoForm->sanitizeForOutput($validacion['form']),
                'errors' => $this->contactoForm->sanitizeForOutput($validacion['errors'])
            ]);
            return;
        }

        try {
            $this->contactoService->actualizarContacto($id, $validacion['data']);
            $this->redirect('/contactos?success=updated');
        } catch (DatabaseException $e) {
            $this->renderHTML(VIEWS_DIR . '/contactos/editar_view.php', [
                'titulo'        => 'Error de persistencia',
                'form'          => $this->contactoForm->sanitizeForOutput($validacion['form']),
                'general_error' => 'No se pudo actualizar el contacto. Intente de nuevo más tarde.'
            ]);
        } catch (\Exception $e) {
            $this->mostrarError("Ocurrió un error crítico: " . $e->getMessage(), 500);
        }
    }

    public function deleteAction(int $id): void
    {
        try {
            $this->contactoService->eliminarContacto($id);
            $this->redirect('/contactos?success=deleted');
        } catch (DatabaseException $e) {
            $this->mostrarErrorDB($e->getMessage());
        } catch (\Exception $e) {
            $this->mostrarError("Ocurrió un error crítico: " . $e->getMessage(), 500);
        }
    }
}