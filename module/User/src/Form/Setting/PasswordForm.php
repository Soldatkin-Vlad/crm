<?php

declare(strict_types=1);

namespace User\Form\Setting;

use Laminas\Form\Element;
use Laminas\Form\Form;

class PasswordForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('update_password');
		$this->setAttribute('metho', 'post');


		# current password field
		$this->add([
			'type' => Element\Password::class,
			'name' => 'current_password',
			'options' => [
				'label' => 'Текущий пароль'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 25,
				'class' => 'form-control',
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'title' => 'Укажите текущий пароль вашей учетной записи',
				'placeholder' => 'Введите текущий пароль'
			]
		]);

		#new_password field
		$this->add([
			'type' => Element\Password::class,
			'name' => 'new_password',
			'options' => [
				'label' => 'Новый пароль'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 25,
				'class' => 'form-control',
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'title' => 'Пароль должен содержать не менее 8 символов',
				'placeholder' => 'Введите ваш новый пароль'
			]
		]);

		#confirm_new_password field
		$this->add([
			'type' => Element\Password::class,
			'name' => 'confirm_new_password',
			'options' => [
				'label' => 'Подтвердите новый пароль'
			],
			'attributes' => [
				'required' => true,
				'size' => 40,
				'maxlength' => 25,
				'class' => 'form-control',
				'autocomplete' => false,
				'data-toggle' => 'tooltip',
				'title' => 'Пароль должен совпадать с указанным выше',
				'placeholder' => 'Введите новый пароль еще раз'
			]
		]);

		$this->add([
			'type' => Element\Csrf::class,
			'name' => 'csrf',
			'options' => [
				'csrf_options' => [
					'timeout' => 600
				],
			],
		]);

		$this->add([
			'type' => Element\Submit::class,
			'name' => 'change_password',
			'attributes' => [
				'class' => 'btn btn-primary',
				'value' => 'Сохранить изменения'
			]
		]);
	}
}
