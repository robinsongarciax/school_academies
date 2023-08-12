<?php
declare(strict_types=1);

namespace App\Controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Cake\Http\CallbackStream;

/**
 * SchoolCoursesStudents Controller
 *
 * @property \App\Model\Table\SchoolCoursesStudentsTable $SchoolCoursesStudents
 * @method \App\Model\Entity\SchoolCoursesStudent[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SchoolCoursesStudentsController extends AppController
{
    /**
     * Delete method
     *
     * @param string|null $id School Courses Student id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['post', 'delete']);
        $schoolCoursesStudent = $this->SchoolCoursesStudents->get($id);
        if ($this->SchoolCoursesStudents->delete($schoolCoursesStudent)) {
            $this->Flash->success(__('The school courses student has been deleted.'));
        } else {
            $this->Flash->error(__('The school courses student could not be deleted. Please, try again.'));
        }

        return $this->redirect($this->referer());
    }

    /**
     * Confirm method
     *
     * @param string|null $id School Courses Student id.
     * @return \Cake\Http\Response|null|void Redirects to SchoolCourses courseRegistration.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function confirm($id = null)
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['post', 'patch']);
        $schoolCoursesStudent = $this->SchoolCoursesStudents->get($id);
        $schoolCoursesStudent->is_confirmed = 1;
        if ($this->SchoolCoursesStudents->save($schoolCoursesStudent)) {
            $this->Flash->success(__('The school courses student has been confirmed.'));
        } else {
            $this->Flash->error(__('The school courses student could not be confirmed. Please, try again.'));
        }

        return $this->redirect($this->referer());
    }

    /**
     * Confirm All Students method
     *
     * @return \Cake\Http\Response|null|void Redirects to SchoolCourses courseRegistration.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function confirmAllStudents($course_id = null)
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['post', 'patch']);

        $schoolCourse = $this->SchoolCoursesStudents->SchoolCourses->get($course_id, [
            'fields' => ['capacity']
        ]);

        $schoolCoursesStudent = $this->SchoolCoursesStudents->findBySchoolCourseId($course_id);
        $arr_is_confirmed = array_values(array_column($schoolCoursesStudent->toArray(), 'is_confirmed'));
        $total_rows = $schoolCoursesStudent->count();
        $total_confirmed = 0;
        foreach ($arr_is_confirmed as $is_confirmed) {
            if ($is_confirmed) $total_confirmed++;
        }

        $available = $schoolCourse->capacity - $total_confirmed;
        $total_pre_enrolled = $total_rows - $total_confirmed;
        if ($available < $total_pre_enrolled) {
            $this->Flash->error(__('The student list is higher than the allowed capacity. Delete a few students to continue.'));
            return $this->redirect($this->referer());
        }
        
        $fields = [
            'is_confirmed' => '1'
        ];
        $conditions = [
            'is_confirmed' => '0',
            'school_course_id' => $course_id
        ];
        
        if ($this->SchoolCoursesStudents->updateAll($fields, $conditions)) {
            $this->Flash->success(__('The school courses student has been confirmed.'));
        } else {
            $this->Flash->error(__('The school courses student could not be confirmed. Please, try again.'));
        }

        return $this->redirect($this->referer());
    }

    public function printForm($id){
        $this->Authorization->skipAuthorization();

        //OBTIENE LA INFORMACION DE LA BDD
        $schoolCoursesStudents = $this->SchoolCoursesStudents->get($id, [
            'contain' => ['Students', 'SchoolCourses.Subjects', 'SchoolCourses.Teachers', 'SchoolCourses.Schedules.Days']
        ])->toArray();
        //pr($schoolCoursesStudents);die();

        //CREA EL EXCEL
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //MARGE DE PAGINA
        $sheet->getPageMargins()->setTop(0.1);
        $sheet->getPageMargins()->setLeft(0.4);
        $sheet->getPageMargins()->setRight(0.4);
        $sheet->getPageMargins()->setBottom(0.1);

        //HORA DE IMPRESION
        $current_row = 1;
        $sheet->setCellValue("A$current_row", date('Y/m/d H:i:s'));
        $sheet->getStyle("A$current_row:B$current_row")->getFont()->setSize(6);
        $sheet->mergeCells("A$current_row:B$current_row");

        //TITULO TOP HEADER IMPRESION;
        $sheet->setCellValue("F$current_row", "Cumbres International School Mérida | Inscripciones a Academias Culturales y Deportivas");
        $sheet->getStyle("F$current_row:K$current_row")->getFont()->setSize(6);
        $sheet->mergeCells("F$current_row:K$current_row");

        //TITULO
        $current_row += 6;
        $rangeCell = "B$current_row:H$current_row";
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "REGISTRO DE INSCRIPCIÓN");
        $sheet->getStyle($rangeCell)->applyFromArray(['font'  => [
            'bold'  => false,
            'size'  => 14,
            'name'  => 'Arial'
        ]]);
        $sheet->mergeCells($rangeCell);

        for($i = 0; $i < 2; $i++):

        //FECHA DEL DOC
        $current_row += 2;
        $rangeCell = "B$current_row:H$current_row";
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "Fecha de Inscripción: ".$schoolCoursesStudents['created']);
        $sheet->getStyle($rangeCell)->applyFromArray(['font'  => [
            'bold'  => false,
            'size'  => 10,
            'name'  => 'Arial'
        ]]);
        $sheet->mergeCells($rangeCell);

        //NUMERO DE FOLIO
        $rangeCell = "K$current_row:M$current_row";
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "FOLIO #".$schoolCoursesStudents['id']);
        $sheet->getStyle($rangeCell)->applyFromArray(['font'  => [
            'bold'  => false,
            'size'  => 15,
            'name'  => 'Arial'
        ]]);
        $sheet->mergeCells($rangeCell);

        //LINE
        $current_row += 2;
        $rangeCell = "A$current_row:M$current_row";
        $sheet->getStyle($rangeCell)->applyFromArray(['borders'  => [
            'top' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => 'C7C6C6']
            ]
        ]]);

        $styleArray = ['font'  => [
            'bold'  => false,
            'size'  => 11,
            'name'  => 'Arial'
        ]];

        //INFO ALUMNO
        //TITLE ALUMNO
        $current_row += 2;
        $rangeCell = "B$current_row:H$current_row";
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "ALUMNO");
        $sheet->getStyle($rangeCell)->applyFromArray(['font'  => [
            'bold'  => false,
            'size'  => 15,
            'name'  => 'Arial'
        ]]);
        $sheet->mergeCells($rangeCell);

        $rangeCell = "J$current_row:N$current_row";
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "ACADEMIA");
        $sheet->getStyle($rangeCell)->applyFromArray(['font'  => [
            'bold'  => false,
            'size'  => 15,
            'name'  => 'Arial'
        ]]);
        $sheet->mergeCells($rangeCell);

        //MATRICULA ALUMNO
        $current_row += 2;
        $rangeCell = "B$current_row:H$current_row";
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "Matricula: ".$schoolCoursesStudents['student']['curp']);
        $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
        $sheet->mergeCells($rangeCell);
        //NOMBRE ACADEMIA
        $rangeCell = "J$current_row:N$current_row";
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "Nombre: ".$schoolCoursesStudents['school_course']['subject']['name']);
        $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
        $sheet->mergeCells($rangeCell);

        //NOMBRE ALUMNO
        $current_row += 1;
        $rangeCell = "B$current_row:H$current_row";
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "Nombre: ".$schoolCoursesStudents['student']['name']);
        $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
        $sheet->mergeCells($rangeCell);
        //INSTRUCTOR ACADEMIA
        $rangeCell = "J$current_row:N$current_row";
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "Instructor: ".$schoolCoursesStudents['school_course']['teacher']['name']);
        $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
        $sheet->mergeCells($rangeCell);

        //INSTITUTO ALUMNO
        $current_row += 1;
        $rangeCell = "B$current_row:H$current_row";
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "Instituto: ".$schoolCoursesStudents['student']['institute']);
        $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
        $sheet->mergeCells($rangeCell);
        //TIPO ACADEMIA
        $rangeCell = "J$current_row:N$current_row";
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "Tipo: ".$schoolCoursesStudents['school_course']['subject']['tipo_academia']);
        $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
        $sheet->mergeCells($rangeCell);

        //GRADO ALUMNO
        $current_row += 1;
        $rangeCell = "B$current_row:H$current_row";
        $sheet->setCellValue(substr($rangeCell, 0, strlen(strval($current_row))+1), "Grado: ".$schoolCoursesStudents['student']['school_level']);
        $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
        $sheet->mergeCells($rangeCell);

        //DAYS HEADER
        $current_row += 3;
        $styleArray = ['font'  => [
            'bold'  => false,
            'size'  => 12,
            'name'  => 'Arial'
        ]];
        $daysHeader = ['B'=>'Lunes', 'D'=>'Martes', 'F'=>'Miércoles', 'H'=>'Jueves', 'J'=>'Viernes'];
        foreach ($daysHeader as $col=>$day):
            $sheet->setCellValue($col.$current_row, $day);//22
        endforeach;

        //SE COMBINAN CELDAS
        $sheet->mergeCells("B$current_row:C$current_row");
        $sheet->mergeCells("D$current_row:E$current_row");
        $sheet->mergeCells("F$current_row:G$current_row");
        $sheet->mergeCells("H$current_row:I$current_row");
        $sheet->mergeCells("J$current_row:K$current_row");
        //SE APLICAN FORMATOS A LAS CELDAS DE ENCABEZADO DE DIAS
        $rangeCell ="B$current_row:K$current_row";
        $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
        $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($rangeCell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight(30, 'pt');
        $sheet->getStyle($rangeCell)->applyFromArray(['borders'  => [
            'top' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => 'C7C6C6']
            ],
            'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => 'C7C6C6']
            ]
        ]]);

        $col_header = [
            "Lunes" => "B",
            "Martes" => "D",
            "Miércoles" => "F",
            "Jueves" => "H",
            "Viernes" => "J"
        ];

        //AGREGANDO LOS HORARIOS A LA TABLA
        $styleArray = ['font'  => [
            'bold'  => false,
            'size'  => 10,
            'name'  => 'Arial'
        ]];
        $current_row += 1;
        foreach ($schoolCoursesStudents['school_course']['schedules'] as $schedule):
            $sheet->setCellValue($col_header[$schedule['day']['name']].$current_row, date("H:i", strtotime($schedule['start'])).' - '.date("H:i", strtotime($schedule['end'])));
            //SE COMBINAN CELDAS
            $sheet->mergeCells("B$current_row:C$current_row");
            $sheet->mergeCells("D$current_row:E$current_row");
            $sheet->mergeCells("F$current_row:G$current_row");
            $sheet->mergeCells("H$current_row:I$current_row");
            $sheet->mergeCells("J$current_row:K$current_row");
            //SE APLICAN FORMATOS A LAS CELDAS DE ENCABEZADO DE DIAS
            $rangeCell = "B$current_row:K$current_row";
            $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
            $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($rangeCell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getRowDimension($current_row)->setRowHeight(25, 'pt');
            $sheet->getStyle($rangeCell)->applyFromArray(['borders'  => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'C7C6C6']
                ]
            ]]);
        endforeach;

        //TOTAL A PAGAR
        $styleArray = ['font'  => [
            'bold'  => false,
            'size'  => 11,
            'name'  => 'Arial'
        ]];
        $current_row += 1;
        $rangeCell = "H$current_row:I$current_row";
        $sheet->setCellValue("H$current_row", "Total a Pagar");
        $sheet->mergeCells($rangeCell);
        $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle($rangeCell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle($rangeCell)->applyFromArray($styleArray);

        //PRECIO
        $rangeCell = "J$current_row:K$current_row";
        $sheet->setCellValue("J$current_row", '$'.number_format(floatval($schoolCoursesStudents['school_course']['price']), 2, '.', ','));
        $sheet->mergeCells($rangeCell);
        $sheet->getStyle($rangeCell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle($rangeCell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle($rangeCell)->applyFromArray($styleArray);
        //LINEA TOTAL
        $sheet->getStyle("H$current_row:K$current_row")->applyFromArray(['borders'  => [
            'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => 'C7C6C6']
            ]
        ]]);
        $sheet->getRowDimension($current_row)->setRowHeight(30, 'pt');

        $current_row += 2;
        //LINE
        $rangeCell = "A$current_row:M$current_row";
        $sheet->getStyle($rangeCell)->applyFromArray(['borders'  => [
            'top' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => 'C7C6C6']
            ]
        ]]);

            endfor;

        //PREPARA LA DESCARGA DEL ARCHIVO
        $fileName = 'INSCRIPCION_'.$schoolCoursesStudents["student"]['name'].'_'.date("ymdHis").".pdf";
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Mpdf');
        $stream = new CallbackStream(function () use ($writer) {
            $writer->save('php://output');
        });
        return $this->response->withType('pdf')
            ->withHeader('Content-Disposition', "attachment;filename=\"{$fileName}\"")
            ->withBody($stream);
    }
}
