<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\BookType;
use Symfony\Component\HttpFoundation\RedirectResponse;




#[Route('/books', name: 'books')]
class BookController extends AbstractController
{
    public function __construct(
        private BookRepository $repo,
        private EntityManagerInterface $em
    ) {
    }
    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $this->repo->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($book);
            $this->em->flush();
            $this->addFlash('success', 'book créé avec succès');
            return $this->redirectToRoute('books.index');
        }

        return $this->render('book/create.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/{id}/delete', name: '.delete', methods: ['POST', 'GET'])]
    public function delete(?Book $book): RedirectResponse
    {
        if (!$book instanceof Book) {
            $this->addFlash('error', 'book non trouvé');

            return $this->redirectToRoute('books.index');
        }

        $this->em->remove($book);
        $this->em->flush();
        $this->addFlash('success', 'book supprimé avec succès');

        return $this->redirectToRoute('books.index');
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?Book $book, Request $request): Response|RedirectResponse
    {
        if (!$book instanceof Book) {
            $this->addFlash('error', 'book non trouvé');

            return $this->redirectToRoute('books.index');
        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($book);
            $this->em->flush();
            $this->addFlash('success', 'book modifié avec succès');

            return $this->redirectToRoute('books.index');
        }

        return $this->render('book/update.html.twig', [
            'form' => $form,
            'book' => $book,
        ]);
    }

}
