<?php

declare(strict_types=1);

namespace History\Form\Interactions;

use Laminas\Form\Element;
use Laminas\Form\Form;

class DeleteForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('remove_client');
        $this->setAttribute('method', 'post');

        $this->add([
            'type' => Element\Hidden::class,
            'name' => 'user_id'
        ]);

        $this->add([
            'type' => Element\Hidden::class,
            'name' => 'client_id'
        ]);

        $this->add([
            'type' => Element\Submit::class,
            'name' => 'delete_client',
            'attributes' => [
                'class' => 'btn btn-primary'
            ]
        ]);
    }
}
