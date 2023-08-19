<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SchoolLevels Model
 *
 * @property \App\Model\Table\InstitutesTable&\Cake\ORM\Association\BelongsToMany $Institutes
 *
 * @method \App\Model\Entity\SchoolLevel newEmptyEntity()
 * @method \App\Model\Entity\SchoolLevel newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\SchoolLevel[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SchoolLevel get($primaryKey, $options = [])
 * @method \App\Model\Entity\SchoolLevel findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\SchoolLevel patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SchoolLevel[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SchoolLevel|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SchoolLevel saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SchoolLevel[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SchoolLevel[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\SchoolLevel[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SchoolLevel[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SchoolLevelsTable extends Table
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

        $this->setTable('school_levels');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Institutes', [
            'foreignKey' => 'school_level_id',
            'targetForeignKey' => 'institute_id',
            'joinTable' => 'institutes_school_levels',
        ]);

        $this->belongsToMany('SchoolCourses', [
            'joinTable' => 'school_courses_school_levels',
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
            ->maxLength('name', 45)
            ->allowEmptyString('name');

        return $validator;
    }
}
