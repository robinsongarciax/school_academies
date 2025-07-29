<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \Cake\Collection\CollectionInterface|string[] $roles
 */
?>
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h3 class="page-header-title">Configuraci&oacute;n de <?= __('Users') ?></h3>
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Add User')?></h6>
        </div>
        <div class="card-body">
            <?= $this->Form->create($user) ?>
            <fieldset>
                <?php
                    echo $this->Form->control('username', [
                        'templates' => 'uppercase_form',
                        'class' => 'form-control text-uppercase'
                    ]);
                    echo $this->Form->control('password');
                    echo $this->Form->control('name');
                    echo $this->Form->control('active', ['type' => 'hidden']);
                    echo $this->Form->control('role_id', ['options' => $roles]);
                ?>
                <div id="collapseSchoolGrade" class="collapse" aria-labelledby="headingSchoolGrade">
                    <label class="form-label"><?= __('School Level') ?></label>
                    <div class="bg-white py-2 collapse-inner rounded" style="padding-left:.75rem;">
                        <?= $this->Form->control('school_levels._ids', [
                            'templates' => 'multichecked_form',
                            'options' => $schoolLevels,
                            'multiple' => 'checkbox',
                            'label' => false
                        ]) ?>
                    </div>
                </div>

            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>


<?php $this->Html->scriptStart(['block' => true]); ?>
$('#role-id').on('change', function() {
    let roleId = $(this).val();
    // roleId 7 - directora / 8 - titular de grupo
    if (roleId == 7 || roleId == 8) {
        $('#collapseSchoolGrade').addClass('show');
    } else {
        $('#collapseSchoolGrade').removeClass('show');
    }
});
<?php $this->Html->scriptEnd(); ?>