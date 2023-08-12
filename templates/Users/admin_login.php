<?= $this->Flash->render() ?>
<?= $this->Form->create(null, [
    'url' => ['action' => 'login'],
    'class'=>'user'
]) ?>
    <legend><?= __('Iniciar sesión') ?></legend>
    <div class="form-group">
        <?= $this->Form->control('username', [
            'required' => true,
            'class' => 'form-control form-control-user text-uppercase',
            'label' => [
                'text' => 'Usuario'
            ],
            'error' => ['required' => __('Este campo es requerido', true)]
        ]) ?>
    </div>
    <div class="form-group">
        <?= $this->Form->control('password', ['required' => true, 
            'class' => 'form-control form-control-user',
            'label' => [
                'text' => 'Contraseña'
            ]
        ]) ?>

    </div>
    <?= $this->Form->submit(__('Login'), ['class' => 'btn btn-primary btn-user btn-block']); ?>
<?= $this->Form->end() ?>

<div class="text-center">
    <?= $this->Html->link(__('Student Login'), ['action' => 'login'], ['class' => 'small']) ?>
</div>