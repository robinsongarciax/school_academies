<?= $this->Form->create($term) ?>
<fieldset>
    <?php
    echo $this->Form->control('description');
    echo $this->Form->control('start');
    echo $this->Form->control('end');
    echo $this->Form->control('courses_allowed');
    echo $this->Form->control('institute_id', ['options' => $institutes]);
    ?>
</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>