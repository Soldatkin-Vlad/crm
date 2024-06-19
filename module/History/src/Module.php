<?php

namespace History;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use History\Model\Table\InteractionsTable;
use Laminas\Authentication\AuthenticationService;
use Laminas\Db\Adapter\Adapter;
use Laminas\Http\Response; # <- add this
use Laminas\Mvc\MvcEvent; # add this
use User\Service\AclService;
use User\Model\Table\RolesTable;
use User\Model\Table\PrivilegesTable;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event)
    {
        $app = $event->getApplication();
        $eventManager = $app->getEventManager();

        $eventManager->attach($event::EVENT_DISPATCH, [$this, 'getAccessPrivileges']);
    }

    public function getAccessPrivileges(MvcEvent $mvcEvent)
    {
        $services = $mvcEvent->getApplication()->getServiceManager();
        $viewAcl  = new AclService($services->get(PrivilegesTable::class));
        $viewAcl->grantAccess();

        $auth = new AuthenticationService();
        $rolesTable = $services->get(RolesTable::class);
        $guest = $rolesTable->fetchRole('guest'); # alternatively you can set a DEFAULT_ROLE = guest constant

        # here we are simply checking if the user is logged in or not. If not logged in, they are
        # of guest role. If they are logged iin we get their role_id from the session
        $roleId = !$auth->hasIdentity() ? (int) $guest->getRoleId() : (int) $auth->getIdentity()->role_id;
        $role = $rolesTable->fetchRoleById($roleId);

        $routeMatch = $mvcEvent->getRouteMatch();
        $resource = $routeMatch->getParam('controller') . DS . $routeMatch->getParam('action');

        $response = $mvcEvent->getResponse();
        if($viewAcl->isAuthorized($role->getRole(), $resource)) {
            if($response instanceof Response) {
                if($response->getStatusCode() != 200) {
                    $response->setStatusCode(200);
                }
            }

            return;
        }

        if(!$response instanceof Response) {
            return $response;
        }

        $response->setStatusCode(403);
        $response->setReasonPhrase('Forbidden');

        # custom handle the 403 error
        return $mvcEvent->getViewModel()->setTemplate('error/403');
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                InteractionsTable::class => function($sm) {
                    $dbAdapter = $sm->get(Adapter::class);
                    return new InteractionsTable($dbAdapter);
                },
            ]
        ];
    }
}