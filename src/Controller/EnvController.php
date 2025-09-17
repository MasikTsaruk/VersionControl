<?php

namespace App\Controller;

use App\Entity\Env;
use App\Form\EnvType;
use App\Repository\EnvRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/env')]
final class EnvController extends AbstractController
{
    #[Route(name: 'app_env_index', methods: ['GET'])]
    public function index(EnvRepository $envRepository): Response
    {
        return $this->render('env/index.html.twig', [
            'envs' => $envRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_env_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $env = new Env();
        $form = $this->createForm(EnvType::class, $env);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($env);
            $entityManager->flush();

            return $this->redirectToRoute('app_env_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('env/new.html.twig', [
            'env' => $env,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_env_show', methods: ['GET'])]
    public function show(Env $env): Response
    {
        return $this->render('env/show.html.twig', [
            'env' => $env,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_env_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Env $env, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EnvType::class, $env);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_env_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('env/edit.html.twig', [
            'env' => $env,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_env_delete', methods: ['POST'])]
    public function delete(Request $request, Env $env, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$env->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($env);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_env_index', [], Response::HTTP_SEE_OTHER);
    }
}
