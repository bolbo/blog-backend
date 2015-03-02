<?php

namespace Bolbo\Component\Manager;

use PommProject\Foundation\Pomm;

/**
 * Class BaseManager
 * @package Bolbo\Component\Manager
 */
abstract class BaseManager
{
    /**
     * @var Pomm
     */
    protected $pomm;
    /**
     * @var string
     */
    protected $modelClass;

    /**
     * @param Pomm $pomm
     * @param string $modelClass
     */
    public function __construct(Pomm $pomm, $modelClass)
    {
        $this->pomm = $pomm;
        $this->modelClass = $modelClass;
    }


    /**
     * @return Pomm
     */
    public function getPomm()
    {
        return $this->pomm;
    }

    /**
     * @param Pomm $pomm
     */
    public function setPomm($pomm)
    {
        $this->pomm = $pomm;
    }

    /**
     * @return string
     */
    public function getModelClass()
    {
        return $this->modelClass;
    }

    /**
     * @param string $modelClass
     */
    public function setModelClass($modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function fetch($start, $limit)
    {
        return $this->getPommModel()
                    ->findAll();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->getPommModel()
                    ->findByPk(['id' => $id]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->getPommModel()
                    ->deleteByPK(['id' => $id]);
    }
}