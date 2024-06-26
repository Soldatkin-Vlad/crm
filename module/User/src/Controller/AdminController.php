<?php

declare(strict_types=1);

/**
 * This app was built using PHP 7.4 it might not work so well in PHP 8.0+ 
 *  @author    https://twitter.com/pulenong
 */

namespace User\Controller;

use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Form\Setting\DeleteForm;
use User\Model\Table\UsersTable;

class AdminController extends AbstractActionController
{
	private $usersTable;

	public function __construct(UsersTable $usersTable)
	{
		$this->usersTable = $usersTable;
	}

	public function indexAction()
	{
		# make sure whoever accesses this page is logged in
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()) {
			return $this->redirect()->toRoute('login');
		}

		# make sure that only the admin can acess this page
		if(!$this->authPlugin()->getRoleId() == 1) {
			return $this->notFoundAction();
		}

		#grab paginator from UsersTable
		$paginator = $this->usersTable->fetchAllAccounts(true);
		$page = (int) $this->params()->fromQuery('page', 1); # sorry i forgot this line..
		$page = ($page < 1) ? 1 : $page;
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage(5);

		return new ViewModel(['accounts' => $paginator]);
	}

	public function deleteAction()
	{
		$userId = (int) $this->params()->fromRoute('id');
		if(!is_numeric($userId) || !$this->usersTable->fetchAccountById($userId)) {
			return $this->notFoundAction();
		}

		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()) {
			return $this->redirect()->toRoute('login');
		}

            # make sure that only the admin can acess this page
		if(!$this->authPlugin()->getRoleId() == 1) {
			return $this->notFoundAction();
		}

		$deleteForm = new DeleteForm();
		$request = $this->getRequest();

		if($request->isPost()) {
			$formData = $request->getPost()->toArray();
			$deleteForm->setData($formData);

			if($deleteForm->isValid()) {
				if($request->getPost()->get('delete_account') == 'Yes') {
					$this->usersTable->deleteAccount((int) $userId);
					$this->flashMessenger()->addSuccessMessage('Account successfully deleted.');

					return $this->redirect()->toRoute('admin_user', ['action' => 'index']);
				}

				# otherwise return to the homepage
				return $this->redirect()->toRoute('home');
			}
		}

		return new ViewModel(['id' => $userId, 'form' => $deleteForm]);
	}
}
