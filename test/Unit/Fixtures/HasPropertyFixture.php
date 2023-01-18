<?php

declare(strict_types=1);

namespace SebastianKnott\HamcrestObjectAccessor\Test\Unit\Fixtures;

class HasPropertyFixture
{
    public string $bla = 'blub';

    private string $getable = 'blub';

    private bool $issable = true;

    private bool $hassable = true;

    public function getGetable(): string
    {
        return $this->getable;
    }

    public function isIssable(): bool
    {
        return $this->issable;
    }

    public function hasHassable(): bool
    {
        return $this->hassable;
    }
}
