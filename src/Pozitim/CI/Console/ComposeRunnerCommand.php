<?php

namespace Pozitim\CI\Console;

use Pozitim\CI\Docker\ComposeRunner;
use Pozitim\Console\DiHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ComposeRunnerCommand extends Command
{
    protected function configure()
    {
        $this->setName('compose:run')
            ->addOption('project-file', null, InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectFilePath = $input->getOption('project-file');
        $composeRunner = new ComposeRunner($this->getDi());
        $composeRunner->run($projectFilePath);
    }

    /**
     * @return \OU\DI
     */
    protected function getDi()
    {
        /**
         * @var DiHelper $diHelper
         */
        $diHelper = $this->getHelper('di');
        return $diHelper->getDi();
    }
}
