<?php

declare(strict_types=1);

namespace Application\Form\Crm;

use Application\Model\Table\CategoriesTable;
use Laminas\Form\Element;
use Laminas\Form\Form;

class CreateForm extends Form
{
	public function __construct(CategoriesTable $categoriesTable)
	{
		parent::__construct('new_client');
		$this->setAttribute('method', 'post');

		$this->add([
			'type' => Element\Text::class,
			'name' => 'username',
			'options' => [
				'label' => 'ФИО'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 255,
				'class' => 'form-control',
				'placeholder' => 'Введите ФИО',
				'data-toggle' => 'tooltip',
				'title' => 'Введите ФИО'
			]
		]);

		$this->add([
			'type' => Element\Text::class,
			'name' => 'pasport',
			'options' => [
				'label' => 'Серия и Номер паспорта'
			],
            'attributes' => [
                'required' => true,
                'size' => 40,
                'maxlength' => 255,
                'class' => 'form-control',
                'placeholder' => 'Введите Серию и Номер паспорта',
                'data-toggle' => 'tooltip',
                'title' => 'Введите Серию и Номер паспорта'
			]
		]);

		//todo
		$this->add([
			'type' => Element\DateSelect::class,
			'name' => 'birthday',
			'options' => [
				'label' => 'Дата рождения',
				'empty_option' => 'Укажите дату рождеения',
				'value_options' => [
					'1 day'  => '1 Day',
					'3 days' => '3 Days',
					'7 days' => '7 Days',
				]
			],
			'attributes' => [
				'required' => true,
				'class' => 'custom-select d-block w-100'
			]
		]);

        $this->add([
            'type' => Element\Text::class,
            'name' => 'nomber_d',
            'options' => [
                'label' => 'Номер договора'
            ],
            'attributes' => [
                'required' => true,
                'size' => 40,
                'maxlength' => 255,
                'class' => 'form-control',
                'placeholder' => 'Введите Номер Договора',
                'data-toggle' => 'tooltip',
                'title' => 'Введите Номер Договора'
            ]
        ]);

		//todo
        $this->add([
			'type' => Element\DateSelect::class,
			'name' => 'data_d',
			'options' => [
				'label' => 'Дата договора'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 100,
				'class' => 'form-control',
				'placeholder' => 'Укажите дату договора',
				'data-toggle' => 'tooltip',
				'title' => 'Provide a possible answer'
			]
		]);

        $this->add([
            'type' => Element\Select::class,
            'name' => 'trafic',
            'options' => [
                'label' => 'Тариф',
                'empty_option' => 'Выберите тариф...',
                'value_options' => [
                    'Базовый'  => 'Базовый',
                    'Семейный' => 'Семейный',
                ]
            ],
            'attributes' => [
                'required' => true,
                'class' => 'custom-select d-block w-100'
            ]
        ]);

        $this->add([
            'type' => Element\Textarea::class,
            'name' => 'coments',
            'options' => [
                'label' => 'Комментарий'
            ],
            'attributes' => [
                'required' => true,
                'cols' => 90,
                'maxlength' => 1024,
                'class' => 'form-control',
                'placeholder' => 'Напишите комментарий',
                'data-toggle' => 'tooltip',
                'title' => 'Напишите комментарий'
            ]
        ]);

        $this->add([
            'type' => Element\Select::class,
            'name' => 'status',
            'options' => [
                'label' => 'Статус',
                'empty_option' => 'Выберите статус...',
                'value_options' => [
                    'Акктивный'  => 'Акктивный',
                    'Неактивный' => 'Неактивный',
                ]
            ],
            'attributes' => [
                'required' => true,
                'class' => 'custom-select d-block w-100'
            ]
        ]);

        $this->add([
            'type' => Element\Submit::class,
            'name' => 'create_client',
            'attributes' => [
                'value' => 'Добавить клиента',
                'class' => 'btn btn-primary'
            ]
        ]);
	}
}
