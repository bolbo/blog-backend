<?php

namespace Bolbo\Component\Model\Database\PublicSchema;

use PommProject\ModelManager\Model\FlexibleEntity;

/**
 * Post
 *
 * Flexible entity for relation
 * public.post
 *
 * @see FlexibleEntity
 */
class Post extends FlexibleEntity
{
    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode(['id' => $this->getId(), 'title' => $this->getTitle()]);
    }
}
