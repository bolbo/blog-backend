<?php

namespace Bolbo\Component\Model\Database\PublicSchema;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

use Bolbo\Component\Model\Database\PublicSchema\AutoStructure\Favorite as FavoriteStructure;
use Bolbo\Component\Model\Database\PublicSchema\Favorite;

/**
 * FavoriteModel
 *
 * Model class for table favorite.
 *
 * @see Model
 */
class FavoriteModel extends Model
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
        $this->structure = new FavoriteStructure;
        $this->flexible_entity_class = "\Bolbo\Component\Model\Database\PublicSchema\Favorite";
    }
}
