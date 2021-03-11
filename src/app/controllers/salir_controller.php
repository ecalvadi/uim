<?php

/**
 * SalirController
 * Controlador para cerrar sesión de todos los usuarios
 *
 * @package    APP
 * @subpackage AppController
 * @author     Héctor Álvarez Díaz <h.alvarez03@ufromail.cl.cl>
 */
class SalirController extends AppController {

    /**
     * Método principal, no se necesita más para cerrar la sesión
     */
    public function index() {
        //Eliminar todos los datos de la sesión
        Session::delete('id', $this->namespace);
        Session::delete('name', $this->namespace);
        Session::delete('email', $this->namespace);
        Session::delete('role', $this->namespace);

        Redirect::to('index');
    }
}