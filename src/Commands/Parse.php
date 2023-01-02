<?php

namespace App\Commands;

use App\Repository\SettingsRepository;
use App\Service\Parser;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: "book:parse", description: "Parse books")]
class Parse extends Command
{
    private Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            '<info>+---------------+</info>',
            '<info>| Start parsing |</info>',
            '<info>+---------------+</info>',
        ]);

        if ($this->parser->parse()) return Command::SUCCESS;
        else return Command::FAILURE;
    }
}