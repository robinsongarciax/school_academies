<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * SchoolLevels Controller
 *
 * @property \App\Model\Table\SchoolLevelsTable $SchoolLevels
 * @method \App\Model\Entity\SchoolLevel[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SchoolLevelsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $schoolLevels = $this->paginate($this->SchoolLevels);

        $this->set(compact('schoolLevels'));
    }

    /**
     * View method
     *
     * @param string|null $id School Level id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $schoolLevel = $this->SchoolLevels->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('schoolLevel'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $schoolLevel = $this->SchoolLevels->newEmptyEntity();
        if ($this->request->is('post')) {
            $schoolLevel = $this->SchoolLevels->patchEntity($schoolLevel, $this->request->getData());
            if ($this->SchoolLevels->save($schoolLevel)) {
                $this->Flash->success(__('The school level has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The school level could not be saved. Please, try again.'));
        }
        $this->set(compact('schoolLevel'));
    }

    /**
     * Edit method
     *
     * @param string|null $id School Level id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $schoolLevel = $this->SchoolLevels->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $schoolLevel = $this->SchoolLevels->patchEntity($schoolLevel, $this->request->getData());
            if ($this->SchoolLevels->save($schoolLevel)) {
                $this->Flash->success(__('The school level has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The school level could not be saved. Please, try again.'));
        }
        $this->set(compact('schoolLevel'));
    }

    /**
     * Delete method
     *
     * @param string|null $id School Level id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $schoolLevel = $this->SchoolLevels->get($id);
        if ($this->SchoolLevels->delete($schoolLevel)) {
            $this->Flash->success(__('The school level has been deleted.'));
        } else {
            $this->Flash->error(__('The school level could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
