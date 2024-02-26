<?php

namespace Oihso\TelegramBotApiBundle\Command;

use Oihso\TelegramBotApiBundle\Service\Bot;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Telegram\Bot\Exceptions\TelegramSDKException;

#[AsCommand(name: 'telegram:bot:webhook:delete')]
class WebhookDeleteCommand extends Command
{
    /**
     * @var Bot
     */
    private Bot $bot;

    /**
     * @inheritDoc
     */
    public function __construct(Bot $bot)
    {
        parent::__construct(null);

        $this->bot = $bot;
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
	{
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'The bot name (is set in configuration file)')
            ->setDescription('Delete Webhook')
            ->setHelp('This command allows you to delete webhook for your bots')
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (null === $input->getArgument('name')) {
            $value = $io->askQuestion(new ChoiceQuestion('Entet the name of the bot', $this->bot->getNames()));
            $input->setArgument('name', $value);
        }

        if (!$this->bot->hasBot($input->getArgument('name'))) {
            $io->error('There is no bot with this name.');
            return Command::FAILURE;
        }

        try {
            $this->bot->getBot($input->getArgument('name'))->deleteWebhook();
        } catch (TelegramSDKException $exception) {
            $io->error($exception->getMessage());

            return Command::FAILURE;
        }

        $io->success(sprintf('Webhook has been deleted for "%s" bot', $input->getArgument('name')));

        return Command::SUCCESS;
    }
}