<?php

namespace Devnix\BelfioreCode\Collection;

use Doctrine\Common\Collections\ArrayCollection;

class AbstractCollection extends ArrayCollection
{
    /**
     * @return static
     * @psalm-return static<TKey,T>
     */
    public function groupBy($key)
    {
        $alreadyMatched = [];

        return $this->filter(function($element) use (&$alreadyMatched, $key) {
            if (!array_key_exists($key, $element)) {
                throw new \InvalidArgumentException(sprintf('Key "%s" does not exists', $key));
            }

            if (in_array($element[$key], $alreadyMatched, true)) {
                return false;
            }

            $alreadyMatched[] = $element[$key];
            return true;
        });
    }
}
