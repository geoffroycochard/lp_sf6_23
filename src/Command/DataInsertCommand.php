<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Ticket;
use App\Entity\User;
use App\Repository\TicketRepository;
use DateTime;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Faker;

#[AsCommand(
    name: 'app:data-insert',
    description: 'Use to insert',
)]
class DataInsertCommand extends Command
{
    private TicketRepository $ticketRepository;

    public function __construct(
        TicketRepository $ticketRepository
    )
    {
        $this->ticketRepository = $ticketRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('limit', InputArgument::OPTIONAL, 'Limit data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $limit = $input->getArgument('limit');

        if ($limit) {
            $io->note(sprintf('You passed an argument: %s', $limit));
        }

        $faker = Faker\Factory::create('fr_FR');

        for ($i=0; $i < 10; $i++) { 
            // User
            if ($i%3 === 0) {
                $user = (new User())
                    ->setUsername($faker->userName)
                ;
            }
            // Category
            if ($i%5 === 0) {
                $category = (new Category())
                    ->setTitle($faker->words(3, true))
                ;
            }
            $ticket = (new Ticket())
                ->setTitle($faker->words(5, true))
                ->setDescription($faker->sentences(3, true))
                ->setDate($faker->dateTimeInInterval('-1 week', '+3 days'))
                ->addUser($user)
                ->setCategory($category)
            ;

            $this->ticketRepository->save($ticket, true);
        }

        

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
