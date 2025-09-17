<?php

namespace App\Controller;

use App\Entity\Version;
use App\Form\VersionType;
use App\Repository\VersionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/version')]
final class VersionController extends AbstractController
{
    #[Route(name: 'app_version_index', methods: ['GET'])]
    public function index(VersionRepository $versionRepository): Response
    {
        return $this->render('version/index.html.twig', [
            'versions' => $versionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_version_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $version = new Version();
        $form = $this->createForm(VersionType::class, $version);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($version);
            $entityManager->flush();

            return $this->redirectToRoute('app_version_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('version/new.html.twig', [
            'version' => $version,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_version_show', methods: ['GET'])]
    public function show(Version $version): Response
    {
        return $this->render('version/show.html.twig', [
            'version' => $version,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_version_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Version $version, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VersionType::class, $version);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_version_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('version/edit.html.twig', [
            'version' => $version,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_version_delete', methods: ['POST'])]
    public function delete(Request $request, Version $version, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$version->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($version);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_version_index', [], Response::HTTP_SEE_OTHER);
    }
}
