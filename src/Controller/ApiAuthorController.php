<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Entity\Nationalite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class ApiAuthorController extends AbstractController
{
   protected $entity;

   public function __construct(EntityManagerInterface $entity){
      $this->entity = $entity;
   }

    /**
     * @Route("/api/auteurs", name="api_auteurs_index", methods="GET")
     */
    public function index(SerializerInterface $serializer): Response
    {
      $auteurs = $this->entity->getRepository(Auteur::class)->findAll();
      $json = $serializer->serialize($auteurs, 'json', ['groups' => 'auteursList']);

      return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

   /**
     * @Route("/api/auteurs/{id}", name="api_auteurs_show", methods="GET")
     */
    public function show(SerializerInterface $serializer, Auteur $auteur): Response
    {
      $json = $serializer->serialize($auteur, 'json', ['groups' => 'auteursList']);

      return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

       /**
     * @Route("/api/auteurs", name="api_auteurs_create", methods="POST")
     */
    public function create(ValidatorInterface $validator, Request $request, SerializerInterface $serializer): Response
    {
      $data = $request->getContent();
      $auteur = $serializer->deserialize($data, Auteur::class, 'json');

      $errors = $validator->validate($auteur);

      if (count($errors) > 0) {
         $errorsJson = $serializer->serialize($errors, 'json');;
         return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST);
     }

      $this->entity->persist($auteur);
      $this->entity->flush();

      return new JsonResponse('Auteur crée', Response::HTTP_CREATED, [
         "location" => $this->generateUrl('api_auteurs_show', ['id' => $auteur->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
      ], true);
    }


       /**
     * @Route("/api/auteurs/{id}", name="api_auteurs_update", methods="PUT")
     */
    public function update(ValidatorInterface $validator, Request $request, SerializerInterface $serializer, Auteur $auteur): Response
    {
      $data = $request->getContent();
      $data = $serializer->decode($data, 'json');

      $nationalite = $this->entity->getRepository(Nationalite::class)->find($data['nationalite']['id']);
      
      $auteur = $serializer->deserialize($data['auteur'], Auteur::class, 'json', ['object_to_populate' => $auteur]);
      $auteur->setNationalite($nationalite);
      $errors = $validator->validate($auteur);

      if (count($errors) > 0) {
          $errorsJson = $serializer->serialize($errors, 'json');;
          return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST);
      }

      $this->entity->flush();

      return new JsonResponse('Auteur modifié', Response::HTTP_OK, [
         "location" => $this->generateUrl('api_auteurs_show', ['id' => $auteur->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
      ], true);
    }

           /**
     * @Route("/api/auteurs/{id}", name="api_auteurs_delete", methods="DELETE")
     */
    public function delete(Auteur $auteur): Response
    {
      $this->entity->remove($auteur);
      $this->entity->flush();

      return new JsonResponse('Auteur supprimé', Response::HTTP_OK, []);
    }
}
