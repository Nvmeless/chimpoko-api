<?php

namespace App\Controller;

use App\Entity\Chimpokodex;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ChimpokodexRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/chimpokodex')]
class ChimpokodexController extends AbstractController
{
    #[Route(name: 'app_chimpokodex__index', methods: ['GET'])]
    public function index(ChimpokodexRepository $chimpokodexRepository, SerializerInterface $serializer): JsonResponse
    {
        $listChimpokodex = $chimpokodexRepository->findAll();

        $jsonChimpokodex = $serializer->serialize($listChimpokodex, 'json', ["groups" => "getChimpokodex"]);


        return new JsonResponse($jsonChimpokodex, Response::HTTP_OK, [], true);
    }

    #[Route(name: 'app_chimpokodex_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $newChimpokodex = $serializer->deserialize($request->getContent(), Chimpokodex::class, 'json', ["groups" => "getChimpokodex"]);
        $newChimpokodex->setStatus("on");
        $newChimpokodex->setCreatedAt(new DateTime());
        $newChimpokodex->setUpdatedAt(new DateTime());
        $entityManager->persist($newChimpokodex);

        $entityManager->flush();

        $jsonNewChimpokodex = $serializer->serialize($newChimpokodex, 'json', ["groups" => "getChimpokodex"]);

        $location = $urlGenerator->generate('app_chimpokodex_show', ["id" => $newChimpokodex->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonNewChimpokodex, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    #[Route('/{id}', name: 'app_chimpokodex_show', methods: ['GET'])]
    public function show(Chimpokodex $chimpokodex, SerializerInterface $serializer): JsonResponse
    {
        $jsonChimpokodex = $serializer->serialize($chimpokodex, 'json', ["groups" => "getChimpokodex"]);
        return new JsonResponse($jsonChimpokodex, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'app_chimpokodex_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Chimpokodex $chimpokodex, SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $updatedChimpokodex = $serializer->deserialize($request->getContent(), Chimpokodex::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $chimpokodex]);
        $updatedChimpokodex->setStatus("on");
        $updatedChimpokodex->setUpdatedAt(new DateTime());
        $entityManager->persist($updatedChimpokodex);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}', name: 'app_chimpokodex_delete', methods: ['DELETE'])]
    public function delete(Request $request, Chimpokodex $chimpokodex, EntityManagerInterface $entityManager): Response
    {

        if (isset($request->toArray()["force"]) && $request->toArray()["force"] === true) {
            $entityManager->remove($chimpokodex);
            $entityManager->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        $chimpokodex->setStatus("off");
        $chimpokodex->setUpdatedAt(new DateTime());
        $entityManager->persist($chimpokodex);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
