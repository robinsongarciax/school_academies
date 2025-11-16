<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Utility\Text;

/**
 * IncidentReports Controller
 *
 * @property \App\Model\Table\IncidentReportsTable $IncidentReports
 * @method \App\Model\Entity\IncidentReport[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class IncidentReportsController extends AppController
{

    const UPLOAD_DIRECTORY = 'img/incident-reports/';

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $user = $this->Authentication->getIdentity();
        $this->Authorization->skipAuthorization();
        $incidentReports = $this->IncidentReports->find('all');
        $incidentReports->contain(['Students' => ['Terms'], 'Users', 'Teachers', 'SchoolCourses']);
        
        $incidentReports->where(['Terms.active' => 1]);
        $incidentReports->order(['IncidentReports.id' => 'DESC']);
        $incidentReports->all();        
        $this->set(compact('incidentReports'));
    }

    /**
     * View method
     *
     * @param string|null $id Incident Report id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $incidentReport = $this->IncidentReports->get($id, [
            'contain' => ['Students', 'Users', 'Teachers', 'SchoolCourses'],
        ]);
        $this->Authorization->authorize($incidentReport);

        $this->set(compact('incidentReport'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Authentication->getIdentity();
        $incidentReport = $this->IncidentReports->newEmptyEntity();
        $this->Authorization->authorize($incidentReport);
        if ($this->request->is('post')) {
            $incidentReport = $this->IncidentReports->patchEntity($incidentReport, $this->request->getData());
            $attachedFile = $this->request->getData('attachment');

            if ($attachedFile && $attachedFile->getError() === UPLOAD_ERR_OK) {
                $uuid = Text::uuid();
                $reportDestination = self::UPLOAD_DIRECTORY . $uuid . DS;
                mkdir($reportDestination, 0777, true);
                $reportDestination .= $attachedFile->getClientFilename();
                $attachedFile->moveTo($reportDestination);
                $incidentReport['attachment_path'] = $reportDestination;
            }

            $incidentReport['users_id'] = $user->getIdentifier();
            if ($this->IncidentReports->save($incidentReport)) {
                $this->Flash->success(__('The incident report has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The incident report could not be saved. Please, try again.'));
        }
        $teachers = $this->IncidentReports->Teachers->find('list', ['limit' => 200])->all();
        $this->set(compact('incidentReport', 'teachers'));
    }

    /**
     * Add Especial method
     * 
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwhise.
     */
    public function addEspecial()
    {
        $user = $this->Authentication->getIdentity();
        $incidentReport = $this->IncidentReports->newEmptyEntity();
        $this->Authorization->authorize($incidentReport);
        if ($this->request->is('post')) {
            $incidentReport = $this->IncidentReports->patchEntity($incidentReport, $this->request->getData());
            $attachedFile = $this->request->getData('attachment');

            if ($attachedFile && $attachedFile->getError() === UPLOAD_ERR_OK) {
                $uuid = Text::uuid();
                $reportDestination = self::UPLOAD_DIRECTORY . $uuid . DS;
                mkdir($reportDestination, 0777, true);
                $reportDestination .= $attachedFile->getClientFilename();
                $attachedFile->moveTo($reportDestination);
                $incidentReport['attachment_path'] = $reportDestination;
            }

            $incidentReport['users_id'] = $user->getIdentifier();
            if ($this->IncidentReports->save($incidentReport)) {
                $this->Flash->success(__('The incident report has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The incident report could not be saved. Please, try again.'));
        }
        $term = $this->IncidentReports->Students->Terms
                               ->find()
                               ->where(['active' => 1])
                               ->first();
        $conditions[] = ['Students.term_id' => $term->id];
        $students = $this->IncidentReports->Students->find('list')
            ->where(['Students.term_id' => $term->id]);

        $students->matching('SchoolCourses')
            ->order(['SchoolCoursesStudents.id' => 'asc'])
            ->where(['SchoolCoursesStudents.is_confirmed' => 1, 'SchoolCourses.tipo_academia' => 'CULTURAL']);
        $students->all();

        $this->set(compact('incidentReport', 'students'));
    } 

    /**
     * Edit method
     *
     * @param string|null $id Incident Report id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $incidentReport = $this->IncidentReports->get($id, [
            'contain' => ['Teachers', 'SchoolCourses', 'Students'],
        ]);
        $this->Authorization->authorize($incidentReport);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $incidentReport = $this->IncidentReports->patchEntity($incidentReport, $this->request->getData());
            $attachedFile = $this->request->getData('attachment');

            if ($attachedFile && $attachedFile->getError() === UPLOAD_ERR_OK) {
                $uuid = Text::uuid();
                $reportDestination = self::UPLOAD_DIRECTORY . $uuid . DS;
                mkdir($reportDestination, 0777, true);
                $reportDestination .= $attachedFile->getClientFilename();
                $attachedFile->moveTo($reportDestination);
                $incidentReport['attachment_path'] = $reportDestination;
            }
            if ($this->IncidentReports->save($incidentReport)) {
                $this->Flash->success(__('The incident report has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The incident report could not be saved. Please, try again.'));
        }
        $this->set(compact('incidentReport'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Incident Report id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $incidentReport = $this->IncidentReports->get($id);
        if ($this->IncidentReports->delete($incidentReport)) {
            $this->Flash->success(__('The incident report has been deleted.'));
        } else {
            $this->Flash->error(__('The incident report could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function searchSchoolCourses()
    {
        $this->Authorization->skipAuthorization();
        
        $this->request->allowMethod(['get', 'ajax']);
        $this->viewBuilder()->setClassName('Json');

        $teacherId = $this->request->getQuery('teacherId');
        $schoolCourses = $this->IncidentReports->SchoolCourses->find('list');
        $schoolCourses->contain(['Terms']);
        $schoolCourses->where(['teacher_id' => $teacherId, 'Terms.active' => 1])->all();
        
        
        $this->set([
            'success' => true,
            'schoolCourses' => $schoolCourses
        ]);

        $this->viewBuilder()->setOption('serialize', ['success', 'schoolCourses']);
        return;
    }

    public function searchSchoolCoursesEspecial()
    {
        $this->Authorization->skipAuthorization();
        
        $this->request->allowMethod(['get', 'ajax']);
        $this->viewBuilder()->setClassName('Json');

        $studentId = $this->request->getQuery('studentId');
        $schoolCourses = $this->IncidentReports->SchoolCourses->find('list');
        $schoolCourses->contain(['Terms']);
        $schoolCourses->where(['Terms.active' => 1]);

        $schoolCourses->matching('Students')
            ->order(['SchoolCoursesStudents.id' => 'asc'])
            ->where(['SchoolCoursesStudents.is_confirmed' => 1, 
                     'SchoolCourses.tipo_academia' => 'CULTURAL',
                     'Students.id' => $studentId]);
        $schoolCourses->all();
        
        
        $this->set([
            'success' => true,
            'schoolCourses' => $schoolCourses
        ]);

        $this->viewBuilder()->setOption('serialize', ['success', 'schoolCourses']);
        return;
    }

    public function searchStudents()
    {
        $this->Authorization->skipAuthorization();
        
        $this->request->allowMethod(['get', 'ajax']);
        $this->viewBuilder()->setClassName('Json');

        $schoolCourseId = $this->request->getQuery('schoolCourseId');
        $students = $this->IncidentReports->Students->find('list')
                                                    ->matching('SchoolCourses')
                                                    ->where(['SchoolCourses.id' => $schoolCourseId])->all();
        
        $this->set([
            'success' => true,
            'students' => $students
        ]);

        $this->viewBuilder()->setOption('serialize', ['success', 'students']);
        return;
    }

    public function searchTeachers()
    {
        $this->Authorization->skipAuthorization();
        
        $this->request->allowMethod(['get', 'ajax']);
        $this->viewBuilder()->setClassName('Json');

        $schoolCourseId = $this->request->getQuery('schoolCourseId');
        $teachers = $this->IncidentReports->SchoolCourses->Teachers->find('list')
            ->matching('SchoolCourses')
            ->where(['SchoolCourses.id' => $schoolCourseId])->all();
        
        $this->set([
            'success' => true,
            'teachers' => $teachers
        ]);

        $this->viewBuilder()->setOption('serialize', ['success', 'teachers']);
        return;
    }
}
