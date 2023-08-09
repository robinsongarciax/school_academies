<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Days Controller
 *
 * @property \App\Model\Table\DaysTable $Days
 * @method \App\Model\Entity\Day[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DaysController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $days = $this->paginate($this->Days);

        $this->set(compact('days'));
    }

    /**
     * View method
     *
     * @param string|null $id Day id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $day = $this->Days->get($id, [
            'contain' => ['Schedules'],
        ]);

        $this->set(compact('day'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $day = $this->Days->newEmptyEntity();
        if ($this->request->is('post')) {
            $day = $this->Days->patchEntity($day, $this->request->getData());
            if ($this->Days->save($day)) {
                $this->Flash->success(__('The day has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The day could not be saved. Please, try again.'));
        }
        $this->set(compact('day'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Day id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $day = $this->Days->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $day = $this->Days->patchEntity($day, $this->request->getData());
            if ($this->Days->save($day)) {
                $this->Flash->success(__('The day has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The day could not be saved. Please, try again.'));
        }
        $this->set(compact('day'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Day id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $day = $this->Days->get($id);
        if ($this->Days->delete($day)) {
            $this->Flash->success(__('The day has been deleted.'));
        } else {
            $this->Flash->error(__('The day could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
