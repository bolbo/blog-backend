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
     */
    public function __construct($pomm, $modelClass)
    {
        parent::__construct($pomm, $modelClass);
    }


    /**
     * @ return \PommProject\ModelManager\Model\Model
     *
     * @return \Bolbo\Component\Model\Database\PublicSchema\PostModel
     */
    public function getPommModel()
    {
        return $this->pomm['database']->getModel($this->modelClass);
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
