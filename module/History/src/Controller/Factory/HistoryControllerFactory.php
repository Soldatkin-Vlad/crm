<?php

declare(strict_types=1);

namespace History\Controller\Factory;

use Application\Model\Table\CrmTable;
use History\Controller\HistoryController;
use History\Model\Table\InteractionsTable;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class HistoryControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		$formManager = $container->get('FormElementManager');
		return new HistoryController(
			$container->get(InteractionsTable::class),
            $container->get(CrmTable::class)
		);
	}
}
