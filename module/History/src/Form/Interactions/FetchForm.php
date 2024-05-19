<?php

declare(strict_types=1);

namespace History\Form\Interactions;

use Laminas\Form\Element;
use Laminas\Form\Form;

class FetchForm extends Form
{
    public function __construct($client_id = null)
    {
        parent::__construct('filter_interaction');
        $this->setAttribute('method', 'get'); // Change to 'get' for search

        $this->add([
            'type' => Element\Hidden::class,
            'name' => 'client_id',
            'attributes' => [
                'value' => $client_id
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
                'required' => false, // Make this optional for searching
                'class' => 'custom-select d-block w-100'
            ]
        ]);

        $this->add([
            'type' => Element\Submit::class,
            'name' => 'filter_interaction',
            'attributes' => [
                'value' => 'Фильтр',
                'class' => 'btn btn-primary'
            ]
        ]);
    }
}