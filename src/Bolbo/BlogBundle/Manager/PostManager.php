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


}
