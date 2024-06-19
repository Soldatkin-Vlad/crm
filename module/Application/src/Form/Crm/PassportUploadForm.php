<?php


namespace Application\Form\Crm;
use Laminas\Form\Element;
use Laminas\Form\Form;

class PassportUploadForm extends Form
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->addElements();
    }

    public function addElements()
    {
        // File Input
        $file = new Element\File('image-file');
        $file->setLabel('Выберите фото паспорта для поиска');
        $file->setAttribute('id', 'image-file');

        $this->add($file);
    }
}