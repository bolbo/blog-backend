<?php
/**
 *
 */
namespace Bolbo\BlogBundle\Manager;

use Bolbo\Component\Manager\BaseManager;
use PommProject\Foundation\Where;

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
        // Create array from tag list
        $tagList = explode(',', $data->tag);
        foreach ($tagList as $key => $tag) {
            $tagList[$key] = trim($tag);
        }
        $data->tag = $tagList;

        if (!$data->has('id')) {
            // Create new object -> generate slug
            // @todo lbolzer generate unique slug
            $slug = $this->slugify->slugify($data->title);
            $data->slug = $slug;
            $this->getPommModel()->insertOne($data);
        } else {
            // Update object
            $fields = $data->fields();
            if (array_key_exists('attachment_count', $fields)) {
                unset($fields['attachment_count']);
            }
            $fieldList = array_keys($fields);

            $this->getPommModel()->updateOne($data, $fieldList);
        }

        return $data;
    }


    /**
     * @param int       $start
     * @param int       $limit
     * @param \stdClass $filter
     * @param \stdClass $sort
     *
     * @return \PommProject\ModelManager\Model\CollectionIterator
     */
    public function fetch($start, $limit, $filter, $sort)
    {
        $where = Where::create();

        if (isset($filter->date->from) && !is_null($filter->date->from)) {
            $where->andWhere('created_at >= $*::timestamptz', [new \DateTime($filter->date->from)]);
        }
        if (isset($filter->date->to) && !is_null($filter->date->to)) {
            $where->andWhere('created_at < $*::timestamptz', [new \DateTime($filter->date->to)]);
        }
        if (isset($filter->author_id) && !is_null($filter->author_id)) {
            $where->andWhere('author_id = ANY($*::int4[])', [$filter->author_id]);
        }

        if (isset($filter->category_id) && !is_null($filter->category_id)) {
            $where->andWhere('category_id = ANY($*::int4[])', [$filter->category_id]);
        }

        if (isset($filter->tag) && is_array($filter->tag) && count($filter->tag) != 0) {
            $strWhere = '';
            $arrWhere = [];
            foreach ($filter->tag as $tag) {
                $strWhere .= " tag ~ '*." . $tag . ".*' or ";
                //$strWhere .= " tag ~ '*.$*::int4.*' || ";
                $arrWhere[] = $tag;
            }
            $strWhere = substr($strWhere, 0, -3);
            if ($strWhere != '') {
                //$where->andWhere('(' . $strWhere . ')', [$arrWhere]);
                $where->andWhere('(' . $strWhere . ')', []);
            }
        }

        // @todo bolbo sanitize order
        $suffix = '';
        if (!is_null($sort)) {
            $suffix .= ' ORDER BY ';
            foreach ($sort as $sortField => $sortOrder) {
                $suffix .= $sortField . ' ' . $sortOrder . ', ';
            }
            $suffix = substr($suffix, 0, -2);
        }

        return $this->getPommModel()
                    ->findWhere($where, [], $suffix);
    }


    /**
     * @param $id
     *
     * @return mixed
     */
    public function get($id)
    {
        return $this->getPommModel()
                    ->findWithSoftCountWhere(new Where('p.id = ANY($*::int4[])', [$id]))
            //->findWithSoftCountId($id)
                    ->current();
    }
}
