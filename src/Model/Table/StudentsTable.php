<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Students Model
 *
 * @property \App\Model\Table\TermsTable&\Cake\ORM\Association\BelongsTo $Terms
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\CoursesTable&\Cake\ORM\Association\HasMany $Courses
 * @property \App\Model\Table\SchoolCoursesTable&\Cake\ORM\Association\BelongsToMany $SchoolCourses
 *
 * @method \App\Model\Entity\Student newEmptyEntity()
 * @method \App\Model\Entity\Student newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Student[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Student get($primaryKey, $options = [])
 * @method \App\Model\Entity\Student findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Student patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Student[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Student|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Student saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Student[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Student[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Student[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Student[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StudentsTable extends Table
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

        $this->setTable('students');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Terms', [
            'foreignKey' => 'term_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Courses', [
            'foreignKey' => 'student_id',
        ]);
        $this->belongsToMany('SchoolCourses', [
            'foreignKey' => 'student_id',
            'targetForeignKey' => 'school_course_id',
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
            ->maxLength('name', 150)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('curp')
            ->maxLength('curp', 18)
            ->requirePresence('curp', 'create')
            ->notEmptyString('curp')
            ->add('curp', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('level')
            ->requirePresence('level', 'create')
            ->notEmptyString('level');

        $validator
            ->scalar('institute')
            ->requirePresence('institute', 'create')
            ->notEmptyString('institute');

        $validator
            ->scalar('school_group')
            ->maxLength('school_group', 10)
            ->requirePresence('school_group', 'create')
            ->notEmptyString('school_group');

        $validator
            ->scalar('id_number')
            ->maxLength('id_number', 45)
            ->allowEmptyString('id_number');

        // $validator
        //     ->date('birth_date')
        //     ->requirePresence('birth_date', 'create')
        //     ->notEmptyDate('birth_date');

        $validator
            ->integer('term_id')
            ->notEmptyString('term_id');

        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

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
        $rules->add($rules->isUnique(['curp']), ['errorField' => 'curp']);
        $rules->add($rules->existsIn('term_id', 'Terms'), ['errorField' => 'term_id']);
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }

    public function findStudentInfo(Query $query, array $options) {

        $query = $query
            ->select(['sl_id' => 'sl.id', 'sex', 'student_id' => 'Students.id'])
            ->join([
                'table' => 'school_levels',
                'alias' => 'sl',
                'type' => 'INNER',
                'conditions' => 'sl.name = school_level'
            ])
            ->where(
                ['user_id' => $options['user_id']]
            );
        return $query;
    }
}
