<?php

namespace Devnix\BelfioreCode;

use Devnix\BelfioreCode\Converter\CitiesConverter;
use Devnix\BelfioreCode\Converter\RegionsConverter;
use Devnix\ZipException\ZipException;
use Symfony\Component\ErrorHandler\ErrorHandler;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;
use ZipArchive;

final class Updater
{
    /**
     * @var string
     *
     * @link https://developers.italia.it/en/anpr
     * @license cc-by-3.0
     */
    protected const CITIES = 'https://raw.githubusercontent.com/italia/anpr/master/src/archivi/ANPR_archivio_comuni.csv';

    /**
     * @var string
     * @link https://www.istat.it
     * @license cc-by-4.0
     */
    protected const COUNTRIES = 'https://www.istat.it/it/files/2011/01/Elenco-codici-e-denominazioni-unita-territoriali-estere-1.zip';

    /**
     * @var string
     */
    protected const DESTINATION = __DIR__.'/../dist';

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var string
     */
    protected $citiesPath;

    /**
     * @var string
     */
    protected $countriesPath;

    public function __construct()
    {
        $this->serializer = new Serializer([], [new CsvEncoder(), new XmlEncoder(), new JsonEncoder()]);

        $this->citiesPath = $this->downloadTmpCities();
        $this->countriesPath = $this->downloadTmpCountries();
    }

    public function __destruct()
    {
        $this->removeTmpCities();
        $this->removeTmpCountries();
    }

    /**
     * Fetch and update all the data
     *
     * @return null
     */
    public function generateCities(): void
    {
        $this->createDistDirectory();

        $citiesConverter = new CitiesConverter($this->citiesPath, $this->serializer);

        file_put_contents(self::DESTINATION.'/cities.csv', $citiesConverter->getCsv());
        file_put_contents(self::DESTINATION.'/cities.xml', $citiesConverter->getXml());
        file_put_contents(self::DESTINATION.'/cities.json', $citiesConverter->getJson());
        file_put_contents(self::DESTINATION.'/cities.yaml', $citiesConverter->getYaml());
    }

    public function generateRegions()
    {
        $this->createDistDirectory();

        $countriesConverter = new RegionsConverter($this->countriesPath, $this->serializer);

        file_put_contents(self::DESTINATION.'/countries.csv', $countriesConverter->getCsv());
        file_put_contents(self::DESTINATION.'/countries.xml', $countriesConverter->getXml());
        file_put_contents(self::DESTINATION.'/countries.json', $countriesConverter->getJson());
        file_put_contents(self::DESTINATION.'/countries.yaml', $countriesConverter->getYaml());
    }

    protected function createDistDirectory(): void
    {
        if (!is_dir(self::DESTINATION)) {
            ErrorHandler::call('mkdir', self::DESTINATION);
        }
    }

    protected function downloadTmpCities(): string
    {
        return ErrorHandler::call(static function () {
            $tmpPath = tempnam(sys_get_temp_dir(), 'devnix-belfiore-code-cities.csv.');

            file_put_contents($tmpPath, file_get_contents(self::CITIES));

            return $tmpPath;
        });
    }

    protected function downloadTmpCountries(): string
    {
        return ErrorHandler::call(static function () {
            $tmpZip = tempnam(sys_get_temp_dir(), 'devnix-belfiore-code-cities.zip.');
            $tmpPath = tempnam(sys_get_temp_dir(), 'devnix-belfiore-code-countries.xlsx.');

            file_put_contents($tmpZip, file_get_contents(self::COUNTRIES));

            $zip = new ZipArchive();

            if (!$zipStatus = $zip->open($tmpZip)) {
                throw new ZipException($zipStatus);
            }

            file_put_contents($tmpPath, $zip->getFromName('Elenco-codici-e-denominazioni-unita-territoriali-estere/Elenco-codici-e-denominazioni-al-31_12_2019.xlsx'));

            return $tmpPath;
        });
    }

    protected function removeTmpCities(): void
    {
        if (empty($this->citiesPath)) {
            return;
        }

        try {
            ErrorHandler::call('unlink', $this->citiesPath);
        } catch (\Exception $e) {};

        unset($this->citiesPath);
    }

    protected function removeTmpCountries(): void
    {
        if (empty($this->citiesPath)) {
            return;
        }

        try {
            ErrorHandler::call('unlink', $this->countriesPath);
        } catch (\Exception $e) {};

        unset($this->citiesPath);
    }
}
