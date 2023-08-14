<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Institute $institute
 */

$this->Html->script('add-edit-modal', ['block' => true]);
?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h3 class="page-header-title"><?= __('Institutes Configuration') ?></h3>
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    <?= $this->Html->link(__('Edit Institute'), ['action' => 'edit', $institute->id], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid">
    <div class="row">
        <?= $this->Flash->render() ?>
        <!-- Segmento izquiero -->
        <div class="col-lg-6 d-none d-lg-block">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary-cm"><?= h($institute->name) ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th><?= __('Name') ?></th>
                                <td><?= h($institute->name) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Principal') ?></th>
                                <td><?= h($institute->principal) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Segmento derecho -->
        <div class="col-lg-6 d-none d-lg-block">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Terms') ?></h6>
                </div>
                <div class="card-body">
                    <?= $this->Html->link(__('Add Term'), [
                        'controller' => 'Terms',
                        'action' => 'add'
                    ], [
                        'class' => 'btn btn-sm btn-outline-primary btn-modal',
                        'modal-title' => 'Agregar Ciclo Escolar',
                        'data-toggle' => 'modal',
                        'data-target' => '#addEditModal'
                    ]) ?>
                    <hr class="mt-0 mb-4">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th><?= __('Description') ?></th>
                                <th><?= __('Start') ?></th>
                                <th><?= __('End') ?></th>
                                <th class="actions"><?= __('Actions') ?></th>
                            </tr>
                            <?php foreach ($institute->terms as $terms) : ?>
                                <tr>
                                    <td><?= h($terms->description) ?></td>
                                    <td><?= h($terms->start) ?></td>
                                    <td><?= h($terms->end) ?></td>
                                    <td class="actions">
                                        <?= $this->Html->link(__('Edit'), [
                                            'controller' => 'Terms', 'action' => 'edit', $terms->id
                                        ],
                                        [
                                            'class' => 'btn-modal',
                                            'modal-title' => 'Editar Ciclo Escolar',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#addEditModal'
                                        ]) ?>
                                        <?= $this->Form->postLink(__('Delete'), ['controller' => 'Terms', 'action' => 'delete', $terms->id], ['confirm' => __('Are you sure you want to delete # {0}?', $terms->id)]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<?= $this->element('modal/add_edit') ?>