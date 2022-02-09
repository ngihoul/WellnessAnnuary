<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repository\UserRepository;

class DeleteUnverifiedUsersCommand extends Command
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    protected static $defaultName = 'app:delete:unverified-users';

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, LoggerInterface $logger) {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Delete users unverified after 24h');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $unverifiedUsers = $this->userRepository->getUnverified();
        $this->logger->info('Unverified_users_deleted : ' . count($unverifiedUsers));
        if($unverifiedUsers) {
            foreach($unverifiedUsers as $user) {
                if($user->getCustomer()) {
                    $images = $user->getCustomer()->getImages();
                    foreach($images as $image) {
                        $this->entityManager->remove($image);
                    }
                    $this->entityManager->remove($user->getCustomer());
                } else if ($user->getProvider()) {
                    $images = $user->getProvider()->getImages();
                    foreach($images as $image) {
                        $this->entityManager->remove($image);
                    }
                    $this->entityManager->remove($user->getProvider());
                }
                $this->entityManager->remove($user);
                $this->entityManager->flush();
            }
            return Command::SUCCESS;
        } else {
            return Command::FAILURE;
        }

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))


        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}