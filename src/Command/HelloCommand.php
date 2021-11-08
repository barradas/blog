<?php

namespace App\Command;

use App\Service\Greeting;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class HelloCommand extends Command
{
    /**
     * @var Greeting
     */
    private $greeting;

    public function __construct(Greeting $greeting)
    {
        $this->greeting = $greeting;
        // you *must* call the parent constructor
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:say-hello')
            ->setDescription('Say hello to the user')
            ->addArgument('name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $output->writeln([
                'HELLO from the app',
                '______________',
                '']
        );
        $output->writeln($this->greeting->greet($name));

        return 0;
    }

}
