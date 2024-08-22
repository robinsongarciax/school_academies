<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Teachers Controller
 *
 * @property \App\Model\Table\TeachersTable $Teachers
 * @method \App\Model\Entity\Teacher[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TeachersController extends AppController
{

    /**
     * El rol 3 es para maestros
     */
    private $teachersRol = 3;

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Authorization->skipAuthorization();
        $teachers = $this->Teachers->find()
                                   ->contain(['Users'])
                                   ->where(['Teachers.active' => 1]);
        $this->set(compact('teachers'));
    }

    /**
     * View method
     *
     * @param string|null $id Teacher id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $teacher = $this->Teachers->get($id, [
            'contain' => ['Users', 'SchoolCourses'],
        ]);
        $this->Authorization->authorize($teacher);
        $this->set(compact('teacher'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $teacher = $this->Teachers->newEmptyEntity();
        $this->Authorization->authorize($teacher);
        if ($this->request->is('post')) {
            $request_data = $this->request->getData();
            $teacher = $this->Teachers->patchEntity($teacher,
                $request_data
            );

            $teacher_user = $this->Teachers->Users->newEmptyEntity();
            $teacher_user->name = trim($request_data['name']);
            $teacher_user->username = $request_data['users']['name'];
            $teacher_user->password = $request_data['users']['password'];
            $teacher_user->role_id = $this->teachersRol;
            $teacher_user->active = 1;
            // Save the user first
            if ($this->Teachers->Users->save($teacher_user)) {
                $teacher->user_id = $teacher_user->id;
                if ($this->Teachers->save($teacher)) {
                    $this->Flash->success(__('The teacher has been saved.'));

                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Teachers->Users->delete($teacher_user);
                }
            }
            
            $this->Flash->error(__('The teacher could not be saved. Please, try again.'));
        }
        $users = $this->Teachers->Users->find('list', ['limit' => 200])->all();
        $this->set(compact('teacher', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Teacher id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $teacher = $this->Teachers->get($id, [
            'contain' => ['Users'],
        ]);
        $this->Authorization->authorize($teacher);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $teacher = $this->Teachers->patchEntity($teacher, $this->request->getData());
            if ($this->Teachers->save($teacher)) {
                $this->Flash->success(__('The teacher has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The teacher could not be saved. Please, try again.'));
        }        
        $this->set(compact('teacher'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Teacher id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $teacher = $this->Teachers->get($id);
        $user_id = $teacher->user_id;
        $this->Authorization->authorize($teacher);
        if ($this->Teachers->delete($teacher)) {
            $this->Teachers->Users->delete($this->Teachers->Users->get($user_id));
            $this->Flash->success(__('The teacher has been deleted.'));
        } else {
            $this->Flash->error(__('The teacher could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Deactive method
     *
     * @param string|null $id Teacher id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function deactive ($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $teacher = $this->Teachers->get($id);
        $user_id = $teacher->user_id;
        $this->Authorization->authorize($teacher);
        $teacher->active = 0;
        if ($this->Teachers->save($teacher)) {
            $aUser = $this->Teachers->Users->get($user_id);
            $aUser->active = 0;
            $this->Teachers->Users->save($aUser);
            $this->Flash->success(__('The teacher has been deactivated.'));
        } else {
            $this->Flash->error(__('The teacher could not be deactivated. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function myAcademies(){
        $this->Authorization->skipAuthorization();
        $userId = $this->request->getAttribute('identity')->getIdentifier();
        $teacher = $this->Teachers->find()->where(['user_id'=>$userId])->contain(['SchoolCourses.Terms'])->first()->toArray();
        $schoolCourses = $teacher["school_courses"];
        $this->set(compact('schoolCourses'));
    }
}
