<?php

declare(strict_types=1);

namespace Application\Controller;

use Application\Form\Crm\CreateForm;
use Application\Form\Crm\DeleteForm;
use Application\Form\Crm\PassportUploadForm;
use Application\Model\Table\AnswersTable;
use Application\Model\Table\CrmTable;
use Application\Model\Table\TalliesTable;
use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CrmController extends AbstractActionController
{
	private $answersTable;
	private $createForm;
	private $crmTable;
	private $talliesTable;
    private $passportUploadForm;

	public function __construct(
		AnswersTable $answersTable,
		CreateForm $createForm,
		CrmTable $crmTable,
		TalliesTable $talliesTable,
        PassportUploadForm $passportUploadForm
	) 
	{
		$this->answersTable = $answersTable;
		$this->createForm   = $createForm;
		$this->crmTable = $crmTable;
		$this->talliesTable = $talliesTable;
		$this->passportUploadForm = $passportUploadForm;

    }

	public function createAction()
	{
		# logged in users only please!
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()) {
			return $this->redirect()->toRoute('login');
		}

		$addForm = $this->createForm;
		$request = $this->getRequest();

		if($request->isPost()) {
		    print_r('post');
			$formData = $request->getPost()->toArray();
            $formData['status'] = 1;
			$addForm->setInputFilter($this->crmTable->getCreateFormFilter());
			$addForm->setData($formData);

			if($addForm->isValid()) {

				try {

					$data = $addForm->getData();
					$info = $this->crmTable->saveClient($data);

					$this->flashMessenger()->addSuccessMessage('Клиент успешно добавлен.');
					return $this->redirect()->toRoute('crm', ['action' => 'index']);

				} catch(\RuntimeException $exception) {
					$this->flashMessenger()->addErrorMessage($exception->getMessage());
					return $this->redirect()->refresh();
				}
			}
		}

		return new ViewModel(['form' => $addForm]);
	}

	public function deleteAction()
	{
		# allow only logged in users
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()) {
			return $this->redirect()->toRoute('login');
		}

		$id = (int) $this->params()->fromRoute('id');
		if(!is_numeric($id)) {
			return $this->notFoundAction();
		}
 
		$info = $this->crmTable->fetchClientById((int)$id);
		if(!$info) {
			return $this->notFoundAction();
		}

		$deleteForm = new DeleteForm();
		$request = $this->getRequest();

		if($request->isPost()) {
			$formData = $request->getPost()->toArray();
			$deleteForm->setData($formData);

			if($deleteForm->isValid()) {
				if($request->getPost()->get('delete_client') == 'Да') {
					# now check that the person deleting the quiz is the author of the quiz
					if($this->authPlugin()->getRoleId() == 1)
					{

						$this->crmTable->deleteCrmById((int)$info->getId());
						$this->flashMessenger()->addInfoMessage('Клиент успешно удален!');

						return $this->redirect()->toRoute('crm', ['action' => 'index']);
					}

					# redirect this person away from this page with a warning
					$this->flashMessenger()->addWarningMessage('Клиентов может удалить только администратор');
					return $this->redirect()->toRoute('home');
				}

				# here as well. The person presumably has clicked the No button
				return $this->redirect()->toRoute('crm', ['action' => 'index']);
			}
		}

		return new ViewModel([
			'form' => $deleteForm,
			'quiz' => $info
		]);
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

        $passportUploadForm = new PassportUploadForm();
        if ($request->isPost()) {
            // Make certain to merge the $_FILES info!
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $passportUploadForm->setData($post);
            if ($passportUploadForm->isValid()) {
                $data = $passportUploadForm->getData();

                // var_dump($data);
                // array(1) { ["image-file"]=> array(5) {
                // ["name"]=> string(11) "faceitA.jpg"
                // ["type"]=> string(10) "image/jpeg"
                // ["tmp_name"]=> string(24) "C:\xampp\tmp\php910C.tmp"
                // ["error"]=> int(0)
                // ["size"]=> int(10935) } }

                // Form is valid, save the form!
                //return $this->redirect()->toRoute('upload-form/success');
            }
        }

		return new ViewModel([
			'clients' => $this->crmTable->fetchAllClients($s),
            'formPassport' => $passportUploadForm
		]);
	}

	public function viewAction()
	{
		$id = (int)$this->params()->fromRoute('id');
		$info = $this->quizzesTable->fetchQuizById((int) $id);

		if(!is_numeric($id) || !$info) {
			return $this->notFoundAction();
		}

		$auth = new AuthenticationService();
		if(!$auth->hasIdentity() || $this->authPlugin()->getUserId() != $info->getUserId()) {
			$this->quizzesTable->updateViews((int) $info->getQuizId());
		}

		return new ViewModel([
			'quiz' => $info,
			'answerTable' => $this->answersTable,
			'currentPage' => $this->getRequest()->getRequestUri()
		]);
	}
}

