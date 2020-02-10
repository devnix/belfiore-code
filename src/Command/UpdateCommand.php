<?php

namespace Devnix\BelfioreCode\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends Command
{
    protected static $defaultName = 'update';

    protected function configure()
    {
        $this
        	->setDescription('Updates the data source')
        	->setHelp('Crawls Italian Belfiore codes and foreign country codes and updates the library from the official sources');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return 0;
    }
}
