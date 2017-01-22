<?php
namespace  DidUngar\LogwatcherBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LogwatcherCommand extends ContainerAwareCommand
{
	protected $debug = false;
	protected function configure()
	{
		$this
			->setName('didungar:logwatcher')
			->setDescription('Lis les logs et fait des alertes par mail.')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->getContainer()->get('kernel')->getRootDir();
		echo "\n";
	}
}
