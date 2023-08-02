<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Institutes Controller
 *
 * @property \App\Model\Table\InstitutesTable $Institutes
 * @method \App\Model\Entity\Institute[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InstitutesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $institutes = $this->paginate($this->Institutes);

        $this->set(compact('institutes'));
    }

    /**
     * View method
     *
     * @param string|null $id Institute id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $institute = $this->Institutes->get($id, [
            'contain' => ['SchoolLevels', 'Terms'],
        ]);

        $this->set(compact('institute'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $institute = $this->Institutes->newEmptyEntity();
        if ($this->request->is('post')) {
            $institute = $this->Institutes->patchEntity($institute, $this->request->getData());
            if ($this->Institutes->save($institute)) {
                $this->Flash->success(__('The institute has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The institute could not be saved. Please, try again.'));
        }
        $schoolLevels = $this->Institutes->SchoolLevels->find('list', ['limit' => 200])->all();
        $this->set(compact('institute', 'schoolLevels'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Institute id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $institute = $this->Institutes->get($id, [
            'contain' => ['SchoolLevels'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $institute = $this->Institutes->patchEntity($institute, $this->request->getData());
            if ($this->Institutes->save($institute)) {
                $this->Flash->success(__('The institute has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The institute could not be saved. Please, try again.'));
        }
        $schoolLevels = $this->Institutes->SchoolLevels->find('list', ['limit' => 200])->all();
        $this->set(compact('institute', 'schoolLevels'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Institute id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $institute = $this->Institutes->get($id);
        if ($this->Institutes->delete($institute)) {
            $this->Flash->success(__('The institute has been deleted.'));
        } else {
            $this->Flash->error(__('The institute could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
