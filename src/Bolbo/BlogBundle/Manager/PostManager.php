<?php
/**
 *
 */
namespace Bolbo\BlogBundle\Manager;

use Bolbo\Component\Manager\BaseManager;

/**
 * Class PostManager
 *
 * @package Bolbo\BlogBundle\Manager
 */
class PostManager extends BaseManager
{

    /**
     * @var string
     */
    var $databaseConnexion;


    /**
     * @param \PommProject\Foundation\Pomm $pomm
     * @param string                       $databaseConnexion
     * @param string                       $modelClass
     */
    public function __construct($pomm, $databaseConnexion, $modelClass)
    {
        $this->databaseConnexion = $databaseConnexion;
        parent::__construct($pomm, $modelClass);
    }


    /**
     * @return \Bolbo\Component\Model\Database\PublicSchema\PostModel
     */
    public function getPommModel()
    {
        return $this->pomm[$this->databaseConnexion]->getModel($this->modelClass);
    }


    /**
     * @param \Bolbo\Component\Model\Database\PublicSchema\Post $data
     *
     * @return mixed
     */
    public function set($data)
    {
        return $this->getPommModel()->updateOne($data, array_keys($data->fields()));
    }
}
