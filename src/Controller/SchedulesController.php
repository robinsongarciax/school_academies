<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Schedules Controller
 *
 * @property \App\Model\Table\SchedulesTable $Schedules
 * @method \App\Model\Entity\Schedule[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SchedulesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Days'],
        ];
        $schedules = $this->paginate($this->Schedules);

        $this->set(compact('schedules'));
    }

    /**
     * View method
     *
     * @param string|null $id Schedule id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $schedule = $this->Schedules->get($id, [
            'contain' => ['Days', 'SchoolCourses'],
        ]);

        $this->set(compact('schedule'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add($school_course_id = null)
    {
        $this->Authorization->skipAuthorization();
        $schedule = $this->Schedules->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $days = $data['days'];
            $schedules = [];
            for ($i = 0; $i < count($days); $i++) {
                if ($i > 0)
                   $schedule = $this->Schedules->newEmptyEntity();
                $schedule = $this->Schedules->patchEntity($schedule, $data);
                $schedule->day_id = $days[$i];
                $schedule->school_courses = [$this->Schedules->SchoolCourses
                    ->get($data['school_course_id'])
                ];
                $schedules[] = $schedule;
            }
            // pr($schedule);die();
            if ($this->Schedules->saveMany($schedules)) {
                $this->Flash->success(__('The schedule has been saved.'));

                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('The schedule could not be saved. Please, try again.'));
        }
        $days = $this->Schedules->Days->find('list', ['limit' => 200])->all();
        $schoolCourses = $this->Schedules->SchoolCourses
            ->find('list', ['limit' => 200])
            ->where(['id' => $school_course_id])
            ->all();
        $this->set(compact('schedule', 'days', 'schoolCourses'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Schedule id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->Authorization->skipAuthorization();
        $schedule = $this->Schedules->get($id, [
            'contain' => ['SchoolCourses'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $schedule = $this->Schedules->patchEntity($schedule, $this->request->getData());
            if ($this->Schedules->save($schedule)) {
                $this->Flash->success(__('The schedule has been saved.'));

                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('The schedule could not be saved. Please, try again.'));
        }
        $days = $this->Schedules->Days->find('list', ['limit' => 200])->all();
        $schoolCourses = $this->Schedules->SchoolCourses->find('list', ['limit' => 200])->all();
        $this->set(compact('schedule', 'days', 'schoolCourses'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Schedule id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['post', 'delete']);
        $schedule = $this->Schedules->get($id);
        if ($this->Schedules->delete($schedule)) {
            $this->Flash->success(__('The schedule has been deleted.'));
        } else {
            $this->Flash->error(__('The schedule could not be deleted. Please, try again.'));
        }

        return $this->redirect($this->referer());
    }
}
