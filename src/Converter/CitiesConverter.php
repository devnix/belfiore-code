<?php

namespace Devnix\BelfioreCode\Converter;

use Symfony\Component\ErrorHandler\ErrorHandler;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Serializer;

class CitiesConverter extends AbstractConverter
{
    /**
     * @var array
     */
    protected const COLUMN_MAPPING = [
        'ID' => 'id',
        'DATAISTITUZIONE' => 'institution_date',
        'DATACESSAZIONE' => 'end_date',
        'CODISTAT' => 'istat_id',
        'CODCATASTALE' => 'registry_code',
        'DENOMINAZIONE_IT' => 'name_it',
        'DENOMTRASLITTERATA' => 'name_transliterated',
        'ALTRADENOMINAZIONE' => 'alternative_name',
        'ALTRADENOMTRASLITTERATA' => 'alternative_name_transliterated',
        'IDPROVINCIA' => 'anpr_id',
        'IDPROVINCIAISTAT' => 'istat_province_id',
        'IDREGIONE' => 'istat_region_id',
        'IDPREFETTURA' => 'istat_prefecture_id',
        'STATO' => 'status',
        'SIGLAPROVINCIA' => 'provincial_code',
        'FONTE' => 'source',
        'DATAULTIMOAGG' => 'last_update',
        'COD_DENOM' => 'istat_discontinued_code',
    ];

    /**
     * @var array
     */
    protected const VALUE_MAPPING = [
        'status' => [
            'A' => 'active',
            'C' => 'discontinued',
            'D' => 'to_be_established',
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
        $this->regions = $this->serializer->decode(ErrorHandler::call('file_get_contents', $path), 'csv', array(CsvEncoder::DELIMITER_KEY => ','));

        $this->regions = $this->convertColumns($this->regions);
        $this->regions = $this->convertValues($this->regions);
    }

    /**
     * Rename the columns
     */
    public function convertColumns(array $cities): array
    {
        foreach ($cities as $cityKey => $cityValue) {
            foreach ($cityValue as $column => $value) {
                $cities[$cityKey][self::COLUMN_MAPPING[$column]] = $value;
                unset($cities[$cityKey][$column]);
            }
        }

        return $cities;
    }

    /**
     * Convert all values using the converted columns
     */
    public function convertValues(array $cities): array
    {
        foreach ($cities as $cityKey => $cityValue) {
            foreach (self::VALUE_MAPPING as $column => $values) {
                foreach ($values as $oldValue => $newValue) {
                    if ($cities[$cityKey][$column] == $oldValue) {
                        $cities[$cityKey][$column] = $newValue;
                        break;
                    }
                }
            }
        }
        return $cities;
    }

    public function getData(): array
    {
        return $this->regions;
    }
}
