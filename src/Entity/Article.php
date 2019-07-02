<?php

namespace App\Entity;

use App\Service\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @UniqueEntity("title", message="Ce titre existe déjà")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ ne peut pas rester vide")
     * @Assert\Length(max=255, maxMessage="Le titre est trop long")
     */

    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Ce champ ne peut pas rester vide")
     * @Assert\Regex(
     *     pattern="[digital]",
     *     match=false,
     *     message="en français, il faut dire numérique")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", mappedBy="articles")
     */
    private $tags;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="favorites")
     */
    private $user_favorite;


    public function __toString()
    {

        return $this->title;
    }

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->user_favorite = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /*  public function getString(): ?string
      {
          return $this->string();
      }

      public function setString(string $string): self
      {
          $res = $this->string();

          return $res;
      } */

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUrl(): string
    {
        return preg_replace('/ /', '-', strtolower($this->getTitle()));

    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addArticle($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeArticle($this);
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getUserFavorite(): ?User
    {
        return $this->user_favorite;
    }

    public function setUserFavorite(?User $user_favorite): self
    {
        $this->user_favorite = $user_favorite;

        return $this;
    }

    public function addUserFavorite(User $userFavorite): self
    {
        if (!$this->user_favorite->contains($userFavorite)) {
            $this->user_favorite[] = $userFavorite;
            $userFavorite->addFavorite($this);
        }

        return $this;
    }

    public function removeUserFavorite(User $userFavorite): self
    {
        if ($this->user_favorite->contains($userFavorite)) {
            $this->user_favorite->removeElement($userFavorite);
            $userFavorite->removeFavorite($this);
        }

        return $this;
    }
}
