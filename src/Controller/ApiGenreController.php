<?php

namespace App\Controller;

use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiGenreController extends AbstractController
{
   protected $entity;

   public function __construct(EntityManagerInterface $entity){
      $this->entity = $entity;
   }

    /**
     * @Route("/api/genres", name="api_genres_index", methods="GET")
     */
    public function index(SerializerInterface $serializer): Response
    {
      $genres = $this->entity->getRepository(Genre::class)->findAll();
      $json = $serializer->serialize($genres, 'json', ['groups' => 'genresList']);

      return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

   /**
     * @Route("/api/genres/{id}", name="api_genres_show", methods="GET")
     */
    public function show(SerializerInterface $serializer, Genre $genre): Response
    {
      $json = $serializer->serialize($genre, 'json', ['groups' => 'genresList']);

      return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}
