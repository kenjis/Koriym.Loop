<?php

declare(strict_types=1);

namespace Koriym\ResultSet;

use PHPUnit\Framework\TestCase;

use function assert;

class ListGenTest extends TestCase
{
    /** @var array<array<scalar>> */
    protected $resultSet;

    protected function setUp(): void
    {
        $this->resultSet = [
            ['id' => 1, 'name' => 'ray'],
            ['id' => 2, 'name' => 'di'],
            ['id' => 3, 'name' => 'aop'],
        ];
    }

    public function testInvoke(): void
    {
        /** @var ListGen<FakeUser> $list */
        $list = (new ListGen())($this->resultSet, FakeUser::class);
        foreach ($list as $loop => $user) {
            /** @var Loop $loop */
            $this->assertInstanceOf(Loop::class, $loop);
            $this->assertInstanceOf(FakeUser::class, $user); // Not by assertContainsOnlyInstancesOf because for the debugging
        }

        $this->assertFalse($loop->isFirst);
        $this->assertTrue($loop->isLast);
    }

    public function testEmptyList()
    {
        $list =  (new ListGen())([], FakeUser::class);
        $item = null;
        foreach ($list as $loop => $item) {
            assert($item instanceof FakeUser);
        }

        $this->assertNull($item);
    }

    public function testSingleItem()
    {
        $list =  (new ListGen())([['id' => 1, 'name' => 'ray']], FakeUser::class);
        foreach ($list as $loop => $item) {
            $this->assertInstanceOf(Loop::class, $loop);
        }

        $this->assertTrue($loop->isFirst);
        $this->assertTrue($loop->isLast);
    }
}
