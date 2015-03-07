<?php

namespace Bolbo\Component\Model;

use PommProject\Foundation\Converter\PgLtree;
use PommProject\ModelManager\SessionBuilder;
use PommProject\ModelManager\Converter\PgEntity;

use PommProject\Foundation\Session\Session;

/**
 * Class BolboSessionBuilder
 *
 * @package Bolbo\Component\Model
 */
class BolboSessionBuilder extends SessionBuilder
{
    /**
     * @param Session $session
     *
     * @throws \PommProject\Foundation\Exception\FoundationException
     * @return void
     */
    protected function postConfigure(Session $session)
    {
        parent::postConfigure($session);
        $converter_holder = $session
            ->getPoolerForType('converter')
            ->getConverterHolder();
        $converter_holder
            ->registerConverter('LTree', new PgLTree(), ['public.ltree']);
    }
}