<?php

namespace App\Controller;

use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

       /**
     * @Route("/api/genres", name="api_genres_create", methods="POST")
     */
    public function create(ValidatorInterface $validator, Request $request, SerializerInterface $serializer): Response
    {
      $data = $request->getContent();
      $genre = $serializer->deserialize($data, Genre::class, 'json');

      $errors = $validator->validate($genre);

      if (count($errors) > 0) {
         $errorsJson = $serializer->serialize($errors, 'json');;
         return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST);
     }

      $this->entity->persist($genre);
      $this->entity->flush();

      return new JsonResponse('Genre crée', Response::HTTP_CREATED, [
         "location" => $this->generateUrl('api_genres_show', ['id' => $genre->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
      ], true);
    }


       /**
     * @Route("/api/genres/{id}", name="api_genres_update", methods="PUT")
     */
    public function update(ValidatorInterface $validator, Request $request, SerializerInterface $serializer, Genre $genre): Response
    {
      $data = $request->getContent();
      $genre = $serializer->deserialize($data, Genre::class, 'json', ['object_to_populate' => $genre]);
      $errors = $validator->validate($genre);

      if (count($errors) > 0) {
          $errorsJson = $serializer->serialize($errors, 'json');;
          return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST);
      }

      $this->entity->flush();

      return new JsonResponse('Genre modifié', Response::HTTP_OK, [
         "location" => $this->generateUrl('api_genres_show', ['id' => $genre->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
      ], true);
    }

           /**
     * @Route("/api/genres/{id}", name="api_genres_delete", methods="DELETE")
     */
    public function delete(Genre $genre): Response
    {
      $this->entity->remove($genre);
      $this->entity->flush();

      return new JsonResponse('Genre supprimé', Response::HTTP_OK, []);
    }
}
