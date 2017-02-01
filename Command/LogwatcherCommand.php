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

	protected $tell = 0;
	protected $alert = [];
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$sFileLog = $this->getContainer()->getParameter('kernel.logs_dir').'/'.$this->getContainer()->getParameter('kernel.environment').'.log';
		//echo "$sFileLog\n";
		$this->alert = $this->getContainer()->getParameter('didungar.logwatcher.alert');

		while ( true ) {
			$handle = @fopen($sFileLog, 'r');
			if ( empty($handle) ) {
				throw new \Exception('$handle not open');
			}

			if ( $this->tell ) {
				fseek($handle, $this->tell);
			}
			while($line = fgets($handle)) {
				$alert_on = 0;
				foreach($this->alert as $alert_regex) {
					$alert_on += preg_match($alert_regex, $line);
				}
				if ( ! $alert_on ) {
					continue;
				}
				echo $line;
				if ( $this->getContainer()->hasParameter('didungar.logwatcher.mail') ) {
					$this->alertMail($line);
				}
			}
			$this->tell = ftell($handle);

			fclose($handle);

			sleep(15);
		}
	}
	protected function alertMail($sLigne) {
		$message = \Swift_Message::newInstance()
			->setSubject(__CLASS__)
			//->setFrom('TODO@TODO.todo')
			->setTo($this->getContainer()->getParameter('didungar.logwatcher.mail'))
			->setBody('Test Body');
		$ret = $this->getContainer()->get('mailer')->send($message);
		echo "Send mail : $ret\n";
		return $ret;
	}
}
