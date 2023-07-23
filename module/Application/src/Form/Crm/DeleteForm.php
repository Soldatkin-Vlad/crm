<?php

declare(strict_types=1);

namespace Application\Form\Crm;

use Laminas\Form\Element;
use Laminas\Form\Form;

class DeleteForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('remove_quiz');
		$this->setAttribute('method', 'post');

		$this->add([
			'type' => Element\Hidden::class,
			'name' => 'id'
		]);

		$this->add([
			'type' => Element\Hidden::class,
			'name' => 'quiz_id'
		]);

		$this->add([
			'type' => Element\Submit::class,
			'name' => 'delete_quiz',
			'attributes' => [
				'class' => 'btn btn-primary'
			]
		]);
	}
}
