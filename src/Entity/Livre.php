<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivreRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=LivreRepository::class)
 * @ApiResource(
 *    attributes={
 *       "order"={
 *          "titre":"ASC"
 *           }
 * },
 *    collectionOperations={
 *          "get_coll_role_adherent"={
 *             "method"="GET",
 *             "path"="/livres",
 *             "normalization_context"={
 *                "groups"={"get_role_adherent"}
 *                }
 *             },
 * 
 *          "post"={
 *             "method"="POST",
 *             "path"="/manager/livres",
 *             "access_control"="is_granted('ROLE_MANAGER')",
 *             "access_control_message"="Vous n'avez pas les droits"
 *             },
 *       },
 * 
 *        itemOperations={
 *          "get_item_role_adherent"={
 *             "method"="GET",
 *             "path"="/adherent/livres/{id}",
 *             "normalization_context"={
 *                "groups"={"get_role_adherent"}
 *                }
 *             },
 * 
 *         "get_item_role_manager"={
 *             "method"="GET",
 *             "path"="/manager/livres/{id}",
 *             "access_control"="is_granted('ROLE_MANAGER')",
 *             "access_control_message"="Vous n'avez pas les droits"
 *             },
 * 
 *          "put_item_role_manager"={
 *             "method"="PUT",
 *             "path"="/manager/livres/{id}",
 *             "access_control"="is_granted('ROLE_MANAGER')",
 *             "access_control_message"="Vous n'avez pas les droits",
 *             "denormalization_context"={
 *                "groups"={"put_manager_livre"}
 *               }
 *          },
 * 
 *          "put_item_role_admin"={
 *             "method"="PUT",
 *             "path"="/admin/livres/{id}",
 *             "access_control"="is_granted('ROLE_ADMIN')",
 *             "access_control_message"="Vous n'avez pas les droits"
 *             },
 * 
 *          "delete_item_role_admin"={
 *             "method"="DELETE",
 *             "path"="/admin/livres/{id}",
 *             "access_control"="is_granted('ROLE_ADMIN')",
 *             "access_control_message"="Vous n'avez pas les droits"
 *             }
 *       }
 * 
 *   )
 * @ApiFilter(
 *    SearchFilter::class,
 *    properties={
 *       "titre":"ipartial",
 *       "auteur":"exact",
 *       "genres":"exact"
 *    }
 * )
 * )
 *  @ApiFilter(
 *    OrderFilter::class,
 *    properties={
 *       "titre",
 *       "prix",
 *       "auteur.nom"
 *    }
 * )
 *  @ApiFilter(
 *    PropertyFilter::class,
 *    arguments={
 *       "parameterName":"filter",
 *       "overrideDefaultProperties":false,
 *       "whitelist"={
 *             "isbn",
 *             "titre",
 *             "prix",
 *       }
 *    }
 * )
 */
class Livre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_role_adherent", "put_manager_livre"})
     */
    private $isbn;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_role_adherent", "put_manager_livre"})
     */
    private $titre;

    /**
     * @ORM\Column(type="float")
     * @Groups({"get_role_manager"})
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_role_adherent", "put_manager_livre"})
     */
    private $genre;

    /**
     * @ORM\ManyToOne(targetEntity=Editeur::class, inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_role_adherent", "put_manager_livre"})
     */
    private $editeur;

    /**
     * @ORM\ManyToOne(targetEntity=Auteur::class, inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_role_adherent", "put_manager_livre"})
     */
    private $auteur;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"get_role_adherent", "put_manager_livre"})
     */
    private $annee;

    /**
     * @ORM\OneToMany(targetEntity=Pret::class, mappedBy="livre")
     * @Groups({"get_role_manager"})
     */
    private $prets;

    public function __construct()
    {
        $this->prets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getEditeur(): ?Editeur
    {
        return $this->editeur;
    }

    public function setEditeur(?Editeur $editeur): self
    {
        $this->editeur = $editeur;

        return $this;
    }

    public function getAuteur(): ?Auteur
    {
        return $this->auteur;
    }

    public function setAuteur(?Auteur $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * @return Collection|Pret[]
     */
    public function getPrets(): Collection
    {
        return $this->prets;
    }

    public function addPret(Pret $pret): self
    {
        if (!$this->prets->contains($pret)) {
            $this->prets[] = $pret;
            $pret->setLivre($this);
        }

        return $this;
    }

    public function removePret(Pret $pret): self
    {
        if ($this->prets->removeElement($pret)) {
            // set the owning side to null (unless already changed)
            if ($pret->getLivre() === $this) {
                $pret->setLivre(null);
            }
        }

        return $this;
    }
}
