<?php

declare(strict_types=1);

/*
 * (c) Pablo Largo Mohedano <devnix.code@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devnix\BelfioreCode\Tests\Collection;

use Devnix\BelfioreCode\Collection\ComuneCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use PHPUnit\Framework\TestCase;

final class ComuneCollectionTest extends TestCase
{
    private ComuneCollection $comuneCollection;

    protected function setUp(): void
    {
        $this->comuneCollection = new ComuneCollection();
    }

    public function testConstruct(): void
    {
        $value = $this->comuneCollection->matching((new Criteria())->where(new Comparison('name_it', Comparison::CONTAINS, 'ARCINAZZO ROMANO')))
            ->groupBy('name_it')
            ->first();

        $this->assertIsArray($value);
        $this->assertSame('ARCINAZZO ROMANO', $value['name_it']);
    }
}
