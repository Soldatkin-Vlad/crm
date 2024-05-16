<?php

declare(strict_types=1);

namespace Application\Model\Entity;

use Application\Model\SlugTrait; # be sure to add this bit

class CrmEntity
{
	// use SlugTrait;
	protected $id;
	protected $username;
	protected $pasport;
    protected $status;
    protected $birthday;
    protected $nomber_d;
    protected $data_d;
    protected $trafic;
    protected $coments;
    protected $data_ad;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPasport()
    {
        return $this->pasport;
    }

    /**
     * @param mixed $pasport
     */
    public function setPasport($pasport): void
    {
        $this->pasport = $pasport;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $birthday
     */
    public function setBirthday($birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return mixed
     */
    public function getNomberD()
    {
        return $this->nomber_d;
    }

    /**
     * @param mixed $nomber_d
     */
    public function setNomberD($nomber_d): void
    {
        $this->nomber_d = $nomber_d;
    }

    /**
     * @return mixed
     */
    public function getDataD()
    {
        return $this->data_d;
    }

    /**
     * @param mixed $data_d
     */
    public function setDataD($data_d): void
    {
        $this->data_d = $data_d;
    }

    /**
     * @return mixed
     */
    public function getTrafic()
    {
        return $this->trafic;
    }

    /**
     * @param mixed $trafic
     */
    public function setTrafic($trafic): void
    {
        $this->trafic = $trafic;
    }

    /**
     * @return mixed
     */
    public function getComents()
    {
        return $this->coments;
    }

    /**
     * @param mixed $coments
     */
    public function setComents($coments): void
    {
        $this->coments = $coments;
    }

    /**
     * @return mixed
     */
    public function getDataAd()
    {
        return $this->data_ad;
    }

    /**
     * @param mixed $data_ad
     */
    public function setDataAd($data_ad): void
    {
        $this->data_ad = $data_ad;
    }
}
