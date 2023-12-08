<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/authors', name: 'authors')]
class AuthorController extends AbstractController
{

    public function __construct(
        private AuthorRepository $repo,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'authors' => $this->repo->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($author);
            $this->em->flush();

            $this->addFlash('success', 'author créé avec succès');

            return $this->redirectToRoute('authors.index');
        }

        return $this->render('author/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['GET','POST'])]
    public function delete(?Author $author, Request $request): RedirectResponse
    {
        if (!$author instanceof Author) {
            $this->addFlash('error', 'author non trouvé');


            return $this->redirectToRoute('authors.index');
        }

        $this->em->remove($author);
        $this->em->flush();
        $this->addFlash('success', 'author supprimé avec succès');

        return $this->redirectToRoute('authors.index');
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?Author $author, Request $request): Response|RedirectResponse
    {
        if (!$author instanceof Author) {
            $this->addFlash('error', 'author non trouvé');

            return $this->redirectToRoute('authors.index');
        }

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($author);
            $this->em->flush();
            $this->addFlash('success', 'author mis à jour avec succès');

            return $this->redirectToRoute('authors.index');
        }

        return $this->render('author/update.html.twig', [
            'form' => $form,
            'author' => $author,
        ]);
    }

}
