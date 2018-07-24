<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppCreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create:admin';
    private $entityManager;
    private $encoder;

    public function __construct(?string $name = null, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
    }

    protected function configure()
    {
        $this
            ->setDescription('Create an admin user.')
            ->setHelp('The command creates an administrator user to manage the other users. It is necessary to execute the command if there are no administrator users in the database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        //username
        $question1 = new Question('Please enter the username: ');
        $question1->setValidator(function ($answer) {
            if (trim($answer) == '') {
                throw new \Exception('The username cannot be empty');
            }
            return $answer;
        });
        $username = $helper->ask($input, $output, $question1);

        //firstName
        $question2 = new Question('Please enter the first name: ');
        $question2->setValidator(function ($answer) {
            if (trim($answer) == '') {
                throw new \Exception('The first name cannot be empty');
            }
            return $answer;
        });
        $firstName = $helper->ask($input, $output, $question2);

        //lastName
        $question3 = new Question('Please enter the last name: ');
        $question3->setValidator(function ($answer) {
            if (trim($answer) == '') {
                throw new \Exception('The last name cannot be empty');
            }
            return $answer;
        });
        $lastName = $helper->ask($input, $output, $question3);

        //email
        $question4 = new Question('Please enter the email: ');
        $question4->setValidator(function ($answer) {
            if (!filter_var($answer, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception('A valid email has not been entered');
            }
            return $answer;
        });
        $email = $helper->ask($input, $output, $question4);

        //password
        $question5 = new Question('Please enter the password: ');
        $question5->setValidator(function ($answer) {
            if (trim($answer) == '') {
                throw new \Exception('The password cannot be empty');
            }
            return $answer;
        });
        $question5->setHidden(true);
        $password = $helper->ask($input, $output, $question5);

        //User creation
        $output->writeln('Creating user...');
        try {
            $user = new User();
            $encodedPassword = $this->encoder->encodePassword($user, $password);

            $user->setUsername($username);
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail($email);
            $user->setPassword($encodedPassword);
            $user->setRole('ROLE_ADMIN');

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $output->writeln('User created correctly');
            $output->writeln('You can now log in with the user "' . $username . '" and the password established');
        } catch (\Exception $exception) {
            $output->writeln('ERROR: ' . $exception->getMessage());
        }
    }
}
