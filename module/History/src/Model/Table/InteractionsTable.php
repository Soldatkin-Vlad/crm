<?php

declare(strict_types=1);

namespace History\Model\Table;

use History\Model\Entity\InteractionsEntity;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Sql\Sql;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\Filter;
use Laminas\I18n;
use Laminas\InputFilter;
use Laminas\Validator;

class InteractionsTable extends AbstractTableGateway
{
    protected $adapter;
    protected $table = 'interactions';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->initialize();
    }

    public function deleteInteractionById(int $id)
    {
        $sqlQuery = $this->sql->update()->set(['status' => '0'])->where(['id' => $id]);
        $sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);

        return $sqlStmt->execute();
    }

    public function fetchAllInteractionsByClientId(int $client_id, string $type_name= null)
    {
        $sqlQuery = $this->sql->select()
            ->join('users', 'users.user_id = interactions.performed_by', ['username' => 'username'])
            ->where([$this->table . '.client_id' => $client_id])
            ->order('interaction_date DESC');
        if ($type_name) {
            $sqlQuery->where(['type_name' => $type_name]);
        }

        $sqlStmt = $this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler = $sqlStmt->execute();

        $classMethod = new ClassMethodsHydrator();
        $entity = new InteractionsEntity();

        $resultSet = new HydratingResultSet($classMethod, $entity);
        $resultSet->initialize($handler);

        return $resultSet;
    }

    /**public function fetchInteractionsById(string $s)
    {
        $sqlQuery = $this->sql->select()
            ->where([$this->table.'.status' => 1]);
        if($s){
            $sqlQuery->where->like($this->table.'.type_name', "%$s%");
        }

        $sqlStmt = $this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler = $sqlStmt->execute();

        $classMethod = new ClassMethodsHydrator();
        $entity      = new InteractionsEntity();

        $resultSet = new HydratingResultSet($classMethod, $entity);
        $resultSet->initialize($handler);

        return $resultSet;
    }**/

    public function fetchInteractionById(int $id)
    {
        $sqlQuery = $this->sql->select()
            ->where([$this->table . '.id' => $id]);

        $sqlStmt = $this->sql->prepareStatementForSqlObject($sqlQuery);
        $handler = $sqlStmt->execute()->current();

        if (!$handler) {
            return null;
        }

        $classMethod = new ClassMethodsHydrator();
        $entity = new InteractionsEntity();

        $classMethod->hydrate($handler, $entity);

        return $entity;
    }

    public function getCreateFormFilter()
    {
        $inputFilter = new InputFilter\InputFilter();
        $factory = new InputFilter\Factory();

        // Adding validation for interaction_date
        $inputFilter->add(
            $factory->createInput([
                'name' => 'interaction_date',
                'required' => true,
                'validators' => [
                    ['name' => Validator\NotEmpty::class],
                    ['name' => Validator\Date::class],
                ],
            ])
        );

        // Adding validation for type_name
        $inputFilter->add(
            $factory->createInput([
                'name' => 'type_name',
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
                            'min' => 1,
                            'max' => 255,
                        ],
                    ],
                ],
            ])
        );

        // Adding validation for notes
        $inputFilter->add(
            $factory->createInput([
                'name' => 'notes',
                'required' => false,
                'filters' => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\StringTrim::class],
                ],
                'validators' => [
                    [
                        'name' => Validator\StringLength::class,
                        'options' => [
                            'max' => 1023,
                        ],
                    ],
                ],
            ])
        );

        return $inputFilter;
    }

    public function saveInteraction(array $data)
    {
        $values = [
            'client_id' => $data['client_id'],
            'interaction_date' => date('Y-m-d H:i:s', strtotime($data['interaction_date'])),
            'type_name' => $data['type_name'],
            'notes' => $data['notes'],
            'performed_by' => $data['performed_by'],
            'status' => $data['status'],
        ];

        $sqlQuery = $this->sql->insert()->values($values);
        $sqlStmt = $this->sql->prepareStatementForSqlObject($sqlQuery);

        return $sqlStmt->execute();
    }
}
