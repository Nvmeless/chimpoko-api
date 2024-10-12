<?php

namespace App\Controller;

use App\Entity\Chimpokomon;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ChimpokomonRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/chimpokomon')]
class ChimpokomonController extends AbstractController
{
    #[Route(name: 'app_chimpokomon__index', methods: ['GET'])]
    public function index(ChimpokomonRepository $chimpokomonRepository, SerializerInterface $serializer): JsonResponse
    {
        $listChimpokomon = $chimpokomonRepository->findAll();

        $jsonChimpokomon = $serializer->serialize($listChimpokomon, 'json', ["groups" => "getChimpokomon"]);


        return new JsonResponse($jsonChimpokomon, Response::HTTP_OK, [], true);
    }

    #[Route(name: 'app_chimpokomon_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $newChimpokomon = $serializer->deserialize($request->getContent(), Chimpokomon::class, 'json', ["groups" => "getChimpokomon"]);
        $newChimpokomon->setStatus("on");
        $newChimpokomon->setCreatedAt(new DateTime());
        $newChimpokomon->setUpdatedAt(new DateTime());
        $entityManager->persist($newChimpokomon);

        $entityManager->flush();

        $jsonNewChimpokomon = $serializer->serialize($newChimpokomon, 'json', ["groups" => "getChimpokomon"]);

        $location = $urlGenerator->generate('app_chimpokomon_show', ["id" => $newChimpokomon->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonNewChimpokomon, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    #[Route('/{id}', name: 'app_chimpokomon_show', methods: ['GET'])]
    public function show(Chimpokomon $chimpokomon, SerializerInterface $serializer): JsonResponse
    {
        $jsonChimpokomon = $serializer->serialize($chimpokomon, 'json', ["groups" => "getChimpokomon"]);
        return new JsonResponse($jsonChimpokomon, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'app_chimpokomon_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Chimpokomon $chimpokomon, SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $updatedChimpokomon = $serializer->deserialize($request->getContent(), Chimpokomon::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $chimpokomon]);
        $updatedChimpokomon->setStatus("on");
        $updatedChimpokomon->setUpdatedAt(new DateTime());
        $entityManager->persist($updatedChimpokomon);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}', name: 'app_chimpokomon_delete', methods: ['DELETE'])]
    public function delete(Request $request, Chimpokomon $chimpokomon, EntityManagerInterface $entityManager): Response
    {

        if (isset($request->toArray()["force"]) && $request->toArray()["force"] === true) {
            $entityManager->remove($chimpokomon);
            $entityManager->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        $chimpokomon->setStatus("off");
        $chimpokomon->setUpdatedAt(new DateTime());
        $entityManager->persist($chimpokomon);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
