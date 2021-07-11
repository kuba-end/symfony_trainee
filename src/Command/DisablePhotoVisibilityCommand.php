<?php

namespace App\Command;

use App\Entity\Photo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DisablePhotoVisibilityCommand extends Command
{
    protected static $defaultName = 'app:photo-visible-false';

    private object $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Set all photo of users to private')
            ->addArgument('user',InputArgument::REQUIRED,'User ID required.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->entityManager;
        $photoRepository = $em->getRepository(Photo::class);
        $photosToSetPrivate = $photoRepository->findBy([
            'is_public' => 1,
            'user' => $input->getArgument('user')
            ]);
        foreach ($photosToSetPrivate as $publicPhoto){
            $publicPhoto->setIsPublic(0);
            $em->persist($publicPhoto);
            $em->flush();
        }
        return 0;
    }
}