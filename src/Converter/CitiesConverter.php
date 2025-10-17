<?php

/*
 * (c) Pablo Largo Mohedano <devnix.code@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devnix\BelfioreCode\Converter;

use Symfony\Component\ErrorHandler\ErrorHandler;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Serializer;

class CitiesConverter extends AbstractConverter
{
    /**
     * @var array<string, string>
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
        'ID_PROVINCIA' => 'anpr_id',
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
     * @var array{
     *     'status': array{
     *         'A': 'active',
     *         'C': 'discontinued',
     *         'D': 'to_be_established',
     *     }
     * }
     */
    protected const VALUE_MAPPING = [
        'status' => [
            'A' => 'active',
            'C' => 'discontinued',
            'D' => 'to_be_established',
        ],
    ];

    protected Serializer $serializer;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array<int, array<string, string>>
     */
    protected $cities;

    public function __construct(string $path)
    {
        parent::__construct();

        $this->path = $path;
        $this->cities = $this->serializer->decode(ErrorHandler::call('file_get_contents', $path), 'csv', [CsvEncoder::DELIMITER_KEY => ',']);

        $this->cities = $this->convertColumns($this->cities);
        $this->cities = $this->convertValues($this->cities);
    }

    /**
     * Rename the columns.
     *
     * @param array<int, array<string, string>> $cities
     *
     * @return array<int, array<string, string>>
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
     * Convert all values using the converted columns.
     *
     * @param array<int, array<string, string>> $cities
     *
     * @return array<int, array<string, string>>
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
        return $this->cities;
    }
}
