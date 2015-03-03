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
     * @var \Cocur\Slugify\Slugify
     */
    var $slugify;


    /**
     * @param \PommProject\Foundation\Pomm $pomm
     * @param string                       $databaseConnexion
     * @param string                       $modelClass
     * @param \Cocur\Slugify\Slugify       $slugify
     */
    public function __construct($pomm, $databaseConnexion, $modelClass, $slugify)
    {
        $this->databaseConnexion = $databaseConnexion;
        $this->slugify = $slugify;
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
     * @return \Bolbo\Component\Model\Database\PublicSchema\Post
     */
    public function save($data)
    {
        if (!$data->has('id')) {
            // Create new object -> generate slug
            // @todo lbolzer generate unique slug
            $slug = $this->slugify->slugify($data->title);
            $data->slug = $slug;
            $this->getPommModel()->insertOne($data);
        } else {
            // Update obect
            $this->getPommModel()->updateOne($data, array_keys($data->fields()));
        }

        return $data;
    }
}
