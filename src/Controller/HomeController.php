<?php

namespace App\Controller;

use App\Entity\Pet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $pets = $this->em->getRepository(Pet::class)->findBy([], ['id' => 'DESC'], 4);

        return $this->render('home/index.html.twig', [
            'pets' => $pets,
        ]);
    }
}
