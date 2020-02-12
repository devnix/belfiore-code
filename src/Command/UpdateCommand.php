<?php

namespace Devnix\BelfioreCode\Command;

use Devnix\BelfioreCode\Updater;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateCommand extends Command
{
    protected static $defaultName = 'update';

    protected function configure()
    {
        $this
        	->setDescription('Updates the data source')
        	->setHelp('Crawls Italian Belfiore codes and foreign region codes and updates the library from the official sources');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->writeln('Updating sources');
        $updater = new Updater();

        $io->writeln('Generating list of cities');
        $updater->generateCities();

        $io->writeln('Generating list of regions');
        $updater->generateRegions();

        $io->success('Data sources generated successfully');
        return 0;
    }
}
