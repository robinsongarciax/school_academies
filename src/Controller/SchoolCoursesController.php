<?php
declare(strict_types=1);

namespace App\Controller;

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
        $this->paginate = [
            'contain' => ['Subjects', 'Teachers', 'Terms'],
        ];
        $schoolCourses = $this->paginate($this->SchoolCourses);

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
            'contain' => ['Subjects', 'Teachers', 'Terms', 'Schedules', 'Students'],
        ]);
        $this->Authorization->authorize($schoolCourse);
        $this->set(compact('schoolCourse'));
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
}
