<?php

namespace App\Controller;

use App\Entity\Pet;
use App\Form\PetType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PetController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/pets', name: 'app_pets_index')]
    public function index(): Response
    {
        $pets = $this->em->getRepository(Pet::class)->findAll();

        return $this->render('pet/index.html.twig', [
            'pets' => $pets,
        ]);
    }

    #[Route('/pet/found', name: 'app_pet_found')]
    public function found(Request $request, SluggerInterface $slugger): Response
    {
        $pet = new Pet();

        $form = $this->createForm(PetType::class, $pet);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pet = $form->getData();
            // added created date
            $pet->setCreatedAt(new DateTimeImmutable());
            $pet->setStatus("found"); // found or reported ??
            // setup as "found" in status, but we need diferent route for this

            if ($file = $form->get('photo')->getData()) {
                $originalFile = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFile);
                $newFilename = $safeFilename . '-' . uniqid() . "." . $file->guessExtension();

                try {
                    $file->move($this->getParameter('files_directory'), $newFilename);
                } catch (FileException $e) {
                    throw new Exception("Upss algo fallo");
                }

                $pet->setPhoto($newFilename);
            }

            // persist the entity pet
            $this->em->persist($pet);
            $this->em->flush();

            return $this->redirectToRoute('app_pets_index');
        }

        return $this->render('pet/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/pet/lost', name: 'app_pet_lost')]
    public function lost(Request $request): Response
    {
        $pet = new Pet();

        $form = $this->createForm(PetType::class, $pet);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pet = $form->getData();
            // added created date
            $pet->setCreatedAt(new DateTimeImmutable());
            $pet->setStatus("lost"); // found ??
            // setup as "found" in status, but we need diferent route for this
            $pet->setPhoto("foto.png");
            // check file and uploaded
            // persist the entity pet
            $this->em->persist($pet);
            $this->em->flush();

            return $this->redirectToRoute('app_pets_index');
        }

        return $this->render('pet/form.html.twig', [
            'form' => $form,
        ]);
    }
}
