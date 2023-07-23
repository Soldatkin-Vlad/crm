<?php

declare(strict_types=1);

namespace Application\Model\Table;

use Application\Model\Entity\CrmEntity;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Sql\Expression;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\Filter;
use Laminas\I18n;
use Laminas\InputFilter;
use Laminas\Validator;

class CrmTable extends AbstractTableGateway
{
	protected $adapter;
	protected $table = 'clients';

	public function __construct(Adapter $adapter)
	{
		$this->adapter = $adapter;
		$this->initialize();
	}

	public function closeQuiz(int $quizId)
	{
//		$sqlQuery = $this->sql->update()->set(['status' => '0'])->where(['quiz_id' => $quizId]);
//		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);
//
//		return $sqlStmt->execute();
	}

	public function deleteCrmById(int $id)
	{
		$sqlQuery = $this->sql->update()->set(['status' => '0'])->where(['id' => $id]);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);

		return $sqlStmt->execute();
	}

	# the correction should have been done here
	public function fetchAllClients(string $s)
	{
		$sqlQuery = $this->sql->select()
		    ->where([$this->table.'.status' => 1])
		    ->order('date_ad ASC');
        if($s){
            $sqlQuery->where->like($this->table.'.pasport', "%$s%");
        }

		$sqlStmt = $this->sql->prepareStatementForSqlObject($sqlQuery);
		$handler = $sqlStmt->execute();

		$classMethod = new ClassMethodsHydrator();
		$entity      = new CrmEntity();

		$resultSet = new HydratingResultSet($classMethod, $entity);
		$resultSet->initialize($handler);

		return $resultSet;
	}

	public function fetchClientById(int $id)
	{
		$sqlQuery = $this->sql->select()
			->where([$this->table.'.id' => $id]);

		$sqlStmt = $this->sql->prepareStatementForSqlObject($sqlQuery);
		$handler = $sqlStmt->execute()->current();

		if(!$handler) {
			return null;
		}

		$classMethod = new ClassMethodsHydrator();
		$entity      = new CrmEntity();

		$classMethod->hydrate($handler, $entity);

		return $entity;
	}

	public function getCreateFormFilter()
	{
		$inputFilter = new InputFilter\InputFilter();
		$factory     = new InputFilter\Factory();

		# filter and validate quiz title
		$inputFilter->add(
			$factory->createInput([
				'name' => 'username',
				'required' => true,
				'filters' => [
					['name' => Filter\StripTags::class],
					['name' => Filter\StringTrim::class],
				],
				'validators' => [
					['name' => Validator\NotEmpty::class],
					[
						'name' => Validator\StringLength::class,
						'options' => [
							'min' => 4,
							'max' => 100,
							'messages' => [
								Validator\StringLength::TOO_SHORT => 'ФИО должно быть больше 4 символов',
								Validator\StringLength::TOO_LONG  => 'ФИО должно быть меньше 100 символов',
							],
						],
					],
				],
			])
		);

		/*# filter and validate category_id field


		# filter and validate timeout select field
		$inputFilter->add(
			$factory->createInput([
				'name' => 'timeout',
				'required' => true,
				'filters' => [
					['name' => Filter\StripTags::class],
					['name' => Filter\StringTrim::class],
				],
				'validators' => [
					['name' => Validator\NotEmpty::class],
					[
						'name' => Validator\InArray::class,
						'options' => [
							'haystack' => ['0', '1'],
						],
					],
				],
			])
		);

		# filter and validate question textarea field
		$inputFilter->add(
			$factory->createInput([
				'name' => 'question',
				'required' => true,
				'filters' => [
					['name' => Filter\StripTags::class],
					['name' => Filter\StringTrim::class],
				],
				'validators' => [
					['name' => Validator\NotEmpty::class],
					[
						'name' => Validator\StringLength::class,
						'options' => [
							'min' => 5,
							'max' => 300,
							'messages' => [
								Validator\StringLength::TOO_SHORT => 'Question must have at least 5 characters',
								Validator\StringLength::TOO_LONG  => 'Question must have at most 300 characters',
							],
						],
					],
				],
			])
		);

		#filter and validate allow checkbox value
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'allow',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],  # removes html tags
						['name' => Filter\StringTrim::class],
						['name' => Filter\ToInt::class],
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						['name' => I18n\Validator\IsInt::class],
						[
							'name' => Validator\InArray::class,
							'options' => [
								'haystack' => [0, 1]
							],
						],
					],
				]
			)
		);


		# filter and validate user_id field
		$inputFilter->add(
			$factory->createInput([
				'name' => 'user_id',
				'required' => true,
				'filters' => [
					['name' => Filter\StripTags::class],
					['name' => Filter\StringTrim::class],
					['name' => Filter\ToInt::class],
				],
				'validators' => [
					['name' => Validator\NotEmpty::class],
					['name' => I18n\Validator\IsInt::class],
					[
						'name' => Validator\Db\RecordExists::class,
						'options' => [
							'table' => 'users',
							'field' => 'user_id',
							'adapter' => $this->adapter,
						],
					],
				],
			])
		);

		# filter and validate the csrf field
		$inputFilter->add(
			$factory->createInput([
				'name' => 'csrf',
				'required' => true,
				'filters' => [
					['name' => Filter\StripTags::class],
					['name' => Filter\StringTrim::class],
				],
				'validators' => [
					['name' => Validator\NotEmpty::class],
					[
						'name' => Validator\Csrf::class,
						'options' => [
							'messages' => [
								Validator\Csrf::NOT_SAME => 'Fill the form again and try one more time',
							],
						],
					],
				],
			])
		);*/

		return $inputFilter;
	}

	public function saveClient(array $data)
	{
	    // todo: сопоставить поля с формы с БД
		$values = [
			'username'     => $data['username'],
			'pasport' => $data['pasport'],
			'birthday'       => date('Y-m-d H:i:s', strtotime($data['birthday'])),
			'nomber_d'    => $data['nomber_d'],
			'data_d'       => date('Y-m-d H:i:s', strtotime($data['data_d'])),
			'trafic'     => $data['trafic'],
            'coments'     => $data['coments'],
            'status'     => '1'
		];

		$sqlQuery = $this->sql->insert()->values($values);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);

		return $sqlStmt->execute();
	}

}
