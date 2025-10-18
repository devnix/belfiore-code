<?php

declare(strict_types=1);

/*
 * (c) Pablo Largo Mohedano <devnix.code@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devnix\BelfioreCode\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * @extends ArrayCollection<int, array<string,string>>
 */
class AbstractCollection extends ArrayCollection
{
    public function matching(Criteria $criteria): static
    {
        // @phpstan-ignore return.type
        return parent::matching($criteria);
    }

    public function groupBy(string $key): self
    {
        $alreadyMatched = [];

        return $this->filter(function (array $element) use (&$alreadyMatched, $key): bool {
            if (!\array_key_exists($key, $element)) {
                throw new \InvalidArgumentException(\sprintf('Key "%s" does not exists', $key));
            }

            if (\in_array($element[$key], $alreadyMatched, true)) {
                return false;
            }

            $alreadyMatched[] = $element[$key];

            return true;
        });
    }
}
