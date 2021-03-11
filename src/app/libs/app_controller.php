<?php
/**
 * @see Controller nuevo controller
 */
require_once CORE_PATH . 'kumbia/controller.php';

/**
 * Controlador principal que heredan los controladores
 *
 * Todos las controladores heredan de esta clase en un nivel superior
 * por lo tanto los métodos aquií definidos estan disponibles para
 * cualquier controlador.
 *
 * @category Kumbia
 * @package Controller
 */
abstract class AppController extends Controller
{
    //Array global que guarda la sessión de toda
    //la aplicación que exienden de AppController,
    //se inicializa en null
    public $session = array();
    public $namespace = 'uim';

    /**
     * Método que se inicializa en cada llamado, este
     * método obtiene los parámetros de la sesión y los
     * guarda en la variable $session
     */
    final protected function initialize()
    {
        //Guardamos la sesión en el namespace simulac
        //Esto puede crecer dependiendo de los datos
        //de los usuarios administradores

        $this->session = array(
            'id' => Session::get('id', $this->namespace),
            'name' => Session::get('name', $this->namespace),
            'email' => Session::get('email', $this->namespace),
            'role' => Session::get('role', $this->namespace),
        );

        if(!$this->session["role"]) {
            if(!(new AclSecurity())->acl()) Redirect::to("index");
        } else {
            if(!(new AclSecurity())->acl([$this->session["role"]])) Redirect::to("index");
        }

    }

    final protected function finalize()
    {
        
    }

}
