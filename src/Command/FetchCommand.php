<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface as MailerTransportExceptionInterface;

#[AsCommand(
    name: 'fruit:fetch',
    description: 'Getting all fruits from https://fruityvice.com/',
    hidden: false
)]
class FetchCommand extends Command
{
    protected HttpClientInterface $client;
    protected SerializerInterface $serializer;
    protected EntityManagerInterface $entityManager;
    protected MailerInterface $mailer;


    const DATASOURCE = 'https://fruityvice.com/api/fruit/all';

    public function __construct(HttpClientInterface $client, SerializerInterface $serializer, EntityManagerInterface $entityManager, MailerInterface $mailer, string $name = null)
    {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;

        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $response = $this->client->request('GET', self::DATASOURCE);

            $fruitData = ($response->getContent());
        } catch (TransportExceptionInterface|ExceptionInterface $exception) {
            $output->writeln($exception->getMessage());
            return Command::FAILURE;
        }

        $serializer = new Serializer(
            [new GetSetMethodNormalizer(), new ArrayDenormalizer()],
            [new JsonEncoder()]
        );

        $fruits = $serializer->deserialize($fruitData, 'App\Entity\Fruit[]', 'json');

        foreach ($fruits as $fruit) {
            $this->entityManager->persist($fruit);
        }

        $this->entityManager->flush();
        $output->writeln(count($fruits) . " successfully added to DB");

        $email = (new Email())
            ->from('test_task@test.com')
            ->to('vls.svyat@gmail.com')
            ->subject('Fruit are fetched')
            ->text(count($fruits) . " successfully added to DB");
        try {
            $this->mailer->send($email);
            $output->writeln("Email was successfully sent");

        } catch (MailerTransportExceptionInterface $e) {
            $output->writeln("Email can`t be sent\n". $e->getMessage());
        }


        return Command::SUCCESS;
    }
}
