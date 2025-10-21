<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Filesystem\File;
use Robotusers\Excel\Registry;
use Robotusers\Excel\Excel\Manager;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Cake\Http\CallbackStream;

/**
 * Students Controller
 *
 * @property \App\Model\Table\StudentsTable $Students
 * @method \App\Model\Entity\Student[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StudentsController extends AppController
{

    /**
     * El rol 3 es para alumnos
     */
    private $studentsRole = 4;

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Authorization->skipAuthorization();
        $students = $this->Students->find();
        $students->contain(['Terms' => ['conditions' => ['active', 1]]]);
        $this->set(compact('students'));
    }

    /**
     * View method
     *
     * @param string|null $id Student id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $student = $this->Students->get($id, [
            'contain' => ['Terms', 'Users', 'SchoolCourses'],
        ]);
        $this->Authorization->authorize($student);

        $aSchoolCoursesStudentsLogs = $this->fetchTable('SchoolCoursesStudentsLogs');
        $log = $aSchoolCoursesStudentsLogs->find('all')
                                          ->contain(['SchoolCourses' => 
                                                            ['fields' => ['name']]])
                                          ->where(['student_id' => $id,
                                                   'status' => 'DELETED'])
                                          ->order(['created' => 'desc']);
        
        $this->set(compact('log'));
        $this->set(compact('student'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $student = $this->Students->newEmptyEntity();
        $this->Authorization->authorize($student);
        if ($this->request->is('post')) {
            $request_data = $this->request->getData();
            $student = $this->Students->patchEntity($student, $request_data);

            $student_user = $this->Students->Users->newEmptyEntity();
            $student_user->name = trim($request_data['name']);
            $student_user->username = $request_data['curp'];
            $student_user->password = $request_data['curp'];
            $student_user->role_id = $this->studentsRole;
            $student_user->active = 1;

            if ($this->Students->Users->save($student_user)) {
                $student->user_id = $student_user->id;
                if ($this->Students->save($student)) {
                    $this->Flash->success(__('The student has been saved.'));

                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Students->Users->delete($student_user);
                    Log::write(
                        'emergency',
                            'Unable to store tutor'
                        );
                    foreach ($student->getErrors() as $errorKey => $errorValue){
                        Log::write(
                            'emergency',
                            'Error with field '.$errorKey
                        );
                    }
                }
            }
            $this->Flash->error(__('The student could not be saved. Please, try again.'));
        }
        $terms = $this->Students->Terms->find('list', ['conditions' => ['active' => 1]])->all();
        $users = $this->Students->Users->find('list', ['limit' => 200])->all();
        $schoolCourses = $this->Students->SchoolCourses->find('list', ['limit' => 200])->all();
        $this->set(compact('student', 'terms', 'users', 'schoolCourses'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Student id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $student = $this->Students->get($id, [
            'contain' => ['SchoolCourses'],
        ]);
        $this->Authorization->authorize($student);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $student = $this->Students->patchEntity($student, $this->request->getData());
            if ($this->Students->save($student)) {
                $this->Flash->success(__('The student has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The student could not be saved. Please, try again.'));
        }
        $terms = $this->Students->Terms->find('list', ['conditions' => ['active' => 1]])->all();
        $users = $this->Students->Users->find('list', ['limit' => 200])->all();
        $schoolCourses = $this->Students->SchoolCourses->find('list', ['limit' => 200])->all();
        $this->set(compact('student', 'terms', 'users', 'schoolCourses'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Student id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $student = $this->Students->get($id);
        $user_id = $student->user_id;
        $this->Authorization->authorize($student);
        if ($this->Students->delete($student)) {
            $this->Students->Users->delete($this->Students->Users->get($user_id));
            $this->Flash->success(__('The student has been deleted.'));
        } else {
            $this->Flash->error(__('The student could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function importFile () {
        set_time_limit(500);
        $student = $this->Students->newEmptyEntity();
        $this->Authorization->authorize($student);

        if ($this->request->is('post')) {
            $eliminar = $this->request->getData('eliminar');
            if ($eliminar) {
                $this->deleteAll();
            }
            $students_file = $this->request->getData('file');
            $stream = $students_file->getStream();
            $tmpFilePath = $stream->getMetadata('uri');
            $stream->close();

            $user_table = TableRegistry::get('Users');
            $manager = new Manager();
            $file = new File($tmpFilePath);
            $spreadsheet = $manager->getSpreadsheet($file);
            $worksheet = $spreadsheet->getActiveSheet();

            $worksheet->setCellValue('O1', 'username');
            $worksheet->setCellValue('P1', 'role_id');
            $last_row = (int) $worksheet->getHighestRow();
            for ($i = 2; $i <= $last_row; $i++) {
                if ($worksheet->getCell('B' . $i)->getValue() != null)
                    $worksheet->setCellValue('O' . $i, trim($worksheet->getCell('B' . $i)->getValue()));
                else 
                    $worksheet->setCellValue('O' . $i, $worksheet->getCell('B' . $i)->getValue());
                $worksheet->setCellValue('P' . $i, $this->studentsRole);
            }

            $user_table = $manager->read($worksheet, $user_table, [
                'startRow' => 2,
                'columnMap' => [
                    'B' => 'username',
                    'C' => 'name',
                    'O' => 'password',
                    'P' => 'role_id',
                ]
            ]);

            $table = TableRegistry::get('Students');
            $manager = new Manager();
            $file = new File($tmpFilePath);
            $spreadsheet = $manager->getSpreadsheet($file);
            $worksheet = $spreadsheet->getActiveSheet();

            $worksheet->setCellValue('Q1', 'term_id');
            $worksheet->setCellValue('R1', 'user_id');
            $last_row = (int) $worksheet->getHighestRow();
            for ($i = 2; $i <= $last_row; $i++) {
                $e_value = $worksheet->getCell('E' . $i)->getValue();
                if ($e_value != null && !empty($e_value) ) {
                    $worksheet->setCellValue('E' . $i,
                        date('Y-m-d', strtotime(str_replace('/', '-', $e_value)))
                    );
                }
                $worksheet->setCellValue('Q' . $i, $this->request->getData('term_id'));
                $worksheet->setCellValue('R' . $i, $user_table[$i-2]->id);
            }

            $table = $manager->read($worksheet, $table, [
                'startRow' => 2,
                'columnMap' => [
                    'A' => 'institute',
                    'B' => 'curp',
                    'C' => 'name',
                    'D' => 'sex',
                    'E' => 'birth_date',
                    'F' => 'level',
                    'G' => 'school_level',
                    'H' => 'school_group',
                    'I' => 'mother_name',
                    'J' => 'mother_phone',
                    'K' => 'mother_email',
                    'L' => 'father_name',
                    'M' => 'father_phone',
                    'N' => 'father_email',
                    'Q' => 'term_id',
                    'R' => 'user_id'
                ],
                'columnTypeMap' => [
                    'E' => 'date'
                ]
            ]);
            $this->Flash->success(__('The students has been imported correctly.'));
            $this->redirect(['action' => 'index']);
        }

        $terms = $this->Students->Terms->find('list', ['conditions' => ['active' => 1]])->all();
        $this->set(compact('student', 'terms'));
    }

    /**
     * Delete All method
     */
    private function deleteAll () {
        // delete user for the current period
        $term = $this->Students->Terms->find()
                              ->select('id')
                              ->where(['active' => 1])
                              ->first();

        $this->Students->deleteAll(['term_id' => $term->id]);

        $_users = $this->getTableLocator()->get('Users');
        $query = $_users->query();
        $query->delete()->where(['role_id'=>4, 'active' => 1])->execute();
    }


    /**
     * search method
     *
     * @return \Cake\Http\Response|null|void Redirects
     */
    public function search() {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['post']);
        $searchOptions = $this->request->getData();

        $conditions = $this->_buildConditions($searchOptions);

        $this->request->getSession()->write('searchOptions', $searchOptions);
        $this->request->getSession()->write('conditions', $conditions);
        return $this->redirect(['action' => 'dashboard?search=true']);
    }

    /**
     * 
     * 
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function dashboard()
    {
        $this->Authorization->skipAuthorization();
        $session = $this->request->getSession();

        if ($this->request->is('get')) {
            if (!$this->request->getQuery('search')) {
                $session->delete('searchOptions');
                $session->delete('conditions');
            }
        }
        $searchOptions = $session->read('searchOptions') ?? $this->_dashboardDefaultOptions();
        $conditions = $session->read('conditions') ?? [];

        $term = $this->Students->Terms
                               ->find()
                               ->where(['active' => 1])
                               ->first();
        $conditions[] = ['Students.term_id' => $term->id];

        $students = $this->_findStudetsPaginate($conditions);
        $students->matching('SchoolCourses')
                 ->order(['SchoolCoursesStudents.id' => 'asc'])
                 ->where(['SchoolCoursesStudents.is_confirmed' => 1]);

        // Buscar Academias
        $schoolCourses = $this->Students->SchoolCourses->find()
                                                       ->contain('Terms')
                                                       ->where(['Terms.id' => $term->id])
                                                       ->all();

        $this->set(compact('schoolCourses'));
        $this->set(compact('searchOptions'));
        $this->set('students', $this->paginate($students));
    }

    /**
     * Download School Courses Students Method
     */
    public function downloadSchoolCoursesStudents () {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['post']);
        $searchOptions = $this->request->getData();

        $conditions = $this->_buildConditions($searchOptions);

        $term = $this->Students->Terms
                               ->find()
                               ->where(['active' => 1])
                               ->first();
        $conditions[] = ['Students.term_id' => $term->id];

        // pr($conditions);die();

        $students = $this->_findStudets($conditions);
        $students->matching('SchoolCourses')
                 ->order(['Students.curp' => 'asc'])
                 ->where(['SchoolCoursesStudents.is_confirmed' => 1]);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // $sheet->setTitle();

        $sheet->getStyle("B1:B3")->getFont()->setBold(true);
        //GENERAL INFO
        $sheet->setCellValue("B1", "CUMBRES INTERNATIONAL SCHOOL MERIDA");
        $sheet->setCellValue("B2", "CICLO ESCOLAR " . $term->description);
        $sheet->setCellValue("B3", 'Academias');

        // nombre, curp, grado, grupo, academia, folio, cost, pagado
        $row = 5;
        $header = ['A' => 'Nombre', 
                    'B' => 'CURP',
                    'C' => 'Grado',
                    'D' => 'Grupo',
                    'E' => 'Academia',
                    'F' => 'Folio',
                    'G' => 'Costo',
                    'H' => 'Estatus pago'];

        // header for list
        foreach ($header as $key => $value) {
            $sheet->setCellValue($key . $row, $value);
        }
        $row++;
        
        foreach ($students as $student) {
            $sheet->setCellValue('A' . $row , $student->name);
            $sheet->setCellValue('B' . $row , $student->curp);
            $sheet->setCellValue('C' . $row , $student->school_level);
            $sheet->setCellValue('D' . $row , $student->school_group);
            $sheet->setCellValue('E' . $row , $student->_matchingData['SchoolCourses']->name);
            $sheet->setCellValue('F' . $row , '#'.$student->_matchingData['SchoolCoursesStudents']->id);
            $sheet->setCellValue('G' . $row , $student->_matchingData['SchoolCoursesStudents']->cost);
            $sheet->setCellValue('H' . $row , '');
            $row++;
        }

        $sFileName = 'LISTA_ACADEMIAS_'.date("ymdHis").".xlsx";
        $writer = new Xlsx($spreadsheet);

        $stream = new CallbackStream(function () use ($writer) {
            $writer->save('php://output');
        });
        $response = $this->response;
        return $response->withType('xlsx')
                        ->withHeader('Content-Disposition', "attachment;filename=\"{$sFileName}\"")
                        ->withBody($stream);
    }

    /*
     * Download Especial Method - Only for Admin
     */ 
    public function downloadSpecial()
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['post']);
        $searchOptions = $this->request->getData();

        $conditions = $this->_buildConditions($searchOptions);

        $term = $this->Students->Terms
                               ->find()
                               ->where(['active' => 1])
                               ->first();
        $conditions[] = ['Students.term_id' => $term->id];

        $students = $this->_findStudets($conditions);
        $students->contain('SchoolCourses')
                 ->innerJoinWith('SchoolCourses')
                 ->distinct(['Students.id'])
                 ->order(['Students.curp' => 'asc', 'SchoolCoursesStudents.id' => 'ASC'])
                 ->where(['SchoolCoursesStudents.is_confirmed' => 1]);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // $sheet->setTitle();

        $sheet->getStyle("B1:B3")->getFont()->setBold(true);
        //GENERAL INFO
        $sheet->setCellValue("B1", "CUMBRES INTERNATIONAL SCHOOL MERIDA");
        $sheet->setCellValue("B2", "CICLO ESCOLAR " . $term->description);
        $sheet->setCellValue("B3", 'Academias');

        // nombre, curp, grado, grupo, academia, folio, cost, pagado
        $row = 5;
        $header = ['A' => 'Nombre', 
                    'B' => 'CURP',
                    'C' => 'Grado',
                    'D' => 'Grupo',
                    'E' => 'Academia 1',
                    'F' => 'Academia 2',
                    'G' => 'Academia 3'];

        // header for list
        foreach ($header as $key => $value) {
            $sheet->setCellValue($key . $row, $value);
        }
        $row++;
        
        foreach ($students as $student) {
            $sheet->setCellValue('A' . $row , $student->name);
            $sheet->setCellValue('B' . $row , $student->curp);
            $sheet->setCellValue('C' . $row , $student->school_level);
            $sheet->setCellValue('D' . $row , $student->school_group);
            $count = 0;
            foreach ($student->school_courses as $schoolCourse) {
                switch($count) {
                    case 0:
                        $sheet->setCellValue('E' . $row , $schoolCourse->name);
                        break;
                    case 1:
                        $sheet->setCellValue('F' . $row , $schoolCourse->name);
                        break;
                    case 2:
                        $sheet->setCellValue('G' . $row , $schoolCourse->name);
                        break;
                }
                $count++;
            }
            $row++;
        }

        $sFileName = 'LISTA_ACADEMIAS_'.date("ymdHis").".xlsx";
        $writer = new Xlsx($spreadsheet);

        $stream = new CallbackStream(function () use ($writer) {
            $writer->save('php://output');
        });
        $response = $this->response;
        return $response->withType('xlsx')
                        ->withHeader('Content-Disposition', "attachment;filename=\"{$sFileName}\"")
                        ->withBody($stream);
        
    }

    private function _findStudets($conditions) {
        $students = $this->Students->find()
                                   ->where($conditions);

        return $students;
    }

    private function _findStudetsPaginate($conditions) {
        $students = $this->Students->find();
        $this->paginate = [
            'limit' => 10,
            'sortableFields' => [
                'id', 'name', 'curp', 'school_level', 'school_group', 'SchoolCourses.name', 'SchoolCoursesStudents.cost'
            ],
            'conditions' => $conditions
        ];

        return $students;
    }

    private function _dashboardDefaultOptions() {
        return [
            'inputSearch' => '',
            'tipoAcacemia' => '',
            'numCursos' => '',
            'tipoCurso' => '',
            'academia' => '',
            'pagado' => ''
        ];
    }

    /**
     * Build Conditions method
     * @param Array $searchOptions
     * @return $conditions
     */
    private function _buildConditions ($searchOptions) : array {
        $conditions = [];
        $students_result = null;
        // buscar por alumno
        if (!empty ($searchOptions['inputSearch'])) {
            $inputSearch = trim ($searchOptions['inputSearch']);
            $student_query = $this->Students->find('list')
                                            ->select(['id']);
            $student_query->where ($student_query->newExpr("LOCATE('{$inputSearch}', CONCAT_WS(' ', name, curp))"));
            $students_result = $student_query->all()->toArray();
            
            if (count ($students_result) == 0) {
                $students_result[] = 0;
            } 
        }

        // nùmero de cursos
        if (isset($students_result)) {
            if (array_key_exists(0, $students_result)) {
                $conditions[] = ['Students.id in' => array_keys($students_result)];
            } else if (!empty ($searchOptions['numCursos'])) {
                $_numCursos = $searchOptions['numCursos'];
                $this->loadModel('SchoolCoursesStudents');
                $numCursosQuery = $this->SchoolCoursesStudents->find()
                                                              ->select(['student_id']);
                $numCursosQuery->group('student_id')
                               ->order('student_id')
                               ->having(['count(*)' => $_numCursos])
                               ->where(['student_id in' => array_keys($students_result),
                                        ])->all();

                $resultSchoolCourses = [];
                foreach($numCursosQuery as $schoolCourses) {
                    $resultSchoolCourses[] = $schoolCourses->student_id;
                }
                
                if (count($resultSchoolCourses) > 0) {
                    $students_result = array_intersect(array_keys($students_result), array_values($resultSchoolCourses));
                    $conditions[] = ['Students.id in' => $students_result];
                } else 
                    $conditions[] = ['Students.id in' => [0]];

            } else {
                $conditions[] = ['Students.id in' => array_keys($students_result)];
            }
        } else if (!empty ($searchOptions['numCursos'])) {
            $_numCursos = $searchOptions['numCursos'];
            $this->loadModel('SchoolCoursesStudents');
            $numCursosQuery = $this->SchoolCoursesStudents->find()
                                                          ->select(['student_id']);
            $numCursosQuery->group('student_id')
                           ->order('student_id')
                           ->having(['count(*)' => $_numCursos])
                           ->all();

            $resultSchoolCourses = [];
            foreach($numCursosQuery as $schoolCourses) {
                $resultSchoolCourses[] = $schoolCourses->student_id;
            }

            if (count($resultSchoolCourses) > 0) {
                $conditions[] = ['Students.id in' => array_values($resultSchoolCourses)];
            } else 
                $conditions[] = ['Students.id in' => [0]];
        }

        // tipo academia
        if ($searchOptions['tipoAcacemia'] == 'DEPORTIVA')
            $conditions[] = ['tipo_academia' => 'DEPORTIVA'];
        if ($searchOptions['tipoAcacemia'] == 'CULTURAL')
            $conditions[] = ['tipo_academia' => 'CULTURAL'];

        // tipo curso
        if ($searchOptions['tipoCurso'] == 1)
            $conditions[] = ['cost' => 0];
        if ($searchOptions['tipoCurso'] == 2)
            $conditions[] = ['cost > ' => 0];

        // academia
        if (!empty ($searchOptions['academia'])) {
            $conditions[] = ['SchoolCourses.id' => $searchOptions['academia']];
        }

        // pagado
        if (!empty($searchOptions['pagado'])) {
            $conditions[] = ['SchoolCoursesStudents.is_pagado' => $searchOptions['pagado'] - 1];
        }

        return $conditions;
    }

}
