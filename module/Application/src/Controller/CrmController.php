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

                    // Добавляем текущую дату и время в поле date_ad
                    $data['date_ad'] = date('Y-m-d H:i:s');

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

			//var_dump($post);
            $passportUploadForm->setData($post);
            if ($passportUploadForm->isValid()) {
                $data = $passportUploadForm->getData();

                //var_dump($data);
				// Известный путь к файлу, который нужно скопировать
				$sourceFilePath = $data["image-file"]['tmp_name'];
				// Создание новой временной директории
				// Генерация случайного набора цифр для имени временной директории
				$randomDirName = 'temp_' . mt_rand(100000, 999999);
				$tempDir = '/var/www/crm' . DIRECTORY_SEPARATOR . $randomDirName;
				if (!is_dir($tempDir) && !mkdir($tempDir, 0777, true) && !is_dir($tempDir)) {
					throw new \RuntimeException(sprintf('Directory "%s" was not created', $tempDir));
				}
				// Новый путь для копирования файла
				$destinationFilePath = $tempDir . DIRECTORY_SEPARATOR . basename($sourceFilePath);
				// Копирование файла в новую временную директорию
				if (copy($sourceFilePath, $destinationFilePath)) {
					//var_dump($destinationFilePath);
				}

				// Команда для выполнения
				$command = 'python3 /home/osboxes/pasport_eye/pasport_eye_v_0.2.0/pasport_eye.py -p ' . $randomDirName . ' --improved_recognition OFF --output terminal > ' . $tempDir . 'old' . ' 2>&1';

				// Выполнение команды и получение результата
				$output = [];
				$returnVar = 0;
				exec($command, $output, $returnVar);
		
				// Преобразование массива строк в одну строку
				$result = implode("\n", $output);
				//var_dump($output);

				//var_dump($result);

				// Путь к файлу
				$filePath = $tempDir . 'old';
				$result='';
				// Проверка наличия файла
				if (file_exists($filePath)) {
					$fileContent = file_get_contents($filePath);
		
					// Поиск первого значения из 10 чисел подряд
					$pattern = '/\d{10}/';
					preg_match($pattern, $fileContent, $matches);
			
					$result = $matches[0] ?? '';
					$s = $result;
				}
		
				// Чтение содержимого файла


				//$command = escapeshellcmd('/usr/custom/test.py');
				//$output = shell_exec($command);
				//var_dump($output);

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
			's' => $s,
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

