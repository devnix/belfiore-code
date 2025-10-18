<?php

declare(strict_types=1);

/*
 * (c) Pablo Largo Mohedano <devnix.code@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devnix\BelfioreCode;

use Devnix\BelfioreCode\Converter\CitiesConverter;
use Devnix\BelfioreCode\Converter\RegionsConverter;
use Devnix\BelfioreCode\Exception\UpdaterException;
use Devnix\ZipException\ZipException;
use Symfony\Component\ErrorHandler\ErrorHandler;

final class Updater
{
    /**
     * @var string
     *
     * @see https://developers.italia.it/en/anpr
     *
     * @license cc-by-3.0
     */
    protected const CITIES = 'https://raw.githubusercontent.com/italia/anpr/master/src/archivi/ANPR_archivio_comuni.csv';

    /**
     * @var string
     *
     * @see https://www.istat.it
     *
     * @license cc-by-4.0
     */
    protected const REGIONS = 'https://www.istat.it/wp-content/uploads/2024/03/Elenco-codici-e-denominazioni-unita-territoriali-estere.zip';

    /**
     * @var string
     */
    protected const DESTINATION = __DIR__.'/../dist';

    private string $citiesPath;

    private readonly string $regionsPath;

    public function __construct()
    {
        $this->citiesPath = $this->downloadTmpCities();
        $this->regionsPath = $this->downloadTmpRegions();
    }

    public function __destruct()
    {
        $this->removeTmpCities();
        $this->removeTmpRegions();
    }

    /**
     * Fetch and update all the data.
     */
    public function generateCities(): void
    {
        $this->createDistDirectory();

        $citiesConverter = new CitiesConverter($this->citiesPath);

        file_put_contents(self::DESTINATION.'/cities.csv', $citiesConverter->getCsv());
        file_put_contents(self::DESTINATION.'/cities.xml', $citiesConverter->getXml());
        file_put_contents(self::DESTINATION.'/cities.json', $citiesConverter->getJson());
        file_put_contents(self::DESTINATION.'/cities.yaml', $citiesConverter->getYaml());
    }

    public function generateRegions(): void
    {
        $this->createDistDirectory();

        $regionsConverter = new RegionsConverter($this->regionsPath);

        file_put_contents(self::DESTINATION.'/regions.csv', $regionsConverter->getCsv());
        file_put_contents(self::DESTINATION.'/regions.xml', $regionsConverter->getXml());
        file_put_contents(self::DESTINATION.'/regions.json', $regionsConverter->getJson());
        file_put_contents(self::DESTINATION.'/regions.yaml', $regionsConverter->getYaml());
    }

    private function createDistDirectory(): void
    {
        if (!is_dir(self::DESTINATION)) {
            ErrorHandler::call('mkdir', self::DESTINATION);
        }
    }

    private function downloadTmpCities(): string
    {
        return ErrorHandler::call(static function () {
            $tmpPath = tempnam(sys_get_temp_dir(), 'devnix-belfiore-code-cities.csv.');

            file_put_contents($tmpPath, file_get_contents(self::CITIES));

            return $tmpPath;
        });
    }

    private function downloadTmpRegions(): string
    {
        return ErrorHandler::call(static function () {
            $tmpZip = tempnam(sys_get_temp_dir(), 'devnix-belfiore-code-cities.zip.');
            $tmpPath = tempnam(sys_get_temp_dir(), 'devnix-belfiore-code-regions.xlsx.');

            file_put_contents($tmpZip, file_get_contents(self::REGIONS));

            $zipArchive = new \ZipArchive();

            if (!$zipStatus = $zipArchive->open($tmpZip)) {
                throw new ZipException($zipStatus);
            }

            for ($i = 0; $i < $zipArchive->numFiles; ++$i) {
                $statIndex = $zipArchive->statIndex($i);

                if (false === $statIndex) {
                    throw new \RuntimeException(\sprintf('Could not get index %d from %s', $i, $tmpZip));
                }

                if ('xlsx' === (pathinfo($statIndex['name'])['extension'] ?? null)) {
                    file_put_contents($tmpPath, $zipArchive->getFromName($statIndex['name']));

                    return $tmpPath;
                }
            }

            throw new UpdaterException('Could not find the xlsx file inside of the downloaded regions zip');
        });
    }

    private function removeTmpCities(): void
    {
        if ('' === $this->citiesPath || '0' === $this->citiesPath) {
            return;
        }

        try {
            ErrorHandler::call('unlink', $this->citiesPath);
        } catch (\Exception) {
        }

        unset($this->citiesPath);
    }

    private function removeTmpRegions(): void
    {
        if ('' === $this->citiesPath || '0' === $this->citiesPath) {
            return;
        }

        try {
            ErrorHandler::call('unlink', $this->regionsPath);
        } catch (\Exception) {
        }

        unset($this->citiesPath);
    }
}
