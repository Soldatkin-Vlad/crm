<?php

namespace History\Controller;

use Application\Model\Table\CrmTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use History\Form\History\CreateForm;
use History\Form\History\DeleteForm;
use History\Model\Table\InteractionsTable;
use Laminas\Authentication\AuthenticationService;

class HistoryController extends AbstractActionController
{

    private $createForm;

    private $interactionsTable;

    private $crmTable;

    public function __construct(InteractionsTable $interactionsTable, CrmTable $crmTable)
    {
        $this->interactionsTable = $interactionsTable;
        $this->crmTable = $crmTable;
    }

    public function indexAction()
    {
        # logged in user allowed here.
        $auth = new AuthenticationService();
        if(!$auth->hasIdentity()) {
            return $this->redirect()->toRoute('login');
        }
        $request = $this->getRequest();
        $searchData = $request->getQuery()->toArray();
        $s='';
        if(isset($searchData['s'])){
            $s=$searchData['s'];
        }

        $client_id = null;
        if(isset($searchData['client_id'])){
            $client_id = $searchData['client_id'];
        }
        if(!$client_id){
            return $this->redirect()->toRoute('crm');
        }

        return new ViewModel([
            'interactions' => $this->interactionsTable->fetchAllInteractionsByClientId($client_id),
            'client' => $this->crmTable->fetchClientById($client_id)
        ]);
    }



    public function addAction()
    {
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
}