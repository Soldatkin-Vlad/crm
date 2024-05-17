<?php

namespace History\Model\Entity;

class InteractionsEntity
{
    private $id;

    private $client_id;

    private $interaction_date;

    private $type_name;

    private $notes;

    private $performed_by;

    protected $status;

    // Getters and setters
    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }

    public function getInteractionDate()
    {
        return $this->interaction_date;
    }

    public function setInteractionDate($interaction_date)
    {
        $this->interaction_date = $interaction_date;
    }

    public function getTypeName()
    {
        return $this->type_name;
    }

    public function setTypeName($type_name)
    {
        $this->type_name = $type_name;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    public function getPerformedBy()
    {
        return $this->performed_by;
    }

    public function setPerformedBy($performed_by)
    {
        $this->performed_by = $performed_by;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }
}