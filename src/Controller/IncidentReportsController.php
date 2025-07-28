<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * IncidentReports Controller
 *
 * @property \App\Model\Table\IncidentReportsTable $IncidentReports
 * @method \App\Model\Entity\IncidentReport[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class IncidentReportsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Authorization->skipAuthorization();
        $incidentReports = $this->IncidentReports->find('all');
        $incidentReports->contain(['Students', 'Users', 'Teachers', 'SchoolCourses']);
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
        $schoolCourses = $this->IncidentReports->SchoolCourses->find('list')->where(['teacher_id' => $teacherId])->all();
        
        
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
}
