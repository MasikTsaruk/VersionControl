<?php

namespace App\Controller;

use App\Repository\AppRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
final class IndexController extends AbstractController
{
    #[Route(path: '{uuid}/', name: 'get_app_info', methods : ['GET'])]
    public function index_page(string $uuid, LoggerInterface $logger, AppRepository $appRepository): Response
    {
        $app = $appRepository->findOneBy(['uuid' => $uuid]);
        $envs = $app->getEnvs();
        $logger->info($envs[0]->getVersions()->last()->getVersion());

        return $this->render('index/index.html.twig', ['app' => $app]);
    }
}
