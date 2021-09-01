<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'create a user',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private EntityManagerInterface      $em
    )
    {
        parent::__construct('app:create-user');
    }

    protected function configure(): void
    {
        $this
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'user name')
            ->addOption('surname', null, InputOption::VALUE_REQUIRED, 'user surname')
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'email address')
            ->addOption('password', null, InputOption::VALUE_REQUIRED, 'password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $user = (new User())
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER'])
            ->setName($input->getOption('name'))
            ->setSurname($input->getOption('surname'))
            ->setEmail($input->getOption('email'));

        $user->setPassword(
            password: $this->hasher->hashPassword(
                user: $user,
                plainPassword: $input->getOption('password')
            )
        );

        $this->em->persist($user);
        $this->em->flush();

        $io->success(sprintf("User %s created !", $input->getOption('email')));
        return Command::SUCCESS;
    }
}
