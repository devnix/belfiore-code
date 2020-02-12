<?php

namespace Devnix\BelfioreCode\Converter;

use PhpOffice\PhpSpreadsheet;
use Symfony\Component\ErrorHandler\ErrorHandler;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Serializer;

class RegionsConverter extends AbstractConverter
{
    /**
     * @var array
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
     * @var array
     */
    protected const VALUE_MAPPING = [
        'region_type' => [
            'S' => 'state',
            'T' => 'territory',
        ],
    ];

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $regions;

    public function __construct(string $path)
    {
        parent::__construct();

        $this->path = $path;

        $xlsxReader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $xlsxReader->setReadDataOnly(true)->setReadEmptyCells(false);
        $spreadsheet = $xlsxReader->load($this->path);

        $rawData = $spreadsheet->getActiveSheet()->toArray(null, true, false, true);
        $header = array_shift($rawData);

        $header = $this->convertColumns($header);
        $this->regions = $this->setKeys($header, $rawData);

        $this->regions = $this->convertValues($this->regions);
    }

    /**
     * Rename the columns
     */
    public function convertColumns(array $regions): array
    {
        foreach ($regions as $key => $value) {
            $regions[$key] = self::COLUMN_MAPPING[$value] ?? $regions[$key];
        }

        return $regions;
    }

    /**
     * Convert all values using the converted columns
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
            $regions[$regionKey] = array_filter($regions[$regionKey], function($value) {
                return !is_null($value) && $value !== '';
            }, ARRAY_FILTER_USE_KEY);
        }

        return $regions;
    }


    public function setKeys(array $header, array $data): array
    {
        foreach ($data as $position => $region) {
            foreach ($region as $key => $value) {
                $data[$position] = array_combine($header, $region);
            }
        }

        return $data;
    }

    public function getData(): array
    {
        return $this->regions;
    }
}
