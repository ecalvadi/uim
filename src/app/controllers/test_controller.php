<?php

/**
 * TestController
 * Controlador para manejar la creación de las pruebas, vista de la prueba y toma de la prueba.
 *
 * @package    APP
 * @subpackage AppController
 * @author     Héctor Álvarez Díaz <h.alvarez03@ufromail.cl.cl>
 */
class TestController extends AppController {

    /**
     * Método principal
     */
    public function index() {
        $user_id = $this->session['id'];

        $student = (new Student)->find_first("conditions: user_id='$user_id'");

        $tests = (new TestHasStudent)->find("conditions: course_student_student_id='$student->id'");


        $mytests = array();

        foreach($tests as $test ) {
            $test_data = (new Test)->find_first("conditions: id='$test->test_id'");

            $data = array(
                'id' => $test->id,
                'name' => $test_data->name,
                'desc' => $test_data->description,
                'start' => $test_data->start,
                'end' => $test_data->end
            );

            array_push($mytests, $data);
        }

        $this->my_tests = $mytests;
    }

    /**
     * Método para ingresar y ejecutar la prueba
     *
     * @param $student_test
     */
    public function realize($student_test) {


    }

    /**
     * Método para crear, editar y publicar una prueba
     *
     * @param $courseId
     * @param int $testId
     */
    public function create($courseId, $testId = 0) {
        if($testId != 0) {
            $this->test = (new Test())->find_first("conditions: id='$testId'");

            if(Input::hasPost('title')) {
                $this->test->name = Input::post('title');
                $this->test->description = Input::post('desc');
                $this->test->start = Input::post('start');
                $this->test->end = Input::post('end');;

                if(Input::hasPost('published')) {
                    if(!$this->test->published == '1') {
                        $this->test->published = '1';

                        //Avisar a los estudiantes de su prueba
                        $students = (new CourseStudent)->find("conditions: course_id='$courseId'");
                        foreach ($students as $asign) {
                            $test_asigned = new TestHasStudent();
                            $test_asigned->aproved = '0';
                            $test_asigned->course_student_id = $asign->id;
                            $test_asigned->course_student_course_id = $courseId;
                            $test_asigned->course_student_student_id = $asign->student_id;
                            $test_asigned->test_id = $testId;
                            $test_asigned->status_id = 1; //Sin responder

                            $test_asigned->create();
                        }
                    }
                }

                $this->test->save();
            }
        } else {
            $now = date("Y-m-d");
            $time = date("Y-m-d h:i:sa");

            $this->test = new Test();
            $this->test->name = ' ';
            $this->test->description = ' ';
            $this->test->creation = $now;
            $this->test->start = $time;
            $this->test->end = $time;
            $this->test->published = '0';
            $this->test->course_id = $courseId;

            if(!$this->test->create()) {
                Redirect::to('course');
            }
        }


    }

    public function edit($testId) {
        $this->test = (new Test)->find_first("conditions: id='$testId'");

    }
}