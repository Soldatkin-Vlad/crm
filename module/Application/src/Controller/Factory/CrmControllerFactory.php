<?php

declare(strict_types=1);

namespace Application\Controller\Factory;

use Application\Form\Crm\CreateForm;
use Application\Controller\CrmController;
use Application\Form\Crm\PassportUploadForm;
use Application\Model\Table\AnswersTable;
use Application\Model\Table\CrmTable;
use Application\Model\Table\TalliesTable;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class CrmControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		$formManager = $container->get('FormElementManager');
		return new CrmController(
			$container->get(AnswersTable::class),
			$formManager->get(CreateForm::class),
			$container->get(CrmTable::class),
			$container->get(TalliesTable::class),
            $formManager->get(PassportUploadForm::class)
		);
	}
}
