<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SchoolCourses Model
 *
 * @property \App\Model\Table\SubjectsTable&\Cake\ORM\Association\BelongsTo $Subjects
 * @property \App\Model\Table\TeachersTable&\Cake\ORM\Association\BelongsTo $Teachers
 * @property \App\Model\Table\TermsTable&\Cake\ORM\Association\BelongsTo $Terms
 * @property \App\Model\Table\SchedulesTable&\Cake\ORM\Association\BelongsToMany $Schedules
 * @property \App\Model\Table\StudentsTable&\Cake\ORM\Association\BelongsToMany $Students
 *
 * @method \App\Model\Entity\SchoolCourse newEmptyEntity()
 * @method \App\Model\Entity\SchoolCourse newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\SchoolCourse[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SchoolCourse get($primaryKey, $options = [])
 * @method \App\Model\Entity\SchoolCourse findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\SchoolCourse patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SchoolCourse[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SchoolCourse|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SchoolCourse saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SchoolCourse[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SchoolCourse[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\SchoolCourse[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SchoolCourse[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SchoolCoursesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('school_courses');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Subjects', [
            'foreignKey' => 'subjet_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Teachers', [
            'foreignKey' => 'teacher_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Terms', [
            'foreignKey' => 'term_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsToMany('Schedules', [
            'foreignKey' => 'school_course_id',
            'targetForeignKey' => 'schedule_id',
            'joinTable' => 'school_courses_schedules',
        ]);
        $this->belongsToMany('Students', [
            'foreignKey' => 'school_course_id',
            'targetForeignKey' => 'student_id',
            'joinTable' => 'school_courses_students',
        ]);

    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 250)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->integer('capacity')
            ->requirePresence('capacity', 'create')
            ->notEmptyString('capacity');

        $validator
            ->numeric('price')
            ->requirePresence('price', 'create')
            ->notEmptyString('costo_normal');

        $validator
            ->integer('subjet_id')
            ->notEmptyString('subjet_id');

        $validator
            ->integer('teacher_id')
            ->notEmptyString('teacher_id');

        $validator
            ->integer('term_id')
            ->notEmptyString('term_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('subjet_id', 'Subjects'), ['errorField' => 'subjet_id']);
        $rules->add($rules->existsIn('teacher_id', 'Teachers'), ['errorField' => 'teacher_id']);
        $rules->add($rules->existsIn('term_id', 'Terms'), ['errorField' => 'term_id']);

        return $rules;
    }

    public function findCoursesForStudent(Query $query, array $options) {
        $school_level_id = $options['school_level_id'];
        $sex = $options['sex'];
        $query = $query
            ->contain(['Subjects', 'Teachers', 'Terms'])
            ->innerJoinWith('Subjects.SchoolLevels', function ($q) use ($school_level_id) {
                return $q->where(['SchoolLevels.id' => $school_level_id]);
            })
            ->where(['Subjects.sex IN' => [$sex, 'X']]);
        return $query;
    }
}
