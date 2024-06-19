<?php

declare(strict_types=1);

namespace User\Form\Setting;

use Laminas\Form\Element;
use Laminas\Form\Form;

class EmailForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('update_email');
		$this->setAttribute('method', 'post');

		$this->add([
			'type' => Element\Email::class,
			'name' => 'current_email',
			'options' => [
				'label' => 'Текущий Email'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 128,
				'pattern' => '^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$',
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'title' => 'Текущий Email',
				'readonly' => true,
			]
		]);

		$this->add([
			'type' => Element\Email::class,
			'name' => 'new_email',
			'options' => [
				'label' => 'Новый Email'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 128,
				'pattern' => '^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$',
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'placeholder' => 'Введите новый Email',
				'title' => 'Укажите действующий и рабочий Email'
			]
		]);

		$this->add([
			'type' => Element\Email::class,
			'name' => 'confirm_new_email',
			'options' => [
				'label' => 'Подтвердите новый Email'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 128,
				'pattern' => '^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$',
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'class' => 'form-control',
				'placeholder' => 'Введите новый Email еще раз',
				'title' => 'Email должен совпадать с указанным выше'
			]
		]);

		$this->add([
			'type' => Element\Csrf::class,
			'name' => 'csrf',
			'options' => [
				'csrf_options' => [
					'timeout' => 600
				]
			]
		]);

		$this->add([
			'type' => Element\Submit::class,
			'name' => 'change_email',
			'attributes' => [
				'class' => 'btn btn-primary',
				'value' => 'Сохранить изменения'
			]
		]);
	}
}
