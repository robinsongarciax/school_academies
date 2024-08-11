<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Terms Controller
 *
 * @property \App\Model\Table\TermsTable $Terms
 * @method \App\Model\Entity\Term[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TermsController extends AppController
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
            'contain' => ['Institutes'],
        ];
        $terms = $this->paginate($this->Terms);
        $this->set(compact('terms'));
    }

    /**
     * View method
     *
     * @param string|null $id Term id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $term = $this->Terms->get($id, [
            'contain' => ['Institutes', 'Classes', 'Students'],
        ]);
        $this->Authorization->authorize($term);
        $this->set(compact('term'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $term = $this->Terms->newEmptyEntity();
        $this->Authorization->authorize($term);
        if ($this->request->is('post')) {
            $term = $this->Terms->patchEntity($term, $this->request->getData());
            if ($this->Terms->save($term)) {
                $this->Flash->success(__('The term has been saved.'));

                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('The term could not be saved. Please, try again.'));
        }
        $institutes = $this->Terms->Institutes->find('list', ['limit' => 200])->all();
        $this->set(compact('term', 'institutes'));
        $this->render('/element/term/add_edit');
    }

    /**
     * Edit method
     *
     * @param string|null $id Term id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $term = $this->Terms->get($id, [
            'contain' => [],
        ]);
        $this->Authorization->authorize($term);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $term = $this->Terms->patchEntity($term, $this->request->getData());
            if ($this->Terms->save($term)) {
                $this->Flash->success(__('The term has been saved.'));

                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('The term could not be saved. Please, try again.'));
        }
        $institutes = $this->Terms->Institutes->find('list', ['limit' => 200])->all();
        $this->set(compact('term', 'institutes'));
        $this->render('/element/term/add_edit');
    }

    /**
     * Delete method
     *
     * @param string|null $id Term id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $term = $this->Terms->get($id);
        $this->Authorization->authorize($term);
        if ($this->Terms->delete($term)) {
            $this->Flash->success(__('The term has been deleted.'));
        } else {
            $this->Flash->error(__('The term could not be deleted. Please, try again.'));
        }

        return $this->redirect($this->referer());
    }
}
