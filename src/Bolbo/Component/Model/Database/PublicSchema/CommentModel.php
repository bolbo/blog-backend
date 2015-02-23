<?php

namespace Bolbo\Component\Model\Database\PublicSchema;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

use Bolbo\Component\Model\Database\PublicSchema\AutoStructure\Comment as CommentStructure;
use Bolbo\Component\Model\Database\PublicSchema\Comment;

/**
 * CommentModel
 *
 * Model class for table comment.
 *
 * @see Model
 */
class CommentModel extends Model
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
        $this->structure = new CommentStructure;
        $this->flexible_entity_class = "\Bolbo\Component\Model\Database\PublicSchema\Comment";
    }
}
