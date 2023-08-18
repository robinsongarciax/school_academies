<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Subject $subject
 */
?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h3 class="page-header-title">Configuraci&oacute;n de <?= __('Subjects') ?></h3>
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    <?= $this->Html->link(__('Edit Subject'), ['action' => 'edit', $subject->id], ['class' => 'btn btn-sm btn-light text-primary']) ?>
                    <?= $this->Form->postLink(
                        __('Delete'),
                        ['action' => 'delete', $subject->id],
                        ['confirm' => __('Are you sure you want to delete {0}?', $subject->name), 'class' => 'btn btn-sm btn-light text-primary']
                    ) ?>
                    <?= $this->Html->link(__('List Subjects'), ['action' => 'index'], ['class' => 'btn btn-sm btn-light text-primary']) ?>
                    <?= $this->Html->link(__('New Subject'), ['action' => 'add'], ['class' => 'btn btn-sm btn-light text-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= h($subject->name) ?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th><?= __('Name') ?></th>
                        <td><?= h($subject->name) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Description') ?></th>
                        <td><?= h($subject->description) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Sex') ?></th>
                        <?php
                        switch ($subject->sex) {
                            case 'M':
                                $sex = 'MASCULINO';
                                break;
                            case 'F':
                                $sex = 'FEMENINO';
                                break;
                            default:
                                $sex = 'MIXTO';
                                break;
                        }
                        ?>
                        <td><?= h($sex) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Tipo Academia') ?></th>
                        <td><?= h($subject->tipo_academia) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Pago Obligatorio') ?></th>
                        <td><?= $subject->pago_obligatorio == 1 ? 'Sí' : 'No' ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Visible para los alumnos') ?></th>
                        <td><?= $subject->is_visible == 1 ? 'Sí' : 'No' ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Created') ?></th>
                        <td><?= h($subject->created) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Modified') ?></th>
                        <td><?= h($subject->modified) ?></td>
                    </tr>
                </table>
            
            </div>
        </div>
    </div>
</div>
