<?php

/*
 * (c) Pablo Largo Mohedano <devnix.code@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devnix\BelfioreCode\Converter;

use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractConverter
{
    protected Serializer $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer([], [
            new CsvEncoder(),
            new XmlEncoder(),
            new JsonEncoder(),
            new YamlEncoder(null, null, ['yaml_inline' => 2, 'yaml_indent' => 2]),
        ]);
    }

    /**
     * @return array<int, array<string, int|string|null>>
     */
    abstract public function getData(): array;

    public function getCsv(): string
    {
        return $this->serializer->encode($this->getData(), 'csv');
    }

    public function getXml(): string
    {
        return $this->serializer->encode($this->getData(), 'xml');
    }

    public function getJson(): string
    {
        return $this->serializer->encode($this->getData(), 'json');
    }

    public function getYaml(): string
    {
        return $this->serializer->encode($this->getData(), 'yaml');
    }
}
