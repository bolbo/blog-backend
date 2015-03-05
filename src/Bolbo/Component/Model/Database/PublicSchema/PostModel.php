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


    public function findWithSoftCountId($id)
    {
        // 1.define SQL query using placeholders
        $sql = <<<SQL
select
    :projection
from
    :post_table p
        left join :attachment_table a on p.id = a.post_id
where p.id = :post_id
group by p.id
SQL;
        // 2.define the projection
        $projection = $this
            ->createProjection()
            ->setField('attachment_count', 'count(a.*)', 'int4');

        // 3.replace placeholders
        $sql = strtr($sql,
            [
                ':projection'       => $projection->formatFieldsWithFieldAlias('p'),
                ':post_table'       => $this->getStructure()->getRelation(),
                ':attachment_table' => $this
                    ->getSession()
                    ->getModel('\Bolbo\Component\Model\Database\PublicSchema\CommentModel')
                    ->getStructure()
                    ->getRelation(),
                //':condition'        => $where
                ':post_id'          => $id
            ]
        );

        // 4.issue the query
        return $this->query($sql, [], $projection);
    }


    public function findWithSoftCountWhere(Where $where)
    {
        // 1.define SQL query using placeholders
        $sql = <<<SQL
select
    :projection
from
    :post_table p
        left join :attachment_table a on p.id = a.post_id
where :condition
group by p.id
SQL;
        // 2.define the projection
        $projection = $this
            ->createProjection()
            ->setField('attachment_count', 'count(a.*)', 'int4');

        // 3.replace placeholders
        $sql = strtr($sql,
            [
                ':projection'       => $projection->formatFieldsWithFieldAlias('p'),
                ':post_table'       => $this->getStructure()->getRelation(),
                ':attachment_table' => $this
                    ->getSession()
                    ->getModel('\Bolbo\Component\Model\Database\PublicSchema\CommentModel')
                    ->getStructure()
                    ->getRelation(),
                ':condition'        => $where
            ]
        );

        // 4.issue the query
        return $this->query($sql, [$where->getValues()], $projection);
    }
}
