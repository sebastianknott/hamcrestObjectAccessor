<?php

namespace SebastianKnott\HamcrestObjectAccessor\Test\Unit\fixtures;

class HasPropertyFixture
{
    public $bla = 'blub';
    private $getable = 'blub';
    private $issable = true;
    private $hassable = true;

    public function getGetable()
    {
        return $this->getable;
    }

    public function isIssable()
    {
        return $this->issable;
    }

    public function hasHassable()
    {
        return $this->hassable;
    }
}
