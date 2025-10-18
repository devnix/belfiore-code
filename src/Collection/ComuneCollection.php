<?php

declare(strict_types=1);

/*
 * (c) Pablo Largo Mohedano <devnix.code@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devnix\BelfioreCode\Collection;

class ComuneCollection extends AbstractCollection
{
    protected const JSON_PATH = __DIR__.'/../../dist/cities.json';

    public function __construct(?array $elements = null)
    {
        if (null === $elements) {
            $rawJson = file_get_contents(self::JSON_PATH);

            if (false === $rawJson) {
                throw new \RuntimeException('Unexpected error when reading '.self::JSON_PATH);
            }

            $elements = json_decode(
                $rawJson,
                flags: \JSON_OBJECT_AS_ARRAY,
            );
        }

        parent::__construct($elements);
    }
}
