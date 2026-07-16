<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReviewController extends AbstractController
{
    #[Route('/new', name: 'review_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($review);
            $em->flush();

            $this->addFlash('success', 'Review saved!');

            return $this->redirectToRoute('review_new');
        }

        return $this->render('review/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/', name: 'review_index', methods: ['GET'])]
    public function index(Request $request, ReviewRepository $reviews): Response
    {
        $limit = 10;
        $page = max(1, $request->query->getInt('page', 1));

        $paginator = $reviews->paginate($page, $limit);
        $pages = max(1, (int) ceil(count($paginator) / $limit));

        return $this->render('review/index.html.twig', [
            'reviews' => $paginator,
            'currentPage' => $page,
            'pages' => $pages,
        ]);
    }

    #[Route('/{id}', name: 'review_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Review $review): Response
    {
        return $this->render('review/show.html.twig', [
            'review' => $review,
        ]);
    }
}
