<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Module $module
 */
?>
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h2 class="page-header-title">Configuraci&oacute;n de <?= __('Modules') ?></h2>
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    <?= $this->Html->link(__('Edit Module'), ['action' => 'edit', $module->id], ['class' => 'btn btn-sm btn-light text-primary']) ?>
                    <?= $this->Form->postLink(__('Delete Module'), ['action' => 'delete', $module->id], ['confirm' => __('Are you sure you want to delete # {0}?', $module->id), 'class' => 'btn btn-sm btn-light text-primary']) ?>
                    <?= $this->Html->link(__('List Modules'), ['action' => 'index'], ['class' => 'btn btn-sm btn-light text-primary']) ?>
                    <?= $this->Html->link(__('New Module'), ['action' => 'add'], ['class' => 'btn btn-sm btn-light text-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h3 class="h6 m-0 font-weight-bold text-primary-cm"><?= h($module->name) ?></h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-7 d-none d-lg-block">
<div class="row">
    
    <div class="column-responsive column-80">
        <div class="modules view content">
            <h3><?= h($module->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($module->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($module->id) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Permissions') ?></h4>
                <?php if (!empty($module->permissions)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($module->permissions as $permissions) : ?>
                        <tr>
                            <td><?= h($permissions->id) ?></td>
                            <td><?= h($permissions->name) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Permissions', 'action' => 'view', $permissions->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Permissions', 'action' => 'edit', $permissions->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Permissions', 'action' => 'delete', $permissions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $permissions->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
