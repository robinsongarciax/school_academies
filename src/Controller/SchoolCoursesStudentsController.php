<?php
declare(strict_types=1);

namespace App\Controller;

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

        return $this->redirect(['controller' => 'SchoolCourses','action' => 'courseRegistration']);
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
        $this->request->allowMethod(['post', 'delete']);
        $schoolCoursesStudent = $this->SchoolCoursesStudents->get($id);
        $schoolCoursesStudent->is_confirmed = 1;
        if ($this->SchoolCoursesStudents->save($schoolCoursesStudent)) {
            $this->Flash->success(__('The school courses student has been confirmed.'));
        } else {
            $this->Flash->error(__('The school courses student could not be confirmed. Please, try again.'));
        }

        return $this->redirect(['controller' => 'SchoolCourses','action' => 'courseRegistration']);
    }
}
