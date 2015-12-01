<?php

namespace Pozitim\CI\Console;

use Pozitim\CI\Docker\Compose\ComposeFacade;
use Pozitim\CI\Docker\Compose\ComposeRunner;
use Pozitim\CI\Exec\Result;
use Pozitim\Console\DiHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Config\Config;

class ComposeRunnerCommand extends Command
{
    protected function configure()
    {
        $this->setName('compose:run')
            ->addOption('project-file', null, InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var DiHelper $diHelper
         * @var Config $sysConfig
         * @var ComposeRunner $dockerComposeRunner
         * @var Result $result
         */
        $diHelper = $this->getHelper('di');
        $projectFolder = $input->getOption('project-file');
        $dockerComposeRunner = $diHelper->getDi()->get('docker_compose_runner');
        $results = $dockerComposeRunner->run($projectFolder);
        foreach ($results as $suiteName => $result) {
            $output->writeln('Suite: ' . $suiteName . ' ExitCode: ' . $result->getExitCode());
            $output->writeln($result->getOutput());
        }
    }
}
