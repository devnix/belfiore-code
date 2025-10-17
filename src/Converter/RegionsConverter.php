<?php

/*
 * (c) Pablo Largo Mohedano <devnix.code@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devnix\BelfioreCode\Converter;

use PhpOffice\PhpSpreadsheet;
use Symfony\Component\Serializer\Serializer;

class RegionsConverter extends AbstractConverter
{
    /**
     * @var array<string, string>
     */
    protected const COLUMN_MAPPING = [
        'Stato(S)/Territorio(T)' => 'region_type',
        'Codice Continente' => 'istat_continent_id',
        'Denominazione Continente (IT)' => 'continent_name_it',
        'Codice Area' => 'istat_area_code',
        'Denominazione Area (IT)' => 'area_name_it',
        'Codice ISTAT' => 'istat_id',
        'Denominazione IT' => 'name_it',
        'Denominazione EN' => 'name_en',
        'Codice MIN' => 'anpr_id',
        'Codice AT' => 'registry_code',
        'Codice UNSD_M49' => 'unsd_m49_id',
        'Codice ISO 3166 alpha2' => 'iso-3166-1-alpha-2',
        'Codice ISO 3166 alpha3' => 'iso-3166-1-alpha-3',
        'Codice ISTAT_Stato Padre' => 'istat-parent-state-code',
        'Codice ISO alpha3_Stato Padre' => 'parent-iso-3166-1-alpha-3',
    ];

    /**
     * @var array{
     *     'region_type': array{
     *         'S': 'state',
     *         'T': 'territory',
     *     }
     * }
     */
    protected const VALUE_MAPPING = [
        'region_type' => [
            'S' => 'state',
            'T' => 'territory',
        ],
    ];

    protected Serializer $serializer;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array<int, array<string, int|string|null>>
     */
    protected $regions;

    public function __construct(string $path)
    {
        parent::__construct();

        $this->path = $path;

        $xlsxReader = new PhpSpreadsheet\Reader\Xlsx();
        $xlsxReader->setReadDataOnly(true)->setReadEmptyCells(false);
        $spreadsheet = $xlsxReader->load($this->path);

        $rawData = $spreadsheet->getActiveSheet()->toArray(null, true, false, true);
        $header = array_shift($rawData);

        $header = $this->convertColumns($header);

        $this->regions = $this->convertValues($this->setKeys($header, $rawData));
    }

    /**
     * Rename the columns.
     *
     * @param array<string, string|null> $regions
     *
     * @return array<string, string|null>
     */
    public function convertColumns(array $regions): array
    {
        foreach ($regions as $key => $value) {
            $regions[$key] = self::COLUMN_MAPPING[$value] ?? $value;
        }

        return $regions;
    }

    /**
     * Convert all values using the converted columns.
     *
     * @param array<int, array<string, int|string|null>> $regions
     *
     * @return array<int, array<string, int|string|null>>
     */
    public function convertValues(array $regions): array
    {
        foreach ($regions as $regionKey => $regionValue) {
            foreach (self::VALUE_MAPPING as $column => $values) {
                // dd([$column => $values]);
                foreach ($values as $oldValue => $newValue) {
                    if ($regions[$regionKey][$column] == $oldValue) {
                        $regions[$regionKey][$column] = $newValue;
                        break;
                    }
                }
            }
            // Remove empty keys due to a possible bug in phpspreadsheet
            $regions[$regionKey] = array_filter($regions[$regionKey], function ($value) {
                return null !== $value && '' !== $value; // @phpstan-ignore notIdentical.alwaysTrue
            }, \ARRAY_FILTER_USE_KEY);
        }

        return $regions;
    }

    /**
     * @param array<string, string|null>                 $header
     * @param array<int, array<string, int|string|null>> $data
     *
     * @return array<int, array<string, int|string|null>>
     */
    public function setKeys(array $header, array $data): array
    {
        foreach ($data as $position => $region) {
            foreach ($region as $key => $value) {
                $data[$position] = array_combine($header, $region); // @phpstan-ignore argument.type
            }
        }

        return $data;
    }

    public function getData(): array
    {
        return $this->regions;
    }
}
