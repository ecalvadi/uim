<?php

/**
 * Clase AclSecurity para asegurar el acceso a los controladores y
 * métodos por rol de usuario.
 * Esta clase permite agregar acceso a controladores y acciones con
 * gran precisión.
 *
 * @author Héctor Álvarez<h.alvarez03@ufromail.cl>
 */
class AclSecurity
{

    /**
     * Método para asegurar las rutas, se le envían los roles que posee el usuario
     * y se verifica a qué controladores y acciones tiene acceso el usuario,
     * si no hay usuario, se tomará como usuario "invitado"
     * @param $user_roles
     * @return false
     */
    public function acl($user_roles = [1])
    {
        $access = false;

        //Se descompone la URL
        $access_url = (new KumbiaRouter())->rewrite(GLOBAL_URL);
        if (!isset($access_url["controller"])) {
            $access_url["controller"] = "index";
        }

        if (!isset($access_url["action"])) {
            $access_url["action"] = "index";
        }

        //En esta sección se agregarán las rutas y los permisos a cada rol de usuario
        //el orden es el siguiente:
        //"ruta del controlador" => ["Acción 1", "Acción 2", "Acción 3"...]
        //donde ruta del controlador es el nombre del controlador sin el "_controller"
        //y las accioes son los métodos o fucniones públicas dentro del controlador.

        $admin_access = [
            "index"     => ["index"],
            "salir"     => ["index"]
        ];

        $professor_access = [
            "index"     => ["index"],
            "courses"   => ["index", "detail"],
            "student"   => ["profile"],
            "test"      => ["new"],
            "salir"     => ["index"]
        ];

        $psycho_access = [
            "index"     => ["index"],
            "salir"     => ["index"]
        ];

        $student_access = [
            "index"     => ["index"],
            "salir"     => ["index"]
        ];

        $guest_access = [
            "index"     => ["index"]
        ];

        //Ejemplo de como crear roles a verificar
        //la arguitectura es la siguiente:
        //"name role" => [rol_id, nombre_rol, acceso]
        $roles = [
            "admin" => [
                "role_id" => 1,
                "role_name" => "Administrador",
                "role_access" => $admin_access
            ],
            "teacher" => [
                "role_id" => 2,
                "role_name" => "Profesor",
                "role_access" => $professor_access
            ],
            "psycho" => [
                "role_id" => 3,
                "role_name" => "Psicólogo",
                "role_access" => $psycho_access
            ],
            "student" => [
                "role_id" => 4,
                "role_name" => "estudiante",
                "role_access" => $student_access
            ],
            "guest" => [
                "role_id" => 5,
                "role_name" => "invitado",
                "role_access" => $guest_access
            ],
        ];

        //Recorremos los roles que nos entregan vs los que hay
        foreach ($user_roles as $urol) {
            foreach ($roles as $rl) {
                if ($urol == $rl["role_id"]) {
                    $access = $this->check_acl($access_url, $rl["role_access"]);
                    if ($access) return $access;
                }
            }
        }

        return $access;
    }

    /**
     * Método privado que recorre los rutas de acceso y si nuestro rol
     * coincide con la ruta, devuelve verdadero dando permiso de acceso
     * a la ruta.
     *
     * @param $access_url
     * @param $access
     * @return bool
     */
    private function check_acl($access_url, $access)
    {
        foreach ($access as $control_name => $actions) {
            if ($access_url["controller"] == $control_name) {
                foreach ($actions as $action) {
                    if ($access_url["action"] == $action) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}