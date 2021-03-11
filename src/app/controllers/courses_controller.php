<?php

/**
 * CoursesController
 * Controlador que obtiene la lista de cursos
 *
 * @package    APP
 * @subpackage AppController
 * @author     Héctor Álvarez Díaz <h.alvarez03@ufromail.cl.cl>
 */
class CoursesController extends AppController {

    /**
     * Método principal, acá obtenemos los cursos
     */
    public function index() {
        $userid = $this->session['id'];
        $this->courses = (new Course)->find("conditions: user_id='$userid' AND active='1'", "order: id desc");
    }

    /**
     * Método para ver el detalle del curso (Estudiantes y pruebas)
     *
     * @param $courseId
     */
    public function detail($courseId) {

        //Obtener info del curso
        $this->course = (new Course)->find_first("conditions: id='$courseId'");

        //Obtener los estudiantes del curso
        $studentsCourse = (new CourseStudent)->find("conditions: course_id='$courseId'");
        $this->students = array();
        $auxArr = array();

        foreach($studentsCourse as $stud) {
            $studData = (new Student)->find_first("conditions: id='$stud->student_id'");
            $usrData = (new User)->find_first("conditions: id='$studData->user_id'");

            $auxStudent = array(
                'id' => $stud->id,
                'name' => $usrData->name,
                'registration' => $studData->registration,
                'birth' => $studData->birthdate,
                'active' => $studData->active
            );

            array_push($auxArr, $auxStudent);
        }

        $this->students = $auxArr;

        //Obtener las pruebas del curso
        $this->tests = (new Test)->find("conditions: course_id='$courseId'");

    }
}