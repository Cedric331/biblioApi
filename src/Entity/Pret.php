<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PretRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PretRepository::class)
 * @ApiResource(
 * itemOperations={
 *      "get"={
 *       "method"="GET",
 *       "path"="/pret/{id}",
 *       "access_control"="(is_granted('ROLE_ADHERENT') and object.getAdherent() == user) or is_granted('ROLE_MANAGER')",
 *       "access_control_message"="Accès refusé"
 *    },
 * "delete"={
 *       "method"="DELETE",
 *       "path"="/pret/{id}",
 *       "access_control"="(is_granted('ROLE_ADHERENT') and object.getAdherent() == user) or is_granted('ROLE_MANAGER')",
 *       "access_control_message"="Accès refusé"
 *    },
 *  "put"={
 *       "method"="PUT",
 *       "path"="/pret/{id}",
 *       "denormalization_context"={
 *                "groups"={"put_manager"}
 *               },
 *       "access_control"="(is_granted('ROLE_MANAGER')",
 *       "access_control_message"="Accès refusé"
 *    },
 * },
 *    collectionOperations={
 *          "get"={
 *             "method"="GET",
 *             "path"="/prets",
 *       "access_control"="is_granted('ROLE_MANAGER')",
 *       "access_control_message"="Accès refusé"
 *             }
 *       },
 )
 */
class Pret
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePret;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRetourPrevue;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("put_manager")
     */
    private $dateRetour;

    /**
     * @ORM\ManyToOne(targetEntity=Livre::class, inversedBy="prets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $livre;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="prets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $adherent;

    public function __construct()
    {
      $this->datePret = new \DateTime();
      $timestamp = date('Y-m-d H:m:s', strtotime("15 days",$this->getDatePret()->getTimestamp()));
      $this->dateRetourPrevue = DateTime::createFromFormat('Y-m-d H:i:s', $timestamp);
      $this->dateRetour = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePret(): ?\DateTimeInterface
    {
        return $this->datePret;
    }

    public function setDatePret(\DateTimeInterface $datePret): self
    {
        $this->datePret = $datePret;

        return $this;
    }

    public function getDateRetourPrevue(): ?\DateTimeInterface
    {
        return $this->dateRetourPrevue;
    }

    public function setDateRetourPrevue(\DateTimeInterface $dateRetourPrevue): self
    {
        $this->dateRetourPrevue = $dateRetourPrevue;

        return $this;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->dateRetour;
    }

    public function setDateRetour(?\DateTimeInterface $dateRetour): self
    {
        $this->dateRetour = $dateRetour;

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): self
    {
        $this->livre = $livre;

        return $this;
    }

    public function getAdherent(): ?Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(?Adherent $adherent): self
    {
        $this->adherent = $adherent;

        return $this;
    }
}
