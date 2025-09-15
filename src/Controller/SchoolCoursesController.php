<?php
declare(strict_types=1);

namespace App\Controller;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Cake\Http\CallbackStream;
use Cake\I18n\FrozenTime;

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
                ->contain(['Teachers', 'Terms', 'Schedules.Days']);
        } else {
            $schoolCourses = $this->SchoolCourses->find('all');
            $schoolCourses->contain(['Teachers', 'TeachingAssistants', 'Terms', 'Schedules.Days']);
            if ($type != null) 
                $schoolCourses->where(['SchoolCourses.tipo_academia' => $type, 'Terms.active' => 1]);
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
            'contain' => ['SchoolLevels', 'Teachers', 'TeachingAssistants', 'Terms', 'Schedules.Days'
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
            'contain' => ['Terms', 'Schedules.Days', 'Teachers', 'Students' => [
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

        $options = ['active' => 1];
        if (isset($type) && !empty($type))
            $options['tipo_academia'] = $type;

        $teachers = $this->SchoolCourses->Teachers->find('list', ['limit' => 200])
                                                  ->where($options)
                                                  ->all();
        
        $terms = $this->SchoolCourses->Terms->find('list', ['conditions' => ['active' => 1]])->all();
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

                /*return $this->redirect([
                    'action' => 'view', 
                    $schoolCourse->id
                ]);*/
            }
            //$this->Flash->error(__('The school course could not be saved. Please, try again.'));
        }
        $teachers = $this->SchoolCourses->Teachers->find('list', ['limit' => 200])->all();
        $terms = $this->SchoolCourses->Terms->find('list', ['conditions' => ['active' => 1]])->all();
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
            $query = $this->SchoolCourses->Students->find('StudentInfo', ['user_id' => $user_id])
                                                   ->contain(['Terms'])
                                                   ->where(['active' => 1])
                                                   ->all();
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
                                                 ->contain(['Teachers', 'TeachingAssistants', 'Terms', 'Schedules'])
                                                 ->where(['Terms.active' => 1])
                                                 ->all();
            $studentCourses = $this->SchoolCourses->Students->find('StudentCourses', ['student_id' => $row->student_id])->all();
            $term = $this->SchoolCourses->Terms->find('all', ['conditions' => ['active' => 1]])
                                               ->all()
                                               ->first();
            $session = $this->request->getSession();
            if ($studentCourses->count() >= $term->courses_allowed && !$session->read('firstAccess')) {
                $session->write('firstAccess', true);
                $this->Flash->warning('Ha seleccionado el número máximo de cursos permitidos. Si desea agregar una academía adicional enviar un correo a kmurillo@cumbresmerida.com ó tjgonzalez@cumbresmerida.com');
            }
            $studentCourses = $studentCourses->toArray();

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
                                                 ->contain(['Terms', 'Teachers', 'Schedules'])
                                                 ->where(['Terms.active' => 1])
                                                 ->all();
            // pr($schoolCourses);die();
        }
        
        $term = $this->SchoolCourses->Terms->find('all', [
            'conditions' => ['active' => 1]
        ])->all()->first();

        $this->set(compact('schoolCourses', 'term', 'student_id'));
    }

    public function enrollStudentAnyAcademy ($student_id = null) {
        $this->Authorization->skipAuthorization();
        $schoolCourses = $this->SchoolCourses->find('all')
                                             ->contain(['Terms', 'Teachers', 'Schedules'])
                                             ->where(['Terms.active' => 1])
                                             ->all();

        $term = $this->SchoolCourses->Terms->find('all', [
            'conditions' => ['active' => 1]
        ])->all()->first();

        $this->set(compact('schoolCourses', 'term', 'student_id'));

        return $this->render('enroll_student');

    }

    public function signup($id = null) {
        $this->request->allowMethod(['post', 'put']);
        $schoolCourse = $this->SchoolCourses->get($id, [
            'contain' => ['Students']
        ]);
        $this->Authorization->authorize($schoolCourse);

        // Verificar si el horario no choca con otra academía
        $student = $this->SchoolCourses->Students->find('all')
                ->where(['user_id' => $this->request->getAttribute('identity')->getIdentifier()])
                ->first();
        $studentId = $student->id;
        
        if ($this->SchoolCourses->hasScheduleConflict($id, $studentId))
        {
            $this->Flash->error(__('Ya estás inscrito en otra academia con el mismo horario.'));
            return $this->redirect($this->referer());
        }

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

    public function exportRelatedStudents($schoolCourseId, $contentType = 'xslx'){
        $this->Authorization->skipAuthorization();

        $row = 6;
        $headers = [
            "A" => "Colegio",
            "B" => "Nombre del Alumno",
            "C" => "CURP",
            "D" => "Grado",
            "E" => "Grupo",
            "F" => '2da. academía',
            "G" => '3ra. academía'
        ];
        $col_header = [
            "institute" => "A",
            "name" => "B",
            "curp" => "C",
            "school_level" => "D",
            "school_group" => "E"
        ];

        $schoolCourse = $this->SchoolCourses->get($schoolCourseId, [
            'contain' => ['Students', 'Teachers', 'Terms', 'Schedules.Days']
        ]);

        $horario = $this->_getSchedule($schoolCourse->schedules);


        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();

        // font size
        $spreadsheet->getDefaultStyle()->getFont()->setSize(10);
        $sheet = $spreadsheet->getActiveSheet();

        //SCHOOL COURSE INFO
        $sheet->setCellValue("A1", "ACADEMIA");
        $sheet->setCellValue("A2", "PROFESOR");
        $sheet->setCellValue("A3", "CURSO");
        $sheet->setCellValue("A4", "HORARIO");
        $sheet->setCellValue("B1", $schoolCourse['name']);
        $sheet->setCellValue("B2", $schoolCourse->teacher['name']);
        $sheet->setCellValue("B3", $schoolCourse->term['description']);
        $sheet->setCellValue("B4", $horario);

        // bold for titles
        $sheet->getStyle("A1:A4")->getFont()->setBold(true);

        // HEADERS
        foreach($headers as $key => $header) {
            $sheet->setCellValue($key.$row, $header);
            $sheet->getColumnDimension($key)->setAutoSize(true);
        }

        $sheet->getStyle("A6:G6")->getFill()
                                 ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                 ->getStartColor()->setARGB('ffa59f9d');

        //DATA
        foreach($schoolCourse->students as $student) {
            $row++;
            $aditionalSC = $this->SchoolCourses->find()
                                               ->select('SchoolCourses.name')
                                               ->matching('Students', function ($q) use ($student) {
                                                    return $q->where(['Students.id' => $student->id]);
                                               })
                                               ->where(['SchoolCourses.id <>' => $schoolCourseId])
                                               ->all();
            foreach ($col_header as $key => $col) {
                $sheet->setCellValue($col . $row, $student[$key]);
            }
            // aditional school courses
            $col = 'F';
            foreach ($aditionalSC as $rowSC) {
                $sheet->setCellValue($col++ . $row, $rowSC->name);
            }
        }

        //border style
        $boderStyle = ['borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]];

        // adding borders
        $sheet->getStyle("A6:G$row")->applyFromArray($boderStyle);
        

        $fileName = $this->_encode($schoolCourse['name'].'_'.date("ymdHis"));

        switch ($contentType) {
            case 'pdf':
                $writer = IOFactory::createWriter($spreadsheet, 'Mpdf');
                $writer->writeAllSheets();     
                $stream = new CallbackStream(function () use ($writer) {
                    $writer->save('php://output');
                });

                // download file
                $response = $this->response;
                return $response
                    ->withHeader('Content-Disposition', "attachment;filename=\"{$fileName}.pdf\"")
                    ->withBody($stream);
                break;
            case 'xslx':
            default:
                $writer = new Xlsx($spreadsheet);

                $stream = new CallbackStream(function () use ($writer) {
                    $writer->save('php://output');
                });
                $response = $this->response;
                return $response->withType('xlsx')
                    ->withHeader('Content-Disposition', "attachment;filename=\"{$fileName}.xlsx\"")
                    ->withBody($stream);
        }
        
    }

    //ASISTENCIA DE ALUMNOS POR ACADEMIA
    public function exportListRelatedStudents($schoolCourseId){
        $this->Authorization->skipAuthorization();

        $row = 12;
        $headers = [
            "A" => "No",
            "B" => "NOMBRE",
            "C" => "Gdo/Gpo",
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
            "T" => "",
            "U" => ""
        ];
        $col_header = [
            "name" => "B",
            "school_group" => "C",
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
            "" => "T",
            "" => "U"
        ];

        $schoolCourse = $this->SchoolCourses->get($schoolCourseId, [
            'contain' => ['Students' => ['sort' => ['Students.name' => 'asc']], 
                          'Teachers', 'Terms', 'Schedules.Days']
        ]);

        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        // Add value in a sheet inside of that spreadsheet.
        // // (It's possible to have multiple sheets in a single spreadsheet)
        $sheet = $spreadsheet->getActiveSheet();
        $schoolCourseName = $schoolCourse->name;
        if (strlen($schoolCourseName) > 30) {
          $schoolCourseName = substr($schoolCourseName, 0, 30);
        }
        $sheet->setTitle($schoolCourseName);

        //DEFINIMOS EL FORMATO PARA LOs BORDES
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
            ),
        );
        //AGREGANDO BORDES A TODOS LOS DATOS INCLUYENDO LOS HEADERS
        $sheet->getStyle("A$row:U" . (count($schoolCourse->students) + 15))->applyFromArray($styleArray);
        $sheet->getStyle("A$row:T$row")->getFill()
                                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('ffa59f9d');

        // bold for titles
        $sheet->getStyle("B1:B7")->getFont()->setBold(true);

        // set column size
        $sheet->getColumnDimension("A")->setWidth(28, 'px');
        $sheet->getColumnDimension("B")->setWidth(274, 'px');
        $sheet->getColumnDimension("C")->setWidth(70, 'px');
        $sheet->getColumnDimension("D")->setWidth(16, 'px');
        $sheet->getColumnDimension("E")->setWidth(16, 'px');
        $sheet->getColumnDimension("F")->setWidth(16, 'px');
        $sheet->getColumnDimension("G")->setWidth(16, 'px');
        $sheet->getColumnDimension("H")->setWidth(16, 'px');
        $sheet->getColumnDimension("I")->setWidth(16, 'px');
        $sheet->getColumnDimension("J")->setWidth(16, 'px');
        $sheet->getColumnDimension("K")->setWidth(16, 'px');
        $sheet->getColumnDimension("L")->setWidth(16, 'px');
        $sheet->getColumnDimension("M")->setWidth(16, 'px');
        $sheet->getColumnDimension("N")->setWidth(16, 'px');
        $sheet->getColumnDimension("O")->setWidth(16, 'px');
        $sheet->getColumnDimension("P")->setWidth(16, 'px');
        $sheet->getColumnDimension("Q")->setWidth(16, 'px');
        $sheet->getColumnDimension("R")->setWidth(16, 'px');
        $sheet->getColumnDimension("S")->setWidth(16, 'px');
        $sheet->getColumnDimension("T")->setWidth(16, 'px');
        $sheet->getColumnDimension("U")->setWidth(16, 'px');
        

        //GENERAL INFO
        $sheet->setCellValue("B1", "CUMBRES INTERNATIONAL SCHOOL MERIDA");
        $sheet->setCellValue("B2", "CICLO ESCOLAR " . $schoolCourse->term['description']);

        $tipo_academia = $schoolCourse->tipo_academia;
        $tipo_academia = 'ACADEMIAS ' . ($tipo_academia == 'CULTURAL' ? 'CULTURALES' : 'DEPORTIVAS'); 
        $sheet->setCellValue("B3", $tipo_academia);

        $sheet->setCellValue("B5", "DISCIPLINA:");
        $sheet->setCellValue("B6", "DOCENTE:");
        $sheet->setCellValue("B7", "HORARIO:");

        $sheet->setCellValue("C5", $schoolCourse->name);
        $sheet->setCellValue("C6", $schoolCourse->teacher->name);
        $sheet->setCellValue("C7", $this->_getSchedule($schoolCourse->schedules));

        $sheet->setCellValue("B".$row-1, "LISTA DE ALUMNOS");


        //COMBINANDO CELDAS DE ENCABEZADOS
        $sheet->mergeCells("D$row:U$row");

        //HEADERS
        foreach($headers as $key => $header) {
            $sheet->setCellValue($key.$row, $header);
        }

        //DATA
        $i = 1;
        foreach($schoolCourse->students as $student) {
            $row++;
            $sheet->setCellValue("A".$row, $i++);
            foreach($col_header as $key=>$col) {
                if(!empty($key))
                    $sheet->setCellValue($col.$row, $student[$key]);
            }
        }

        // Adding new sheet with parents information
        $spreadsheet->createSheet();
        $sheet2 = $spreadsheet->getSheet(1);
        $sheet2->setTitle ('Datos de contacto');

        $sheet2->getStyle("A1:G1")->getFont()->setBold(true);
        $row = 1;
        $header = ['A' => 'Nombre del alumno', 
                   'B' => 'Nombre de la madre',
                   'C' => 'Teléfono',
                   'D' => 'Email',
                   'E' => 'Nombre del padre',
                   'F' => 'Telefono',
                   'G' => 'Email'];

        // header for list
        foreach ($header as $key => $value) {
            $sheet2->getColumnDimension($key)->setAutoSize(true);
            $sheet2->setCellValue($key . $row, $value);
        }
        $row++;
        
        foreach ($schoolCourse->students as $student) {
            $sheet2->setCellValue('A' . $row , $student->name);
            $sheet2->setCellValue('B' . $row , $student->mother_name);
            $sheet2->setCellValue('C' . $row , $student->mother_phone);
            $sheet2->setCellValue('D' . $row , $student->mother_email);
            $sheet2->setCellValue('E' . $row , $student->father_name);
            $sheet2->setCellValue('F' . $row , $student->father_phone);
            $sheet2->setCellValue('G' . $row , $student->father_email);
            $row++;
        }

        $spreadsheet->setActiveSheetIndex(0);
        $fileName = $this->_encode('LISTA_'.$schoolCourse['name'].'_'.date("ymdHis").".xlsx");
        $writer = new Xlsx($spreadsheet);

        $stream = new CallbackStream(function () use ($writer) {
            $writer->save('php://output');
        });
        $response = $this->response;
        return $response->withType('xlsx')
                        ->withCharset('UTF-8')
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
                'Students' => ['conditions' => ['is_confirmed' => 0, 'active' => 1]],
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

        $students_enrroled = $this->fetchTable('SchoolCoursesStudents')
                                  ->find()
                                  ->select(['student_id'])
                                  ->where(['school_course_id' => $id,
                                           'active' => 1]);

        $search_options += ['Students.id not in' => $students_enrroled];
        // Get only students for current period
        $search_options += ['Terms.active' => 1];

        $students = $this->SchoolCourses->Students->find('all')
                                                  ->contain('Terms')
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
    public function preEnroll($id = null, ?array $students_id = null) {
        $schoolCourse = $this->SchoolCourses->get($id, [
            'contain' => ['Students' => ['conditions' => ['SchoolCoursesStudents.active' => 1]]]
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

            $students = $this->SchoolCourses->Students->find('all')
                                                      ->where(['Students.id in' => $students_id])
                                                      ->all()
                                                      ->toArray();
            // sql($students);die();

            $schoolCourse->students = array_merge($schoolCourse->students, $students);
            // pr($schoolCourse->students);die();
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
            $drawing->setHeight(211);
            $drawing->setRotation(25);
            $drawing->getShadow()->setVisible(true);
            $drawing->getShadow()->setDirection(45);
            $drawing->setWorksheet($sheet);
            //FECHA
            $current_row += 3;
            $rangeCell = "F$current_row:N$current_row";
            $now = FrozenTime::now();
            $fecha = str_replace(',', '', $now->i18nFormat([\IntlDateFormatter::FULL, \IntlDateFormatter::NONE]));
            $lugar_fecha = 'Mérida, Yucatán a ' . $fecha;
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), $lugar_fecha);
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $current_row += 4;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "A quien corresponda:");
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            $current_row += 4;
            $styleArray = ['font'  => [
                    'bold'  => false,
                    'size'  => 13,
                    'name'  => 'Arial',
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_JUSTIFY,
                ],
            ];

            $student_sex = $student['sex'];
            $body_text1 = 'Por medio de la presente, se HACE CONSTAR que %s es %s regular de';
            $body_text1_complement = $student_sex == 'M' ? 'el siguiente niño' : 'la siguiente niña';
            $body_text1_complement2 = $student_sex == 'M' ? 'alumno' : 'alumna';
            $body_text1 = sprintf($body_text1, $body_text1_complement, $body_text1_complement2);

            $body_text2 = 'este instituto con clave 31PPR0004R, en el presente curso escolar %s.';
            $body_text2 = sprintf($body_text2, $student->term->description);

            $body_text1 .= ' ' . $body_text2;

            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), $body_text1);
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight(45, 'pt');

            //NOMBRE
            $current_row += 7;
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
            $birth_date = new FrozenTime($student['birth_date']);
            $birth_date = $birth_date->i18nFormat('yyyy-MM-dd');
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), $birth_date);
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
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            $current_row += 3;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "Atentamente");
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight(35, 'pt');

            $current_row += 3;
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
                'bold'  => true,
                'size'  => 10,
                'name'  => 'Arial'
            ]];
            $current_row += 1;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "CUMBRES INTERNATIONAL SCHOOL MÉRIDA");
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight(35, 'pt');

            $styleArray = ['font'  => [
                'bold'  => false,
                'size'  => 7,
                'name'  => 'Arial'
            ]];
            $current_row += 1;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "Calle 5 x 18 s/n Glorieta Cumbres, Fraccionamiento Montecristo, 97133. Mérida, Yucatán.\t\tT.(999) 801 86 60");
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $styleArray = ['font'  => [
                'bold'  => true,
                'size'  => 7,
                'name'  => 'Arial'
            ]];
            $current_row += 1;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "cumbresmerida.com");
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

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

    /**
     * DownloadAllProofOfStudy method
     *
     * @param string $id School Course id.
     * @return \Cake\Http\Response|null|void Render view
     * 
     */
    public function downloadAllProofOfStudy ($schoolCourseId) {
        // supress warning with Mpdf
        error_reporting(0);
        $this->Authorization->skipAuthorization();

        $schoolCourse = $this->SchoolCourses->get($schoolCourseId, [
            'contain' => ['Students.Terms.Institutes']
        ]);

        $spreadsheet = new Spreadsheet();
        $i = 0;
        foreach ($schoolCourse->students as $student) {
            if ($i > 0) {
                $spreadsheet->createSheet();
                $spreadsheet->setActiveSheetIndex($i);
            }
            $i++;
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
            $drawing->setHeight(211);
            $drawing->setRotation(25);
            $drawing->getShadow()->setVisible(true);
            $drawing->getShadow()->setDirection(45);
            $drawing->setWorksheet($sheet);
            //FECHA
            $current_row += 3;
            $rangeCell = "F$current_row:N$current_row";
            $now = FrozenTime::now();
            $fecha = str_replace(',', '', $now->i18nFormat([\IntlDateFormatter::FULL, \IntlDateFormatter::NONE]));
            $lugar_fecha = 'Mérida, Yucatán a ' . $fecha;
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), $lugar_fecha);
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $current_row += 4;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "A quien corresponda:");
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            $current_row += 4;
            $styleArray = ['font'  => [
                    'bold'  => false,
                    'size'  => 13,
                    'name'  => 'Arial',
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_JUSTIFY,
                ],
            ];

            $student_sex = $student['sex'];
            $body_text1 = 'Por medio de la presente, se HACE CONSTAR que %s es %s regular de';
            $body_text1_complement = $student_sex == 'M' ? 'el siguiente niño' : 'la siguiente niña';
            $body_text1_complement2 = $student_sex == 'M' ? 'alumno' : 'alumna';
            $body_text1 = sprintf($body_text1, $body_text1_complement, $body_text1_complement2);

            $body_text2 = 'este instituto con clave 31PPR0004R, en el presente curso escolar %s.';
            $body_text2 = sprintf($body_text2, $student->term->description);

            $body_text1 .= ' ' . $body_text2;

            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), $body_text1);
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight(45, 'pt');

            //NOMBRE
            $current_row += 7;
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
            $birth_date = new FrozenTime($student['birth_date']);
            $birth_date = $birth_date->i18nFormat('yyyy-MM-dd');
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), $birth_date);
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
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            $current_row += 3;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "Atentamente");
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight(35, 'pt');

            $current_row += 3;
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
                'bold'  => true,
                'size'  => 10,
                'name'  => 'Arial'
            ]];
            $current_row += 1;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "CUMBRES INTERNATIONAL SCHOOL MÉRIDA");
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight(35, 'pt');

            $styleArray = ['font'  => [
                'bold'  => false,
                'size'  => 7,
                'name'  => 'Arial'
            ]];
            $current_row += 1;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "Calle 5 x 18 s/n Glorieta Cumbres, Fraccionamiento Montecristo, 97133. Mérida, Yucatán.\t\tT.(999) 801 86 60");
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $styleArray = ['font'  => [
                'bold'  => true,
                'size'  => 7,
                'name'  => 'Arial'
            ]];
            $current_row += 1;
            $rangeCell = "B$current_row:N$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "cumbresmerida.com");
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        } // end foreach

        //PREPARA LA DESCARGA DEL ARCHIVO
        $fileName = $this->_encode('CONSTANCIAS_ESTUDIOS_' . $schoolCourse['name'] . '_' . date("ymdHis") . ".pdf");
        $writer = IOFactory::createWriter($spreadsheet, 'Mpdf');
        $writer->writeAllSheets();     
        $writer->save($fileName);

        // download file
        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Content-Length: ' . filesize($fileName));
        readfile($fileName);

        unlink($fileName);
        
        $this->redirect($this->referer());

    }

    /**
     * Download Single Proof Of Study method
     * 
     * @param string|null $id School Course id.
     * @return \Cake\Http\Response|null|void Redirects to referer.
     */
    public function downloadSingleProofOfStudy ($id) {
        // supress warning with Mpdf
        error_reporting(0);
        $this->Authorization->skipAuthorization();

        $schoolCourse = $this->SchoolCourses->get($id, [
            'contain' => ['Students.Terms.Institutes']
        ]);

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
        $drawing->setHeight(211);
        $drawing->setRotation(25);
        $drawing->getShadow()->setVisible(true);
        $drawing->getShadow()->setDirection(45);
        $drawing->setWorksheet($sheet);

        //FECHA
        $current_row += 3;
        $rangeCell = "F$current_row:N$current_row";
        $now = FrozenTime::now();
        $fecha = str_replace(',', '', $now->i18nFormat([\IntlDateFormatter::FULL, \IntlDateFormatter::NONE]));
        $lugar_fecha = 'Mérida, Yucatán a ' . $fecha;
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), $lugar_fecha);
        $sheet->mergeCells($rangeCell);
        $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $current_row += 4;
        $rangeCell = "B$current_row:N$current_row";
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "A quien corresponda:");
        $sheet->mergeCells($rangeCell);
        $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        $current_row += 4;
        $styleArray = ['font'  => [
                'bold'  => false,
                'size'  => 13,
                'name'  => 'Arial',
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_JUSTIFY,
            ],
        ];

        $student_sex = $student['sex'];
        $body_text1 = 'Por medio de la presente, se HACE CONSTAR que %s son %s regulares de';
        $body_text1_complement = 'los siguientes niños (as)';
        $body_text1_complement2 = 'alumnos (as)';
        $body_text1 = sprintf($body_text1, $body_text1_complement, $body_text1_complement2);

        $body_text2 = 'este instituto con clave 31PPR0004R, en el presente curso escolar %s.';
        $body_text2 = sprintf($body_text2, $student->term->description);

        $body_text1 .= ' ' . $body_text2;

        $rangeCell = "B$current_row:N$current_row";
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), $body_text1);
        $sheet->mergeCells($rangeCell);
        $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight(45, 'pt');
        
        // Agregar 3 alumnos por linea
        $iCont = 0;
        $iActiveRow = $current_row;
        $aRangeCell = [['A', 'D'], ['F', 'I'], ['K', 'N']];
        foreach ($schoolCourse->students as $student) {
            // Datos del alumno
            // Nombre
            $current_row = $iActiveRow + 7;
            $rowHeight = 23;
            $rangeCell = "{$aRangeCell[$iCont][0]}$current_row:{$aRangeCell[$iCont][1]}$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), $student['name']);
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight($rowHeight, 'pt');

            //CURP
            $current_row += 1;
            $rangeCell = "{$aRangeCell[$iCont][0]}$current_row:{$aRangeCell[$iCont][1]}$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), $student['curp']);
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight($rowHeight, 'pt');

            // Fecha de nacimiento
            $current_row += 1;
            $rangeCell = "{$aRangeCell[$iCont][0]}$current_row:{$aRangeCell[$iCont][1]}$current_row";
            $birth_date = new FrozenTime($student['birth_date']);
            $birth_date = $birth_date->i18nFormat('yyyy-MM-dd');
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), $birth_date);
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight($rowHeight, 'pt');

            // Sexo
            $sex = ["M"=>'Masculino', 'F'=>'FEMENINO'];
            $current_row += 1;
            $rangeCell = "{$aRangeCell[$iCont][0]}$current_row:{$aRangeCell[$iCont][1]}$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), $sex[$student['sex']]);
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight($rowHeight, 'pt');

            // Grado y grupo
            $current_row += 1;
            $rangeCell = "{$aRangeCell[$iCont][0]}$current_row:{$aRangeCell[$iCont][1]}$current_row";
            $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), $student['school_level']);
            $sheet->mergeCells($rangeCell);
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight($rowHeight, 'pt');

            $iCont++;
            if ($iCont > 2) {
                $iCont = 0;
                $iActiveRow = $current_row;
            }
        
        } // end foreach


        $current_row += 7;
        $rangeCell = "B$current_row:N$current_row";
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "Se expide la presente para los fines que corresponda.");
        $sheet->mergeCells($rangeCell);
        $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
        $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        $current_row += 3;
        $rangeCell = "B$current_row:N$current_row";
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "Atentamente");
        $sheet->mergeCells($rangeCell);
        $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
        $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight(35, 'pt');

        $current_row += 3;
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
            'bold'  => true,
            'size'  => 10,
            'name'  => 'Arial'
        ]];
        $current_row += 1;
        $rangeCell = "B$current_row:N$current_row";
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "CUMBRES INTERNATIONAL SCHOOL MÉRIDA");
        $sheet->mergeCells($rangeCell);
        $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
        $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight(35, 'pt');

        $styleArray = ['font'  => [
            'bold'  => false,
            'size'  => 7,
            'name'  => 'Arial'
        ]];
        $current_row += 1;
        $rangeCell = "B$current_row:N$current_row";
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "Calle 5 x 18 s/n Glorieta Cumbres, Fraccionamiento Montecristo, 97133. Mérida, Yucatán.\t\tT.(999) 801 86 60");
        $sheet->mergeCells($rangeCell);
        $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
        $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $styleArray = ['font'  => [
            'bold'  => true,
            'size'  => 7,
            'name'  => 'Arial'
        ]];
        $current_row += 1;
        $rangeCell = "B$current_row:N$current_row";
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "cumbresmerida.com");
        $sheet->mergeCells($rangeCell);
        $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
        $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        //PREPARA LA DESCARGA DEL ARCHIVO

        $fileName = $this->_encode('CONSTANCIAS_ESTUDIOS_' . $schoolCourse['name'] . '_' . date("ymdHis") . ".xlsx");

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        //$writer->save($fileName);
        // redirect output to client browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');

        $this->redirect($this->referer());
        /*$writer = new Xlsx($spreadsheet);

        $stream = new CallbackStream(function () use ($writer) {
            $writer->save('php://output');
        });
        $response = $this->response;
        return $response->withType('xlsx')
                        ->withCharset('UTF-8')
            ->withHeader('Content-Disposition', "attachment;filename=\"{$fileName}\"")
            ->withBody($stream);*/
    }

    /**
     * Get Schedule method
     * @param Entity $schedules
     * @return String $horario the schedule in a prety format
     */
    private function _getSchedule($schedules) {
        // horario
        $monday = '';
        $tuesday = '';
        $wednesday = '';
        $thursday = '';
        $friday = '';
        $saturday = '';
        $horario = [];
        $time_schedule = '';
        foreach ($schedules as $schedule) {
            $start = substr($schedule->start, 0, 5);//$this->Time->format($schedule->start, [IntlDateFormatter::NONE, IntlDateFormatter::SHORT]);
            $end = substr($schedule->end, 0, 5);//$this->Time->format($schedule->end, [IntlDateFormatter::NONE, IntlDateFormatter::SHORT]);
            $time_schedule = $start . ' - ' . $end;
            switch ($schedule->day_id) {
                case 1:
                    $horario[] = 'Lun';
                    break;
                case 2:
                    $horario[] = 'Mar';
                    break;
                case 3:
                    $horario[] = 'Miérc';
                    break;
                case 4:
                    $horario[] = 'Juev';
                    break;
                case 5:
                    $horario[] = 'Vier';
                    break;
            }
        }
        $horario = implode(', ', $horario);
        $horario .= ' de ' .$time_schedule;
        return $horario;
    }


    // decode function
    private function _encode($value = '') {
        return mb_convert_encoding($value, 'ISO-8859-1', 'UTF-8');
    }
}
