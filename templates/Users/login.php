<?= $this->Flash->render() ?>
<?= $this->Form->create(null, ['class'=>'user']) ?>
    <legend><?= __('Iniciar sesión') ?></legend>
    <div class="form-group">
        <?= $this->Form->control('username', [
            'required' => true,
            'class' => 'form-control form-control-user',
            'label' => [
                'text' => 'Nombre de usuario'
            ],
            'error' => ['required' => __('Este campo es requerido', true)]
        ]) ?>
    </div>
    <div class="form-group">
        <?= $this->Form->control('password', ['required' => true, 'class' => 'form-control form-control-user',
            'label' => [
                'text' => 'Contraseña'
            ]
        ]) ?>

    </div>
    <?= $this->Form->submit(__('Login'), ['class' => 'btn btn-primary btn-user btn-block']); ?>
<?= $this->Form->end() ?>

<div class="text-center">
    <?= $this->Html->link(__('¿Olvidó la contraseña?'), ['action' => 'forgotpassword'], ['class' => 'small']) ?>
</div>