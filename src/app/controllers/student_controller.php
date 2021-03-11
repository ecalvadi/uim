<?php

/**
 * StudentController
 * Controlador para ver datos del estudiante
 *
 * @package    APP
 * @subpackage AppController
 * @author     Héctor Álvarez Díaz <h.alvarez03@ufromail.cl.cl>
 */
class StudentController extends AppController {

    /**
     * Método principal
     */
    public function index() {
    }

    /**
     * Método para mostrar la vista de perfil del estudiante,
     * Mostrará todos los datos de este.
     *
     * @param $studentId
     */
    public function profile($studentId) {
        $this->student = (new Student)->find_first("conditions: id='$studentId'");
        $usrId = $this->student->user_id;
        $this->user = (new User)->find_first("conditions: id='$usrId'");

    }
}