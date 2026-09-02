<?php

declare(strict_types=1);

/*
 * (c) Pablo Largo Mohedano <devnix.code@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devnix\BelfioreCode\Command;

use Devnix\BelfioreCode\Updater;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateCommand extends Command
{
    protected static $defaultName = 'update';

    protected function configure(): void
    {
        $this
            ->setDescription('Updates the data source')
            ->setHelp('Crawls Italian Belfiore codes and foreign region codes and updates the library from the official sources');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        $symfonyStyle->writeln('Updating sources');

        $updater = new Updater();

        $symfonyStyle->writeln('Generating list of cities');
        if (!$updater->generateCities()) {
            $symfonyStyle->warning('Cities source skipped (unavailable)');
        }

        $symfonyStyle->writeln('Generating list of regions');
        if (!$updater->generateRegions()) {
            $symfonyStyle->warning('Regions source skipped (unavailable)');
        }

        if ($updater->hasFailedSources()) {
            $updater->writeUnavailableLog();

            $rows = [];
            foreach ($updater->getFailedSources() as $name => $info) {
                $rows[] = [$name, $info['url'], $info['error']];
            }

            $symfonyStyle->section('Unavailable sources');
            $symfonyStyle->table(['Source', 'URL', 'Error'], $rows);
            $symfonyStyle->note('Details appended to var/unavailable_sources.txt');

            if ([] === array_diff_key(['cities' => null, 'regions' => null], $updater->getFailedSources())) {
                $symfonyStyle->error('All sources failed. No data was generated.');

                return Command::FAILURE;
            }

            $symfonyStyle->success('Data sources partially generated. Check warnings above.');

            return Command::SUCCESS;
        }

        $symfonyStyle->success('Data sources generated successfully');

        return Command::SUCCESS;
    }
}
