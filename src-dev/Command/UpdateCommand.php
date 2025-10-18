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
        $updater->generateCities();

        $symfonyStyle->writeln('Generating list of regions');
        $updater->generateRegions();

        $symfonyStyle->success('Data sources generated successfully');

        return 0;
    }
}
