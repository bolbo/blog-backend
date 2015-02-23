<?php

namespace Bolbo\Component\Model\Database\PublicSchema;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

use Bolbo\Component\Model\Database\PublicSchema\AutoStructure\Category as CategoryStructure;
use Bolbo\Component\Model\Database\PublicSchema\Category;

/**
 * CategoryModel
 *
 * Model class for table category.
 *
 * @see Model
 */
class CategoryModel extends Model
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
        $this->structure = new CategoryStructure;
        $this->flexible_entity_class = "\Bolbo\Component\Model\Database\PublicSchema\Category";
    }
}
