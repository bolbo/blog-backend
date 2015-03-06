<?php

namespace Bolbo\BlogBundle\Model;

use Bolbo\Component\Model\Database\PublicSchema\Post;

class PostCollection
{

    /**
     * @var Post[]
     */
    public $posts;

    /**
     * @var integer
     */
    public $offset;

    /**
     * @var integer
     */
    public $limit;


    /**
     * @param Post[]  $posts
     * @param integer $offset
     * @param integer $limit
     */
    public function __construct($posts = array(), $offset = null, $limit = null)
    {
        $this->posts = $posts;
        $this->offset = $offset;
        $this->limit = $limit;
    }
}
