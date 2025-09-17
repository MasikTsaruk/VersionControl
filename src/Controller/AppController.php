<?php

namespace App\Controller;

use App\Entity\App;
use App\Form\AppType;
use App\Repository\AppRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app')]
final class AppController extends AbstractController
{
    #[Route(name: 'app_app_index', methods: ['GET'])]
    public function index(AppRepository $appRepository): Response
    {
        return $this->render('app/index.html.twig', [
            'apps' => $appRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_app_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $app = new App();
        $form = $this->createForm(AppType::class, $app);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($app);
            $entityManager->flush();

            return $this->redirectToRoute('app_app_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('app/new.html.twig', [
            'app' => $app,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_app_show', methods: ['GET'])]
    public function show(App $app): Response
    {
        return $this->render('app/show.html.twig', [
            'app' => $app,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_app_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, App $app, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AppType::class, $app);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_app_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('app/edit.html.twig', [
            'app' => $app,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_app_delete', methods: ['POST'])]
    public function delete(Request $request, App $app, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$app->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($app);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_app_index', [], Response::HTTP_SEE_OTHER);
    }
}
