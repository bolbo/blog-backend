<?php

namespace Bolbo\Component\Model\Database\PublicSchema;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

use Bolbo\Component\Model\Database\PublicSchema\AutoStructure\Post as PostStructure;
use Bolbo\Component\Model\Database\PublicSchema\Post;

/**
 * PostModel
 *
 * Model class for table post.
 *
 * @see Model
 */
class PostModel extends Model
{
    use WriteQueries;

    /**
     * __construct()
     *
     * Model constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->structure = new PostStructure;
        $this->flexible_entity_class = "\Bolbo\Component\Model\Database\PublicSchema\Post";
    }
}
