<?php

namespace History\Controller;

use Application\Model\Table\CrmTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use History\Form\Interactions\CreateForm;
use History\Form\Interactions\DeleteForm;
use History\Form\Interactions\FetchForm;
use History\Model\Table\InteractionsTable;
use Laminas\Authentication\AuthenticationService;
use Laminas\Http\PhpEnvironment\Request;

class HistoryController extends AbstractActionController
{
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

        $client_id = $searchData['client_id'] ?? null;
        if(!$client_id){
            return $this->redirect()->toRoute('crm');
        }

        $type_name = $searchData['type_name'] ?? null;

        $form = new FetchForm($client_id);

        // Populate the form with data from the query if available
        if (!empty($type_name)) {
            $form->setData(['type_name' => $type_name]);
        }

        $interactions = $this->interactionsTable->fetchAllInteractionsByClientId($client_id, $type_name);

        return new ViewModel([
            'form' => $form,
            'interactions' => $interactions,
            'client' => $this->crmTable->fetchClientById($client_id),
            'client_id' => $client_id
        ]);
    }



    public function addAction()
    {
    }

    public function createAction()
    {
        # logged in users only please!
        $auth = new AuthenticationService();
        if(!$auth->hasIdentity()) {
            return $this->redirect()->toRoute('login');
        }

        $client_id = (int) $this->params()->fromRoute('id');
        if(!is_numeric($client_id)) {
            return $this->notFoundAction();
        }
        $performedId = $auth->getIdentity()->user_id;
        $addForm = new CreateForm($client_id, $performedId);
        $request = $this->getRequest();

        if($request->isPost()) {
            $formData = $request->getPost()->toArray();
            $formData['client_id'] = $client_id;
            $formData['performed_by'] = $performedId;
            $addForm->setInputFilter($this->interactionsTable->getCreateFormFilter());
            $addForm->setData($formData);

            $addForm->isValid();
            print_r($addForm->getInputFilter()->getMessages());
            if($addForm->isValid()) {

                try {

                    $data = $addForm->getData();
                    $data['status'] = 1;
                    $info = $this->interactionsTable->saveInteraction($data);

                    $this->flashMessenger()->addSuccessMessage('Событие успешно добавлен.');
                    return $this->redirect()->toRoute('history', ['action' => 'index'], ['query' => ['client_id' => $client_id]]);

                } catch(\RuntimeException $exception) {
                    $this->flashMessenger()->addErrorMessage($exception->getMessage());
                    return $this->redirect()->refresh();
                }
            }
        }

        return new ViewModel(['form' => $addForm]);
    }

//    public function deleteAction()
//    {
//        # allow only logged in users
//        $auth = new AuthenticationService();
//        if(!$auth->hasIdentity()) {
//            return $this->redirect()->toRoute('login');
//        }
//
//        $client_id = (int) $this->params()->fromRoute('id');
//        if(!is_numeric($client_id)) {
//            return $this->notFoundAction();
//        }
//
//        $id = (int) $this->params()->fromRoute('id');
//        if(!is_numeric($id)) {
//            return $this->notFoundAction();
//        }
//
//        $info = $this->interactionsTable->fetchInteractionById((int)$id);
//        if(!$info) {
//            return $this->notFoundAction();
//        }
//
//        $deleteForm = new DeleteForm();
//        $request = $this->getRequest();
//
//        if($request->isPost()) {
//            $formData = $request->getPost()->toArray();
//            $deleteForm->setData($formData);
//
//            if($deleteForm->isValid()) {
//                if($request->getPost()->get('delete_interaction') == 'Да') {
//                    # now check that the person deleting the quiz is the author of the quiz
//                    if($this->authPlugin()->getRoleId() == 1)
//                    {
//
//                        $this->interactionsTable->deleteInteractionById((int)$info->getId());
//                        $this->flashMessenger()->addInfoMessage('Событие успешно удален!');
//
//                        return $this->redirect()->toRoute('history', ['action' => 'index'], ['query' => ['client_id' => $client_id]]);
//                    }
//
//                    # redirect this person away from this page with a warning
//                    $this->flashMessenger()->addWarningMessage('События может удалить только администратор');
//                    return $this->redirect()->toRoute('home');
//                }
//
//                # here as well. The person presumably has clicked the No button
//                return $this->redirect()->toRoute('history', ['action' => 'index'], ['query' => ['client_id' => $client_id]]);
//            }
//        }
//
//        return new ViewModel([
//            'form' => $deleteForm,
//            'quiz' => $info
//        ]);
//    }
    public function deleteAction()
    {
        $auth = new AuthenticationService();
        if (!$auth->hasIdentity()) {
            return $this->redirect()->toRoute('login');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = (int) $request->getPost('id');
            if ($id) {
                try {
                    $this->interactionsTable->deleteInteraction($id);
                    $this->flashMessenger()->addSuccessMessage('Событие успешно удалено');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage('Ошибка при удалении события');
                }
            }
        }

        return $this->redirect()->toRoute('history');
    }
}