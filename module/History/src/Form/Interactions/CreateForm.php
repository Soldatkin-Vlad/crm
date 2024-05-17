<?php

declare(strict_types=1);

namespace History\Form\Interactions;

use Laminas\Form\Element;
use Laminas\Form\Form;
use Laminas\Http\PhpEnvironment\Request;

class CreateForm extends Form
{
    public function __construct($clientId, $performedBy)
    {
        parent::__construct('new_interaction');
        $this->setAttribute('method', 'post');

        // Automatically set the client_id from the URL parameter
        $this->add([
            'type' => Element\Hidden::class,
            'name' => 'client_id',
            'attributes' => [
                'value' => $clientId
            ]
        ]);

        $this->add([
            'type' => Element\DateSelect::class,
            'name' => 'interaction_date',
            'options' => [
                'label' => 'Дата события',
            ],
            'attributes' => [
                'required' => true,
                'class' => 'custom-select d-block w-100'
            ]
        ]);

        $this->add([
            'type' => Element\Select::class,
            'name' => 'type_name',
            'options' => [
                'label' => 'Тип взаимодействия',
                'empty_option' => 'Выберите тип...',
                'value_options' => [
                    'Звонок' => 'Звонок',
                    'Встреча' => 'Встреча',
                    'Email' => 'Email',
                    'Социальные сети' => 'Социальные сети',
                    'Другое' => 'Другое'
                ]
            ],
            'attributes' => [
                'required' => true,
                'class' => 'custom-select d-block w-100'
            ]
        ]);

        $this->add([
            'type' => Element\Textarea::class,
            'name' => 'notes',
            'options' => [
                'label' => 'Заметки'
            ],
            'attributes' => [
                'required' => true,
                'cols' => 90,
                'maxlength' => 1024,
                'class' => 'form-control',
                'placeholder' => 'Напишите заметки',
                'data-toggle' => 'tooltip',
                'title' => 'Напишите заметки'
            ]
        ]);

        // Automatically set the performed_by based on the current user
        $this->add([
            'type' => Element\Hidden::class,
            'name' => 'performed_by',
            'attributes' => [
                'value' => $performedBy
            ]
        ]);

        $this->add([
            'type' => Element\Submit::class,
            'name' => 'create_interaction',
            'attributes' => [
                'value' => 'Добавить событие',
                'class' => 'btn btn-primary'
            ]
        ]);
    }
}