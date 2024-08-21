<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Teacher $teacher
 * @var \Cake\Collection\CollectionInterface|string[] $users
 */
?>
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h3 class="page-header-title">Configuraci&oacute;n de <?= __('Teachers') ?></h3>
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    <?= $this->Html->link(__('List Teachers'), ['action' => 'index'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Add Teacher') ?></h6>
        </div>
        <div class="card-body">
            <?= $this->Form->create($teacher) ?>
            <fieldset>
                <legend><?= __('Add Teacher') ?></legend>
                <?php
                    echo $this->Form->control('name', [
                    ]);
                    echo $this->Form->control('email');
                    echo $this->Form->control('celular');
                    echo $this->Form->control('active', ['value' => '1', 'hidden' => true, 'label' => false]);

                    echo $this->Form->control('tipo_academia', 
                                             ['label' => ['text' => 'Tipo de academia'],
                                              'options' => ['CULTURAL' => 'CULTURAL', 
                                                            'DEPORTIVA' => 'DEPORTIVA']
                                             ]);

                    echo $this->Form->control('users.name', ['label' => 'Nombre de usuario', 
                        'templates' => 'uppercase_form',
                        'class' => 'form-control text-uppercase',
                        'required' => true
                    ]);
                    echo $this->Form->control('users.password', [
                        'required' => true
                    ]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
