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
            'contain' => ['Terms', 'Users', 'SchoolCourses', 'Courses'],
        ]);
        $this->Authorization->authorize($student);
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

            // $student_user = $this->Students->Users->newEmptyEntity();
            // $student_user->name = trim($request_data['name']);
            // $student_user->username = $request_data['curp'];
            // $student_user->password = $request_data['curp'];
            // $student_user->role_id = $this->studentsRol;
            // $student_user->active = 1;

            // if ($this->Students->Users->save($student_user)) {
                $student->user_id = 3;
                if ($this->Students->save($student)) {
                    $this->Flash->success(__('The student has been saved.'));

                    return $this->redirect(['action' => 'index']);
                } else {
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
            // }
            $this->Flash->error(__('The student could not be saved. Please, try again.'));
        }
        $terms = $this->Students->Terms->find('list', ['limit' => 200])->all();
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
        $terms = $this->Students->Terms->find('list', ['limit' => 200])->all();
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
        $this->Authorization->authorize($student);
        if ($this->Students->delete($student)) {
            $this->Flash->success(__('The student has been deleted.'));
        } else {
            $this->Flash->error(__('The student could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function importFile() {
        set_time_limit(300);
        $student = $this->Students->newEmptyEntity();
        $this->Authorization->authorize($student);

        if ($this->request->is('post')) {
            $eliminar = $this->request->getData('eliminar');
            if($eliminar){
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

            $worksheet->setCellValue('I1', 'username');
            $worksheet->setCellValue('J1', 'role_id');
            $last_row = (int) $worksheet->getHighestRow();
            for ($i = 2; $i <= $last_row; $i++) {
                $worksheet->setCellValue('I' . $i, $worksheet->getCell('B' . $i)->getValue());
                $worksheet->setCellValue('J' . $i, $this->studentsRole);
            }

            $user_table = $manager->read($worksheet, $user_table, [
                'startRow' => 2,
                'columnMap' => [
                    'B' => 'username',
                    'C' => 'name',
                    'I' => 'password',
                    'J' => 'role_id',
                ]
            ]);

            $table = TableRegistry::get('Students');
            $manager = new Manager();
            $file = new File($tmpFilePath);
            $spreadsheet = $manager->getSpreadsheet($file);
            $worksheet = $spreadsheet->getActiveSheet();

            $worksheet->setCellValue('K1', 'term_id');
            $worksheet->setCellValue('L1', 'user_id');
            $last_row = (int) $worksheet->getHighestRow();
            for ($i = 2; $i <= $last_row; $i++) {
                $e_value = $worksheet->getCell('E' . $i)->getValue();
                if ($e_value != null && !empty($e_value) ) {
                    $worksheet->setCellValue('E' . $i,
                        date('Y-m-d', strtotime(str_replace('/', '-', $e_value)))
                    );
                }
                $worksheet->setCellValue('K' . $i, $this->request->getData('term_id'));
                $worksheet->setCellValue('L' . $i, $user_table[$i-2]->id);
            }

            // pr( strtotime($var) );
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
                    'K' => 'term_id',
                    'L' => 'user_id'
                ],
                'columnTypeMap' => [
                    'E' => 'date'
                ]
            ]);
            $this->Flash->success(__('The students has been imported correctly.'));

        }

        $terms = $this->Students->Terms->find('list', ['limit' => 200])->all();
        $this->set(compact('student', 'terms'));
    }

    public function constanciaEstudios($studentId){
        setlocale(LC_TIME, "spanish");//PARA TENER LA FECHA EN ESPANOL
        $this->Authorization->skipAuthorization();

        $student = $this->Students->get($studentId, [
            'contain' => ['Terms', 'Users']
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
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "P. Carlos Pi Pérez L.C.");
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
        $stream = new CallbackStream(function () use ($writer) {
            $writer->save('php://output');
        });
        return $this->response->withType('pdf')
            ->withHeader('Content-Disposition', "attachment;filename=\"{$fileName}\"")
            ->withBody($stream);
    }

    private function deleteAll(){
        $this->Students->deleteAll([]);
        $_users = $this->getTableLocator()->get('Users');
        $query = $_users->query();
        $query->delete()->where(['role_id'=>4])->execute();
    }
}
