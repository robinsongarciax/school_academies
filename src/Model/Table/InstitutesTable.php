<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Institutes Model
 *
 * @property \App\Model\Table\TermsTable&\Cake\ORM\Association\HasMany $Terms
 * @property \App\Model\Table\SchoolLevelsTable&\Cake\ORM\Association\BelongsToMany $SchoolLevels
 *
 * @method \App\Model\Entity\Institute newEmptyEntity()
 * @method \App\Model\Entity\Institute newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Institute[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Institute get($primaryKey, $options = [])
 * @method \App\Model\Entity\Institute findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Institute patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Institute[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Institute|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Institute saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Institute[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Institute[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Institute[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Institute[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class InstitutesTable extends Table
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

        $this->setTable('institutes');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Terms', [
            'foreignKey' => 'institute_id',
        ]);
        $this->belongsToMany('SchoolLevels', [
            'foreignKey' => 'institute_id',
            'targetForeignKey' => 'school_level_id',
            'joinTable' => 'institutes_school_levels',
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
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        return $validator;
    }
}
