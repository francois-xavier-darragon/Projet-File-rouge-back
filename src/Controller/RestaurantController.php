<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use DateTimeImmutable;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/restaurant', name: 'app_api_restaurant_')]
class RestaurantController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializerInterface,
        private RestaurantRepository $restaurantRepository,
        private UrlGeneratorInterface $urlGeneratorInterface,
    ){}

    /** @OA\Post(
     *     path="/api/restaurant",
     *     summary="Créer un restaurant",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données du restaurant à créer",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Nom du restaurant"),
     *             @OA\Property(property="description", type="string", example="Description du restaurant")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Restaurant créé avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Nom du restaurant"),
     *             @OA\Property(property="description", type="string", example="Description du restaurant"),
     *             @OA\Property(property="createdAt", type="string", format="date-time")
     *         )
     *     )
     * )
     */
    #[Route(methods: 'POST')]
    public function new(Request $request): JsonResponse
    {

        $restaurant = $this->serializerInterface->deserialize($request->getContent(), Restaurant::class, 'json');
        $restaurant->setCreatedAt(new DateTimeImmutable());
        
        $this->restaurantRepository->save($restaurant);

        $responseData = $this->serializerInterface->serialize($restaurant, 'json');
        $location = $this->urlGeneratorInterface->generate(
            'app_api_restaurant_show',
            [
                'id' => $restaurant->getId()
            ],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );

            return new JsonResponse($responseData, Response::HTTP_CREATED, 
            [
                'location'=> $location,
               
            ], true
        );
    }

    /** @OA\Get(
     *     path="/api/restaurant/{id}",
     *     summary="Afficher un restaurant par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du restaurant à afficher",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Restaurant trouvé avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Nom du restaurant"),
     *             @OA\Property(property="description", type="string", example="Description du restaurant"),
     *             @OA\Property(property="createdAt", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Restaurant non trouvé"
     *     )
     * )
     */
    #[Route('/{id}/show', name: 'show', methods: 'GET')]
    public function show(int $id): JsonResponse
    {
        $restaurant = $this->restaurantRepository->findOneBy(['id' => $id]);
        if ($restaurant) {
            $responseData = $this->serializerInterface->serialize($restaurant, 'json');

                return new JsonResponse($responseData, Response::HTTP_OK,
                [],
                true
            );
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    /** @OA\Put(
     *     path="/api/restaurant/{id}",
     *     summary="Modifier un restaurant par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du restaurant à modifier",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Nouvelles données du restaurant à mettre à jour",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Nouveau nom du restaurant"),
     *             @OA\Property(property="description", type="string", example="Nouvelle description du restaurant")
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Restaurant modifié avec succès"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Restaurant non trouvé"
     *     )
     * )
     */
    #[Route('/{id}/edit', name: 'edit', methods: 'PUT')]
    public function edit(int $id, Request $request): JsonResponse
    {
        $restaurant = $this->restaurantRepository->findOneBy(['id' => $id]);
        if ($restaurant) {
            $restaurant = $this->serializerInterface->deserialize($request->getContent(), Restaurant::class, 'json',
                [
                    AbstractNormalizer::OBJECT_TO_POPULATE => $restaurant
                ]
            );
            $restaurant->setUpdatedAt(new DateTimeImmutable());

            $this->restaurantRepository->save($restaurant);

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    /** @OA\Delete(
     *     path="/api/restaurant/{id}",
     *     summary="Supprimer un restaurant par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du restaurant à supprimer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Restaurant supprimé avec succès"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Restaurant non trouvé"
     *     )
     * )
     */
    #[Route('/{id}/delete', name: 'delete', methods: 'DELETE')]
    public function delete(int $id): JsonResponse
    {
        $restaurant = $this->restaurantRepository->findOneBy(['id' => $id]);
        if ($restaurant) {
            $this->restaurantRepository->save($restaurant);

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
