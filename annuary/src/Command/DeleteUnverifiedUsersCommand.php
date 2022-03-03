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

    /**
     * Constructor
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        parent::__construct();
    }

    /**
     * Command configuration
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription('Delete users unverified after 24h');
    }

    /**
     * Deletes users who have been unverified for more than 24 hours
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int Return status
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Fetch unverified users
        $unverifiedUsers = $this->userRepository->getUnverified();
        // Log the number of unverified users
        $this->logger->info('Unverified_users_deleted : ' . count($unverifiedUsers));
        if($unverifiedUsers) {
            foreach($unverifiedUsers as $user) {
                if($user->getCustomer()) {
                    // Delete images linked to this customer
                    $images = $user->getCustomer()->getImages();
                    foreach($images as $image) {
                        $this->entityManager->remove($image);
                    }
                    // Delete customer linked to the user
                    $this->entityManager->remove($user->getCustomer());
                } else if ($user->getProvider()) {
                    // Delete images linked to this provider
                    $images = $user->getProvider()->getImages();
                    foreach($images as $image) {
                        $this->entityManager->remove($image);
                    }
                    // Delete provider linked to the user
                    $this->entityManager->remove($user->getProvider());
                }
                // Delete the user
                $this->entityManager->remove($user);
                $this->entityManager->flush();
            }
            return Command::SUCCESS;
        } else {
            return Command::FAILURE;
        }
    }
}