<?= $this->Form->create($schedule) ?>
<fieldset>
    <?php
        echo $this->Form->control('days', [
            'templates' => 'multichecked_form',
            'options' => $days,
            'multiple' => 'checkbox',
            'label' => ['text' => 'Días de la semana']
        ]);
        echo $this->Form->control('start');
        echo $this->Form->control('end');
        echo $this->Form->control('school_course_id', [
            'options' => $schoolCourses,
            'hidden' => true,
            'label' => false
        ]);
    ?>
</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>