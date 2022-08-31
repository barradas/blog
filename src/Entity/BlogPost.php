<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BlogPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BlogPostRepository::class)
 */

#[
    ApiResource(
        collectionOperations: [
            'get' => ["access_control" => "is_granted('IS_AUTHENTICATED_FULLY')"],
            'post' => ["access_control" => "is_granted('IS_AUTHENTICATED_FULLY')"]],
        itemOperations: ['get'],
    )
]
class BlogPost
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Assert\NotBlank,
        Assert\Length(min: 10)
    ]
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    #[
        Assert\NotBlank,
    ]
    private $published;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    #[
        Assert\NotBlank,
        Assert\Length(min: 20)
    ]
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable="false")
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="blogPost")
     * @ORM\JoinColumn(nullable="false");
     */
    private $comments;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[
        Assert\NotBlank
    ]
    private $slug;


    public function __construct(){
        $this->comments = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): self
    {
        $this->author = $author;

        return $this;
    }
}
