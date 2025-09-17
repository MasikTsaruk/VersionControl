<?php

namespace App\Controller;

use App\Entity\Version;
use App\Repository\AppRepository;
use App\Repository\EnvRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;

#[Route('/api')]
final class ApiController extends AbstractController
{
    #[Route('/env/{uuid}/deployment', name:'deployment_post', methods: ['POST'])]
    public function deployment_post(Request $request, string $uuid, LoggerInterface $logger, EnvRepository $envRepository, EntityManagerInterface $em): JsonResponse
    {   
        $response = json_decode($request->getContent(), true);
        $logger->info($response['version']);
        $logger->info($uuid);
        $version = new Version();
        $env = $envRepository->findOneBy(['uuid' => $uuid]);
        $version->setVersion($response['version']);
        $version->setEnv($env);
        $version->setCreatedAt(new \DateTimeImmutable('now'));
        $logger->info($env->getName());
        $em->persist($version);
        $em->flush();
        return new JsonResponse($response);
    }

    #[Route('/{uuid}', name:'app_get', methods: ['GET'])]
    public function app_get(Request $request, string $uuid, AppRepository $appRepository, EntityManagerInterface $em, LoggerInterface $logger): JsonResponse
    {   
        $app = $appRepository->findOneBy(['uuid'=> $uuid]);
        $logger->info($app->getName());
        $envs = $app->getEnvs();
        return new JsonResponse($envs);
    }

}