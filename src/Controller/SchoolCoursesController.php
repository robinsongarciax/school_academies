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
    public function index($type = null)
    {
        $this->Authorization->skipAuthorization();
        if ($this->Authentication->getIdentity()->role->name == 'ALUMNO' ) {
            // Traer los datos del estudiante
            $query = $this->SchoolCourses->Students->find('StudentInfo', [
                'user_id' => $this->Authentication->getIdentity()->getIdentifier()
            ])->all();
            if ($query->count() == 0) {
                $this->Flash->error(__('No existe registro del grado escolar del alumno. Ponerse en contacto con el administrador del sistema'));
                return $this->redirect(['controller' => 'pages', 'action' => 'home']);
            }

            $row = $query->first();

            $options = [
                'school_level_id' => $row->sl_id,
                'sex' => $row->sex
            ];

            // Traer los cursos relacionados con el grado escolar, sexo y la edad del estudiante
            $schoolCourses = $this->SchoolCourses->find('CoursesForStudent', $options)
                ->contain(['Teachers', 'Terms']);
        } else {
            $schoolCourses = $this->SchoolCourses->find('all');
            $schoolCourses->contain(['Teachers', 'Terms']);
            if ($type != null) 
                $schoolCourses->where(['tipo_academia' => $type]);
        }

        $this->set(compact('schoolCourses', 'type'));
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
            'contain' => ['SchoolLevels', 'Teachers', 'Terms', 'Schedules.Days'
            ]
        ]);
        $this->Authorization->authorize($schoolCourse);

        $query_schoolCoursesStudents = $this->SchoolCourses->find('StudentsConfirmed', [
            'id' => $id
        ]);

        $totalStudentsConfirmed = $query_schoolCoursesStudents->count();

        $days = $this->SchoolCourses->Schedules->Days->find('list', ['limit' => 200])->all();
        $schoolCourses = $this->SchoolCourses->Schedules->SchoolCourses->find('list', ['limit' => 200])->all();
        $schedule = $this->SchoolCourses->Schedules->newEmptyEntity();
        $this->set(compact('schoolCourse', 'schedule', 'days', 'schoolCourses', 'totalStudentsConfirmed'));
    }

    /**
     * confirmedStudents method
     *
     * @param string|null $id School Course id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function confirmedStudents($id = null)
    {
        $schoolCourse = $this->SchoolCourses->get($id, [
            'contain' => ['Terms', 'Schedules.Days', 'Students' => [
                    'conditions' => ['SchoolCoursesStudents.is_confirmed' => '1']
                ]
            ]
        ]);
        $this->Authorization->authorize($schoolCourse);
        $this->set(compact('schoolCourse'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add($type = null)
    {
        $schoolCourse = $this->SchoolCourses->newEmptyEntity();
        $this->Authorization->authorize($schoolCourse);
        if ($this->request->is('post')) {
            $schoolCourse = $this->SchoolCourses->patchEntity($schoolCourse, $this->request->getData());
            if ($this->SchoolCourses->save($schoolCourse)) {
                $this->Flash->success(__('The school course has been saved.'));

                return $this->redirect([
                    'action' => 'view', 
                    $schoolCourse->id
                ]);
            }
            $this->Flash->error(__('The school course could not be saved. Please, try again.'));
        }
        $teachers = $this->SchoolCourses->Teachers->find('list', ['limit' => 200])->all();
        $terms = $this->SchoolCourses->Terms->find('list', ['limit' => 200])->all();
        $schedules = $this->SchoolCourses->Schedules->find('list', ['limit' => 200])->all();
        $students = $this->SchoolCourses->Students->find('list', ['limit' => 200])->all();
        $schoolLevels = $this->SchoolCourses->SchoolLevels->find('list', ['limit' => 200])->all();
        $this->set(compact('schoolCourse', 'teachers', 'terms', 'schedules', 'students', 'schoolLevels', 'type'));
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
            'contain' => ['Schedules', 'Students', 'SchoolLevels'],
        ]);
        $this->Authorization->authorize($schoolCourse);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $schoolCourse = $this->SchoolCourses->patchEntity($schoolCourse, $this->request->getData());
            if ($this->SchoolCourses->save($schoolCourse)) {
                $this->Flash->success(__('The school course has been updated.'));

                return $this->redirect([
                    'action' => 'view', 
                    $schoolCourse->id
                ]);
            }
            $this->Flash->error(__('The school course could not be saved. Please, try again.'));
        }
        $teachers = $this->SchoolCourses->Teachers->find('list', ['limit' => 200])->all();
        $terms = $this->SchoolCourses->Terms->find('list', ['limit' => 200])->all();
        $schedules = $this->SchoolCourses->Schedules->find('list', ['limit' => 200])->all();
        $students = $this->SchoolCourses->Students->find('list', ['limit' => 200])->all();
        $schoolLevels = $this->SchoolCourses->SchoolLevels->find('list', ['limit' => 200])->all();
        $this->set(compact('schoolCourse', 'teachers', 'terms', 'schedules', 'students', 'schoolLevels'));
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

    public function courseRegistration($enrolled = null) {
        $this->Authorization->skipAuthorization();
        $user_id = $this->Authentication->getIdentity()->getIdentifier();
        if ($this->Authentication->getIdentity()->role->name == 'ALUMNO' ) {
            $query = $this->SchoolCourses->Students->find('StudentInfo', [
                'user_id' => $user_id
            ])->all();
            // Si no se encuentra información del nivel es porque no existe en la base de datos tabla school_levels
            if ($query->count() == 0) {
                $this->Flash->error(__('No existe registro del grado escolar del alumno. Póngase en contacto con el administrador para obtener más información.'));
                return $this->redirect(['controller' => 'pages', 'action' => 'home']);
            }
            $row = $query->first();

            $options = [
                'enrolled' => $enrolled,
                'school_level_id' => $row->sl_id,
                'sex' => $row->sex,
                'student_id' => $row->student_id,
                'year_of_birth' => "{$row->year_of_birth} BETWEEN min_year_of_birth AND max_year_of_birth"
            ];

            // Traer los cursos relacionados con el grado escolar, sexo y la edad del estudiante
            $schoolCourses = $this->SchoolCourses->find('CoursesForStudent', $options)
                ->contain(['Teachers', 'Terms', 'Schedules'])
                ->all();
            $studentCourses = $this->SchoolCourses->Students->find('StudentCourses', ['student_id' => $row->student_id])->all()->toList();
            $term = $this->SchoolCourses->Terms->find('all', [
                'conditions' => ['active' => 1]
            ])->all()->first();

        }

        $this->set(compact('schoolCourses', 'studentCourses', 'term', 'enrolled'));
    }

    public function enrollStudent($student_id = null) {
        $this->Authorization->skipAuthorization();
        $query = $this->SchoolCourses->Students->find('StudentInfo', ['student_id' => $student_id])->all();
        // Si no se encuentra información del nivel es porque no existe en la base de datos tabla school_levels
        $schoolCourses = [];
        if ($query->count() > 0) {

            $row = $query->first();

            $options = [
                'enrolled' => null,
                'school_level_id' => $row->sl_id,
                'sex' => $row->sex,
                'student_id' => $student_id,
                'year_of_birth' => "{$row->year_of_birth} BETWEEN min_year_of_birth AND max_year_of_birth"
            ];

            // Traer los cursos relacionados con el grado escolar, sexo y la edad del estudiante
            $schoolCourses = $this->SchoolCourses->find('CoursesAvailableForStudent', $options)
                ->contain(['Teachers', 'Terms', 'Schedules'])->all();
            // pr($schoolCourses);die();
        }
        $term = $this->SchoolCourses->Terms->find('all', [
            'conditions' => ['active' => 1]
        ])->all()->first();

        $this->set(compact('schoolCourses', 'term', 'student_id'));
    }

    public function signup($id = null) {
        $this->request->allowMethod(['post', 'put']);
        $schoolCourse = $this->SchoolCourses->get($id, [
            'contain' => ['Students']
        ]);
        $this->Authorization->authorize($schoolCourse);
        $availability = $schoolCourse->capacity - $schoolCourse->occupancy;
        if ($availability <= 0) {
            $this->Flash->info(__('There is no availability for this course.'));
        } else {
            // get student info
            $student = $this->SchoolCourses->Students->find('all')
                ->where(['user_id' => $this->request->getAttribute('identity')->getIdentifier()])
                ->all();
            $schoolCourse->students = array_merge($schoolCourse->students, $student->toArray());

            if ($this->SchoolCourses->save($schoolCourse)) {
                // Actualizar el campo is_confirmed
                $schoolCoursesStudents = $this->loadModel('SchoolCoursesStudents');
                $schoolCourseStudent = $schoolCoursesStudents->findBySchoolCourseIdAndStudentId($schoolCourse->id, $student->first()->id)->first();
                $schoolCourseStudent->is_confirmed = 1;
                $schoolCoursesStudents->save($schoolCourseStudent);
                $this->Flash->success(__('You have signed up for the course ') . $schoolCourse->name . '.');
            } else {
                $this->Flash->error(__('The school course could not be taken. Please, try again.'));
            }
        }
        return $this->redirect(['action' => 'courseRegistration']);
    }

    public function enrollment($id = null, $student_id = null) {
        $this->request->allowMethod(['post', 'put']);
        $schoolCourse = $this->SchoolCourses->get($id, [
            'contain' => ['Students']
        ]);
        $this->Authorization->authorize($schoolCourse);
        $availability = $schoolCourse->capacity - $schoolCourse->occupancy;
        if ($availability <= 0) {
            $this->Flash->info(__('There is no availability for this course.'));
        } else {
            // get student info
            $student = $this->SchoolCourses->Students->find('all')
                ->where(['id' => $student_id])
                ->all();
            $schoolCourse->students = array_merge($schoolCourse->students, $student->toArray());

            if ($this->SchoolCourses->save($schoolCourse)) {
                // Actualizar el campo is_confirmed
                $schoolCoursesStudents = $this->loadModel('SchoolCoursesStudents');
                $schoolCourseStudent = $schoolCoursesStudents->findBySchoolCourseIdAndStudentId($schoolCourse->id, $student->first()->id)->first();
                $schoolCourseStudent->is_confirmed = 1;
                $schoolCoursesStudents->save($schoolCourseStudent);
                $this->Flash->success(__('You have signed up for the course ') . $schoolCourse->name . '.');
            } else {
                $this->Flash->error(__('The school course could not be taken. Please, try again.'));
            }
        }
        return $this->redirect(['controller' => 'Students', 'action' => 'view', $student_id]);
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

    /**
     * StudentRegistration method
     *
     * @param string|null $id School Course id.
     * @return \Cake\Http\Response|null|void Render view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function studentRegistration($id = null) {
        $this->Authorization->skipAuthorization();
        $schoolCourse = $this->SchoolCourses->get($id, [
            'contain' => [
                'Students' => ['conditions' => ['is_confirmed' => 0]],
                'SchoolLevels'
            ],
        ]);

        $search_options = [];
        if ($schoolCourse->criterio_academia === 'GRADO ESCOLAR') {
            $schoolLevels = [];
            foreach ($schoolCourse->school_levels as $school_lavel) {
                $schoolLevels[] = $school_lavel->name;
            }
            $search_options += ['school_level in' => $schoolLevels];
        } else {
            $min_birthyear = $schoolCourse->min_year_of_birth;
            $max_birthyear = $schoolCourse->max_year_of_birth;

            $search_options[] = "YEAR(Students.birth_date) BETWEEN {$min_birthyear} AND {$max_birthyear}";
        }

        $sex = $schoolCourse->sex;
        $sex = $sex === 'X' ? ['F', 'M'] : [$sex];
        $search_options += ['sex in ' => $sex];

        $students_enrroled = $this->fetchTable('SchoolCoursesStudents')->find()
            ->select(['student_id'])
            ->where(['school_course_id' => $id]);

        $search_options += ['id not in' => $students_enrroled];

        $students = $this->SchoolCourses->Students
            ->find('all', ['limit' => 200])
            ->where($search_options)
            ->all();


        $totalStudentsConfirmed = $this->SchoolCourses->find('all')
            ->contain(['Students' => ['conditions' => ['is_confirmed' => 1]]])
            ->where(['SchoolCourses.id' => $id])
            ->all()
            ->first();
        $totalStudentsConfirmed = count($totalStudentsConfirmed->students);

        $this->set(compact('schoolCourse', 'students', 'totalStudentsConfirmed'));
    }

    /**
     * Delete method
     *
     * @param string|null $id School Course id.
     * @return \Cake\Http\Response|null|void Redirects to studentRegistration.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function preEnroll($id = null, array $students_id = null) {
        $schoolCourse = $this->SchoolCourses->get($id, [
            'contain' => ['Students']
        ]);
        $this->Authorization->authorize($schoolCourse);
        if ($this->request->is(['patch', 'post', 'put'])) {

            $ids = $this->request->getData('ids');
            if ($ids != null) {
                $ids = array_filter($ids, function($v) {
                    return $v;
                });
            }

            $students_id = $students_id == null ? $ids : $students_id;
            // si no se selecciona ningun alumno, regresar a la vista
            if (empty($students_id)) {
                return $this->redirect(['action' => 'studentRegistration', $id]);
            }

            $students = $this->SchoolCourses->Students
                ->find('all')
                ->where(['id in' => $students_id])
                ->all()
                ->toArray();
            $schoolCourse->students = array_merge($schoolCourse->students, $students);

            if ($this->SchoolCourses->save($schoolCourse)) {
                $this->Flash->success(__('The student has been enrolled.'));
                return $this->redirect(['action' => 'studentRegistration', $id]);
            }
            $this->Flash->error(__('The student could not be enrolled. Please, try again.'));

        }
        return $this->redirect(['action' => 'studentRegistration', $id]);
    }

    public function constanciasEstudios($schoolCourseId){
        error_reporting(0);
        setlocale(LC_TIME, "spanish");//PARA TENER LA FECHA EN ESPANOL
        $this->Authorization->skipAuthorization();

        $schoolCourse = $this->SchoolCourses->get($schoolCourseId, [
            'contain' => ['Students.Terms.Institutes']
        ]);

        $zip = new \ZipArchive();
        $zip_file_tmp = tempnam("/tmp", 'tmp');
        $zip->open($zip_file_tmp, \ZipArchive::OVERWRITE);

        foreach($schoolCourse->students as $student):
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $current_row = 1;
            //CENTRA LAS CELDAS COMBINADAS DEL LOGO
            $sheet->mergeCells("A$current_row:N$current_row");
            $sheet->getStyle("A$current_row:N$current_row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            //DIBUJA EL LOGO
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath('img/logo_cumbres.png'); /* put your path and image here */
            $drawing->setCoordinates("A$current_row");
            $drawing->setOffsetX(110);
            $drawing->setHeight(120);
            $drawing->setRotation(25);
            $drawing->getShadow()->setVisible(true);
            $drawing->getShadow()->setDirection(45);
            $drawing->setWorksheet($sheet);
            //FECHA
            $current_row += 3;
            $rangeCell = "F$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "Mérida, Yucatán a ".strftime("%A, %d de %B de %Y"));
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $current_row += 4;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "A QUIEN CORRESPONDA:");
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            $current_row += 4;
            $styleArray = ['font'  => [
                'bold'  => false,
                'size'  => 13,
                'name'  => 'Arial'
            ]];
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "           Por medio del presente, se le HACE CONSTAR que el siguiente niño es");
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight(30, 'pt');
            $current_row += 1;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "alumno regular es alumno regular de este instituto con clave 31PPR0004R, en");
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight(30, 'pt');
            $current_row += 1;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "el presente curso escolar ".$student->term->description);
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight(30, 'pt');

            //NOMBRE
            $current_row += 10;
            $rowHeight = 23;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), $student['name']);
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight($rowHeight, 'pt');

            //CURP
            $current_row += 1;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), $student['curp']);
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight($rowHeight, 'pt');

            //FECHA NACIMIENTO
            $current_row += 1;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), $student['birth_date']);
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight($rowHeight, 'pt');

            //SEXO
            $sex = ["M"=>'Masculino', 'F'=>'FEMENINO'];
            $current_row += 1;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), $sex[$student['sex']]);
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight($rowHeight, 'pt');

            //GRADO Y GRUPO
            $current_row += 1;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), $student['school_level']);
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight($rowHeight, 'pt');

            $current_row += 2;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "Se expide la presente para los fines que corresponda.");
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $current_row += 3;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "ATENTAMENTE");
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight(35, 'pt');

            $current_row += 4;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), $student->term->institute->principal);
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $current_row += 1;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "Director");
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


            $current_row += 3;
            $styleArray = ['font'  => [
                'bold'  => false,
                'size'  => 8,
                'name'  => 'Arial'
            ]];
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "CUMBRES INTERNATIOAL SCHOOL MÉRIDA");
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight(35, 'pt');

            //PREPARA LA DESCARGA DEL ARCHIVO
            $fileName = 'CONSTANCIA_ESTUDIOS_'.$student['name'].'_'.date("ymdHis").".pdf";
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Mpdf');

            $excel_file_tmp = tempnam("/tmp", 'tmp');
            $writer->save($excel_file_tmp);

            $zip->addFile($excel_file_tmp, $fileName);

            $stream = new CallbackStream(function () use ($writer, $schoolCourse, $fileName) {
                $writer->save("constancias/".$schoolCourse['name'].'/'.$fileName);
            });
        endforeach;

        $zip->close();
        header("Content-Type: application/zip");
        header("Content-Length: " . filesize($zip_file_tmp));
        header("Content-Disposition: attachment; filename=\"CONSTANCIAS_".$schoolCourse['name'].".zip\"");
        readfile($zip_file_tmp);
        unlink($excel_file_tmp);
        unlink($zip_file_tmp);
        $this->redirect($this->referer());
    }
}
