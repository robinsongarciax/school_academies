<?= $this->Html->script('student-login', ['block' => true]) ?>
<?= $this->Flash->render() ?>

<?php $allowed_access = true; ?>
<?php if ($allowed_access): ?>
    <?= $this->Form->create(null, ['url' => ['action' => 'login'], 'class'=>'user', 'id' => 'student-tmp-form']) ?>
        <legend><?= __('Iniciar sesión') ?></legend>
        <div class="form-group">
            <?= $this->Form->control('username', [
                'required' => true,
                'class' => 'form-control form-control-user text-uppercase',
                'id' => 'student-user',
                'label' => [
                    'text' => 'Ingrese su CURP'
                ],
                'error' => ['required' => __('Este campo es requerido', true)]
            ]) ?>
        </div>
        <div class="form-group">
            <?= $this->Form->control('password', ['required' => true, 
                'class' => 'form-control form-control-user',
                'id' => 'student-pass',
                'type' => 'hidden',
                'label' => [
                    'text' => 'Contraseña'
                ]
            ]) ?>

        </div>
        <?= $this->Form->submit(__('Login'), ['class' => 'btn btn-primary btn-user btn-block']); ?>
    <?= $this->Form->end() ?>
<?php else: ?>
    <legend><?= __('¡Información importante!') ?></legend>
    <p>Estimados padres de familia, se les informa que el acceso a la plataforma de inscripción a las academias estará disponible a partir del día <b>miércoles 17 de septiembre del 2025 a las 7:00 a.m.</b></p>
<?php endif; ?>
<div class="text-center">
    <?= $this->Html->link(__('Admin Login'), ['action' => 'login', 'admin'], ['class' => 'small']) ?>
</div>