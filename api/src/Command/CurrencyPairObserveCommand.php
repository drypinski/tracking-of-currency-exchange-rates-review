<?php

namespace App\Command;

use App\Entity\Currency\Field\Code;
use App\Exception\ValidationFailedException;
use App\Service\Validator\ValidatorInterface;
use App\UseCase\Command\Pair\Observing\Command as ObservingCommand;
use App\UseCase\Command\Pair\Observing\ObservingCurrencyPairHandlerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

#[AsCommand(
    name: 'app:currency-pair:observe',
    description: 'Enable/Disable observing of currency pair',
)]
final class CurrencyPairObserveCommand extends Command
{
    public function __construct(
        private readonly ObservingCurrencyPairHandlerInterface $observingCurrencyPair,
        private readonly ValidatorInterface $validator
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('base', InputArgument::REQUIRED, 'Base currency code. One of: '.implode(', ', Code::CODES))
            ->addArgument('quote', InputArgument::REQUIRED, 'Quote currency code. One of: '.implode(', ', Code::CODES))
            ->addOption('observe', null, InputOption::VALUE_REQUIRED, 'Enable/Disable observing of currency pair.')
            ->setHelp(
                <<<'HELP'
                    The <info>currency-pair:observe</info> command Enable/Disable pair to observe currency rate.

                    Start watching the "USD/EUR" currency pair.
                    <info>php %command.full_name% USD EUR --observe=1</info>

                    Stop watching the "USD/EUR" currency pair.
                    <info>php %command.full_name% USD EUR --observe=0</info>
                    HELP
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            return $this->handle($input, $output);
        } catch (ValidationFailedException $exception) {
            return $this->handleValidationException($exception, new SymfonyStyle($input, $output));
        } catch (Throwable $exception) {
            return $this->handleException($exception->getMessage(), new SymfonyStyle($input, $output));
        }
    }

    private function handle(InputInterface $input, OutputInterface $output): int
    {
        $command = new ObservingCommand(
            $input->getArgument('base'),
            $input->getArgument('quote'),
            (bool) $input->getOption('observe')
        );

        $this->validator->validate($command);

        $this->observingCurrencyPair->handle($command);

        return Command::SUCCESS;
    }

    private function handleValidationException(ValidationFailedException $exception, SymfonyStyle $io): int
    {
        foreach ($exception->getViolations() as $violation) {
            $io->error(\sprintf('"%s" %s', $violation->getPropertyPath(), $violation->getMessage()));
        }

        return Command::FAILURE;
    }

    private function handleException(string $errorMessage, SymfonyStyle $io): int
    {
        $io->error($errorMessage);

        return Command::FAILURE;
    }
}
