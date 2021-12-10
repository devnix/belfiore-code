<?php

namespace Devnix\BelfioreCode\Collection;

use Symfony\Component\ErrorHandler\ErrorHandler;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

class RegionCollection extends AbstractCollection
{
    protected const JSON_PATH = __DIR__.'/../../dist/regions.json';

    public function __construct(array $elements = null)
    {
        if (is_null($elements)) {
            $serializer = new Serializer([], [new JsonEncoder()]);
            $elements = $serializer->decode(ErrorHandler::call('file_get_contents', self::JSON_PATH), 'json');
        }

        parent::__construct($elements);
    }
}
