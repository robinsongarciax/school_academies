<?php
declare(strict_types=1);

namespace App\Controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Cake\Http\CallbackStream;

/**
 * SchoolCourses Controller
 *
 * @property \App\Model\Table\SchoolCoursesTable $SchoolCourses
 * @method \App\Model\Entity\SchoolCourse[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SchoolCoursesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Authorization->skipAuthorization();
        if ($this->Authentication->getIdentity()->role->name == 'ALUMNO' ) {
            // Traer los datos del estudiante
            $query = $this->SchoolCourses->Students->find('StudentInfo', [
                'user_id' => $this->Authentication->getIdentity()->getIdentifier()
            ])->all();

            $row = $query->first();

            $options = [
                'school_level_id' => $row->sl_id,
                'sex' => $row->sex
            ];

            // Traer los cursos relacionados con el grado escolar, sexo y la edad del estudiante
            $schoolCourses = $this->SchoolCourses->find('CoursesForStudent', $options)
                ->contain(['Subjects', 'Teachers', 'Terms'])
                ->all();
        } else {
            $schoolCourses = $this->SchoolCourses->find('all')
                ->contain(['Subjects', 'Teachers', 'Terms'])
                ->all();
        }

        $this->set(compact('schoolCourses'));
    }

    /**
     * View method
     *
     * @param string|null $id School Course id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $schoolCourse = $this->SchoolCourses->get($id, [
            'contain' => ['Subjects', 'Teachers', 'Terms', 'Schedules.Days', 'Students'],
        ]);
        $this->Authorization->authorize($schoolCourse);

        $days = $this->SchoolCourses->Schedules->Days->find('list', ['limit' => 200])->all();
        $schoolCourses = $this->SchoolCourses->Schedules->SchoolCourses->find('list', ['limit' => 200])->all();
        $schedule = $this->SchoolCourses->Schedules->newEmptyEntity();
        $this->set(compact('schoolCourse', 'schedule', 'days', 'schoolCourses'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $schoolCourse = $this->SchoolCourses->newEmptyEntity();
        $this->Authorization->authorize($schoolCourse);
        if ($this->request->is('post')) {
            $schoolCourse = $this->SchoolCourses->patchEntity($schoolCourse, $this->request->getData());
            if ($this->SchoolCourses->save($schoolCourse)) {
                $this->Flash->success(__('The school course has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The school course could not be saved. Please, try again.'));
        }
        $subjects = $this->SchoolCourses->Subjects->find('list', ['limit' => 200])->all();
        $teachers = $this->SchoolCourses->Teachers->find('list', ['limit' => 200])->all();
        $terms = $this->SchoolCourses->Terms->find('list', ['limit' => 200])->all();
        $schedules = $this->SchoolCourses->Schedules->find('list', ['limit' => 200])->all();
        $students = $this->SchoolCourses->Students->find('list', ['limit' => 200])->all();
        $this->set(compact('schoolCourse', 'subjects', 'teachers', 'terms', 'schedules', 'students'));
    }

    /**
     * Edit method
     *
     * @param string|null $id School Course id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $schoolCourse = $this->SchoolCourses->get($id, [
            'contain' => ['Schedules', 'Students'],
        ]);
        $this->Authorization->authorize($schoolCourse);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $schoolCourse = $this->SchoolCourses->patchEntity($schoolCourse, $this->request->getData());
            if ($this->SchoolCourses->save($schoolCourse)) {
                $this->Flash->success(__('The school course has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The school course could not be saved. Please, try again.'));
        }
        $subjects = $this->SchoolCourses->Subjects->find('list', ['limit' => 200])->all();
        $teachers = $this->SchoolCourses->Teachers->find('list', ['limit' => 200])->all();
        $terms = $this->SchoolCourses->Terms->find('list', ['limit' => 200])->all();
        $schedules = $this->SchoolCourses->Schedules->find('list', ['limit' => 200])->all();
        $students = $this->SchoolCourses->Students->find('list', ['limit' => 200])->all();
        $this->set(compact('schoolCourse', 'subjects', 'teachers', 'terms', 'schedules', 'students'));
    }

    /**
     * Delete method
     *
     * @param string|null $id School Course id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $schoolCourse = $this->SchoolCourses->get($id);
        $this->Authorization->authorize($schoolCourse);
        if ($this->SchoolCourses->delete($schoolCourse)) {
            $this->Flash->success(__('The school course has been deleted.'));
        } else {
            $this->Flash->error(__('The school course could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function courseRegistration() {
        $this->Authorization->skipAuthorization();
        $user_id = $this->Authentication->getIdentity()->getIdentifier();
        if ($this->Authentication->getIdentity()->role->name == 'ALUMNO' ) {
            $query = $this->SchoolCourses->Students->find('StudentInfo', [
                'user_id' => $user_id
            ])->all();
            $row = $query->first();

            $options = [
                'school_level_id' => $row->sl_id,
                'sex' => $row->sex,
                'student_id' => $row->student_id
            ];

            // Traer los cursos relacionados con el grado escolar, sexo y la edad del estudiante
            $schoolCourses = $this->SchoolCourses->find('CoursesForStudent', $options)
                ->contain(['Subjects', 'Teachers', 'Terms', 'Schedules'])
                ->all();
            $studentCourses = $this->SchoolCourses->Students->find('StudentCourses', ['student_id' => $row->student_id])->all()->toList();
        }

        $this->set(compact('schoolCourses', 'studentCourses'));
    }

    public function signup($id = null) {
        $this->request->allowMethod(['post', 'put']);
        $schoolCourse = $this->SchoolCourses->get($id);
        $this->Authorization->authorize($schoolCourse);
        // get student info
        $student = $this->SchoolCourses->Students->find('all')
            ->where(['user_id' => $this->request->getAttribute('identity')->getIdentifier()])
            ->all()
            ->first();
        $schoolCourse->students = [$student];
        if ($this->SchoolCourses->save($schoolCourse)) {
            $this->Flash->success(__('You have signed up for the course ') . $schoolCourse->name . '.');
        } else {
            $this->Flash->error(__('The school course could not be taken. Please, try again.'));
        }
        return $this->redirect(['action' => 'courseRegistration']);
    }

    public function exportRelatedStudents($schoolCourseId){
        $this->Authorization->skipAuthorization();

        $row = 5;
        $headers = [
            "A" => "Colegio",
            "B" => "Clave Identificacion Alumno",
            "C" => "Nombre del Alumno",
            "D" => "Sexo",
            "E" => "Fecha Nacimiento",
            "F" => "Seccion",
            "G" => "Grado",
            "H" => "Grupo"
        ];
        $col_header = [
            "institute" => "A",
            "curp" => "B",
            "name" => "C",
            "sex" => "D",
            "birth_date" => "E",
            "level" => "F",
            "school_level" => "G",
            "school_group" => "H"
        ];

        $schoolCourse = $this->SchoolCourses->get($schoolCourseId, [
            'contain' => ['Students', 'Teachers', 'Terms']
        ]);

        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        // Add value in a sheet inside of that spreadsheet.
        // // (It's possible to have multiple sheets in a single spreadsheet)
        $sheet = $spreadsheet->getActiveSheet();

        //SCHOOL COURSE INFO
        $sheet->setCellValue("A1", "ACADEMIA");
        $sheet->setCellValue("A2", "PROFESOR");
        $sheet->setCellValue("A3", "CURSO");
        $sheet->setCellValue("B1", $schoolCourse['name']);
        $sheet->setCellValue("B2", $schoolCourse->teacher['name']);
        $sheet->setCellValue("B3", $schoolCourse->term['description']);

        //HEADERS
        foreach($headers as $key=>$header):
            $sheet->setCellValue($key.$row, $header);
            $sheet->getColumnDimension($key)->setAutoSize(true);
        endforeach;

        //DATA
        foreach($schoolCourse->students as $student):
            $row++;
            foreach($col_header as $key=>$col):
                $sheet->setCellValue($col.$row, $student[$key]);
            endforeach;
        endforeach;

        $fileName = $schoolCourse['name'].'_'.date("ymdHis").".xlsx";
        $writer = new Xlsx($spreadsheet);

        $stream = new CallbackStream(function () use ($writer) {
            $writer->save('php://output');
        });
        $response = $this->response;
        return $response->withType('xlsx')
            ->withHeader('Content-Disposition', "attachment;filename=\"{$fileName}\"")
            ->withBody($stream);
    }

    //ASISTENCIA DE ALUMNOS POR ACADEMIA
    public function exportListRelatedStudents($schoolCourseId){
        $this->Authorization->skipAuthorization();

        $row = 8;
        $headers = [
            "A" => "No",
            "B" => "NOMBRE",
            "C" => "",
            "D" => "",
            "E" => "",
            "F" => "",
            "G" => "",
            "H" => "",
            "I" => "",
            "J" => "",
            "K" => "",
            "L" => "",
            "M" => "",
            "N" => "",
            "O" => "",
            "P" => "",
            "Q" => "",
            "R" => "",
            "S" => "",
            "T" => ""
        ];
        $col_header = [
            "name" => "B",
            "" => "C",
            "" => "D",
            "" => "E",
            "" => "F",
            "" => "G",
            "" => "H",
            "" => "I",
            "" => "J",
            "" => "K",
            "" => "L",
            "" => "M",
            "" => "N",
            "" => "O",
            "" => "P",
            "" => "Q",
            "" => "R",
            "" => "S",
            "" => "T"
        ];

        $schoolCourse = $this->SchoolCourses->get($schoolCourseId, [
            'contain' => ['Students', 'Teachers', 'Terms']
        ]);

        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        // Add value in a sheet inside of that spreadsheet.
        // // (It's possible to have multiple sheets in a single spreadsheet)
        $sheet = $spreadsheet->getActiveSheet();

        //DEFINIMOS EL FORMATO PARA LOs BORDES
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
            ),
        );
        //AGREGANDO BORDES A TODOS LOS DATOS INCLUYENDO LOS HEADERS
        $sheet->getStyle("A$row:T11")->applyFromArray($styleArray);
        $sheet->getStyle("A$row:T$row")->getFill()
                                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('ffa59f9d');

        //GENERAL INFO
        $sheet->setCellValue("A1", "CUMBRES INTERNATIONAL SCHOOL MERIDA");
        $sheet->setCellValue("A2", "CICLO ESCOLAR ".$schoolCourse->term['description']);
        $sheet->setCellValue("A3", $schoolCourse["name"]);

        $sheet->setCellValue("A5", "PROFESOR(A): ".$schoolCourse->teacher['name']);

        $sheet->setCellValue("B".$row-1, "LISTA DE ALUMNOS");

        //COMBINAMOS LAS COLUMNAS DE LA INFO GENERAL
        $sheet->mergeCells("A1:F1");
        $sheet->mergeCells("A2:F2");
        $sheet->mergeCells("A3:F3");
        $sheet->mergeCells("A4:F4");
        $sheet->mergeCells("A5:F5");

        //COMBINANDO CELDAS DE ENCABEZADOS
        $sheet->mergeCells("C$row:T$row");

        //CENTRAMOS LA INFORMACION GENERAL
        $sheet->getStyle("A1:A5")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        //HEADERS
        foreach($headers as $key=>$header):
            $sheet->setCellValue($key.$row, $header);
            $sheet->getColumnDimension($key)->setAutoSize(true);
        endforeach;

        //DATA
        foreach($schoolCourse->students as $student):
            $row++;
            $sheet->setCellValue("A".$row, $row-8);
            foreach($col_header as $key=>$col):
                if(!empty($key)):
                    $sheet->setCellValue($col.$row, $student[$key]);
                endif;
            endforeach;
        endforeach;

        $fileName = 'LISTA_'.$schoolCourse['name'].'_'.date("ymdHis").".xlsx";
        $writer = new Xlsx($spreadsheet);

        $stream = new CallbackStream(function () use ($writer) {
            $writer->save('php://output');
        });
        $response = $this->response;
        return $response->withType('xlsx')
            ->withHeader('Content-Disposition', "attachment;filename=\"{$fileName}\"")
            ->withBody($stream);
    }

    public function studentRegistration($id = null) {
        $this->Authorization->skipAuthorization();
        $schoolCourse = $this->SchoolCourses->get($id, [
            'contain' => ['Students', 'Subjects' => ['SchoolLevels']],
        ]);
        
        $search_options = [];
        if ($schoolCourse->subject->criterio_academia === 'GRADO ESCOLAR') {
            $schoolLevels = [];
            foreach ($schoolCourse->subject->school_levels as $school_lavel) {
                $schoolLevels[] = $school_lavel->name;
            }
            $search_options += ['school_level in' => $schoolLevels];
        } else {
            $min_birthyear = $schoolCourse->subject->anio_nacimiento_minimo;
            $max_birthyear = $schoolCourse->subject->anio_nacimiento_maximo;

            $search_options[] = "YEAR(Students.birth_date) BETWEEN {$min_birthyear} AND {$max_birthyear}";
        }
        
        $sex = $schoolCourse->subject->sex;
        $sex = $sex === 'X' ? ['F', 'M'] : [$sex];
        $search_options += ['sex in ' => $sex];
        $search_options[] = 'student_id is null';
        $search_options[] = '(school_course_id = 1 OR school_course_id is null)';

        $students = $this->SchoolCourses->Students
            ->find('all', ['limit' => 200])
            ->leftJoin(['SchoolCoursesStudents' => 'school_courses_students'],
                [
                    'SchoolCoursesStudents.student_id = Students.id'
                ])
            ->where($search_options)
            ->all();

        $this->set(compact('schoolCourse', 'students'));
    }

    /**
     * Delete method
     *
     * @param string|null $id School Course id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function enroll($id = null, array $students_id = null) {
        $schoolCourse = $this->SchoolCourses->get($id, [
            'contain' => ['Students']
        ]);
        $this->Authorization->authorize($schoolCourse);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $students = $this->SchoolCourses->Students
                ->find('all')
                ->where(['id in' => $students_id])
                ->all()
                ->toArray();
            $schoolCourse->students = array_merge($schoolCourse->students, $students);
            // pr($schoolCourse);die();

            if ($this->SchoolCourses->save($schoolCourse)) {
                $this->Flash->success(__('The student has been enrolled.'));
                return $this->redirect(['action' => 'studentRegistration', $id]);
            }
            $this->Flash->error(__('The student could not be enrolled. Please, try again.'));

        }
        return $this->redirect(['action' => 'studentRegistration', $id]);
    }
}
