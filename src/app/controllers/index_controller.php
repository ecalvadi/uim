<?php

/**
 * Controller por defecto si no se usa el routes
 *
 */
class IndexController extends AppController
{

    public function index()
    {
        if(Input::hasPost(rut)) {
            $this->login(Input::post('rut'), Input::post('email'));
        }
    }

    /**
     * Método encargado de manerjar el login, en caso de no encontrar el usuario no hará nada
     *
     * @param String $email
     * @param String $pass
     */
    private function login($email, $pass = '') {
        $user = (new User)->find_first("conditions: rut='$email'");

        //Si encontramos al usuario
        if($user) {
            //Si la contraseña es igual a la entregada se guardarán los datos del login
            if($user->password == $pass) {
                //this-session
            }
        }
    }

    /**
     * Método para guardar la sesión del usuario loggeado
     *
     * @param $id
     * @param $name
     * @param $email
     * @param $role
     */
    private function set_session($id, $name, $email, $role) {
        //Guardamos la sesión en las variables de nuestro espacio de trabajo (memoria server)
        Session::set('id', $id, $this->namespace);
        Session::set('name', $name, $this->namespace);
        Session::set('email', $email, $this->namespace);
        Session::set('role', $role, $this->namespace);

        //Actualizamos la sesión actual en ejecución para tener los datos más a mano,
        //esto viene de app\libs\app_controller.php
        $this->session = array(
            'id'    => $id,
            'name'  => $name,
            'email' => $email,
            'role'  => $role
        );
    }
}
