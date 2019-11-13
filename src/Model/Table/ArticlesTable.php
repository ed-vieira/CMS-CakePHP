<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;



class ArticlesTable extends Table
{


    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->belongsToMany('Tags');
    }




    public function beforeSave($event, $entity, $options)
    {
        if ($entity->isNew() && !$entity->slug) {
            $sluggedTitle = Text::slug($entity->title);
            // trim slug to maximum length defined in schema
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }



    public function validationDefault(Validator $validator)
    {
        $validator->allowEmptyString(false, 'title')
            ->minLength('title', 10)
            ->maxLength('title', 255)
            ->allowEmptyString(false, 'body')
            ->minLength('body', 10);

        return $validator;
    }



}
