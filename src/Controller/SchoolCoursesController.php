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
        if ($this->Authentication->getIdentity()->role->name == 'ALUMNO' ) {
            // Traer los datos del estudiante
            $query = $this->SchoolCourses->Students->find('StudentInfo', [
                'user_id' => $this->Authentication->getIdentity()->getIdentifier()
            ])->all();

            $row = $query->first();

            $options = [
                'school_level_id' => $row->sl_id,
                'sex' => $row->sex
            ];

            // Traer los cursos relacionados con el grado escolar, sexo y la edad del estudiante
            $schoolCourses = $this->SchoolCourses->find('CoursesForStudent', $options)
                ->contain(['Subjects', 'Teachers', 'Terms'])
                ->all();
        } else {
            $schoolCourses = $this->SchoolCourses->find('all')
                ->contain(['Subjects', 'Teachers', 'Terms'])
                ->all();
        }

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
            'contain' => ['Subjects', 'Teachers', 'Terms', 'Schedules.Days', 'Students'],
        ]);
        $this->Authorization->authorize($schoolCourse);

        $days = $this->SchoolCourses->Schedules->Days->find('list', ['limit' => 200])->all();
        $schoolCourses = $this->SchoolCourses->Schedules->SchoolCourses->find('list', ['limit' => 200])->all();
        $schedule = $this->SchoolCourses->Schedules->newEmptyEntity();
        $this->set(compact('schoolCourse', 'schedule', 'days', 'schoolCourses'));
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

    public function courseRegistration() {
        $this->Authorization->skipAuthorization();
        $user_id = $this->Authentication->getIdentity()->getIdentifier();
        if ($this->Authentication->getIdentity()->role->name == 'ALUMNO' ) {
            $query = $this->SchoolCourses->Students->find('StudentInfo', [
                'user_id' => $user_id
            ])->all();
            $row = $query->first();

            $options = [
                'school_level_id' => $row->sl_id,
                'sex' => $row->sex,
                'student_id' => $row->student_id
            ];

            // Traer los cursos relacionados con el grado escolar, sexo y la edad del estudiante
            $schoolCourses = $this->SchoolCourses->find('CoursesForStudent', $options)
                ->contain(['Subjects', 'Teachers', 'Terms', 'Schedules'])
                ->all();
            $studentCourses = $this->SchoolCourses->Students->find('StudentCourses', ['student_id' => $row->student_id])->all()->toList();
        }

        $this->set(compact('schoolCourses', 'studentCourses'));
    }

    public function signup($id = null) {
        $this->request->allowMethod(['post', 'put']);
        $schoolCourse = $this->SchoolCourses->get($id);
        $this->Authorization->authorize($schoolCourse);
        // get student info
        $student = $this->SchoolCourses->Students->find('all')
            ->where(['user_id' => $this->request->getAttribute('identity')->getIdentifier()])
            ->all()
            ->first();
        $schoolCourse->students = [$student];
        if ($this->SchoolCourses->save($schoolCourse)) {
            $this->Flash->success(__('You have signed up for the course ') . $schoolCourse->name . '.');
        } else {
            $this->Flash->error(__('The school course could not be taken. Please, try again.'));
        }
        return $this->redirect(['action' => 'courseRegistration']);
    }

    public function studentRegistration($id = null) {
        $this->Authorization->skipAuthorization();
        $schoolCourse = $this->SchoolCourses->get($id, [
            'contain' => ['Students', 'Subjects' => ['SchoolLevels']],
        ]);
        
        $schoolLevels = [];
        foreach ($schoolCourse->subject->school_levels as $school_lavel) {
            $schoolLevels[] = $school_lavel->name;
        }

        $sex = $schoolCourse->subject->sex;
        $sex = $sex === 'X' ? ['F', 'M'] : [$sex];

        $students = $this->SchoolCourses->Students
            ->find('all', ['limit' => 200])
            ->leftJoin(['SchoolCoursesStudents' => 'school_courses_students'],
                [
                    'SchoolCoursesStudents.student_id = Students.id'
                ])
            ->where([
                'school_level in' => $schoolLevels,
                'sex in ' => $sex,
                'student_id is null',
                '(school_course_id = 1 OR school_course_id is null)'
            ])
            ->all();

        $this->set(compact('schoolCourse', 'students'));
    }

    /**
     * Delete method
     *
     * @param string|null $id School Course id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function enroll($id = null, array $students_id = null) {
        $schoolCourse = $this->SchoolCourses->get($id, [
            'contain' => ['Students']
        ]);
        $this->Authorization->authorize($schoolCourse);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $students = $this->SchoolCourses->Students
                ->find('all')
                ->where(['id in' => $students_id])
                ->all()
                ->toArray();
            $schoolCourse->students = array_merge($schoolCourse->students, $students);
            // pr($schoolCourse);die();

            if ($this->SchoolCourses->save($schoolCourse)) {
                $this->Flash->success(__('The student has been enrolled.'));
                return $this->redirect(['action' => 'studentRegistration', $id]);
            }
            $this->Flash->error(__('The student could not be enrolled. Please, try again.'));

        }
        return $this->redirect(['action' => 'studentRegistration', $id]);
    }
}
