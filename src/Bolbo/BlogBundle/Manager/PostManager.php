<?php
/**
 *
 */
namespace Bolbo\BlogBundle\Manager;

use Bolbo\Component\Manager\BaseManager;

/**
 * Class PostManager
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
     * @param \Bolbo\Component\Model\Database\PublicSchema\Post $data
     * @return mixed
     */
    public function set($data)
    {

        //dump(array_keys($data->fields()));
        //exit;
        return $this->getPommModel()->updateOne($data, array_keys($data->fields()));
    }
}
