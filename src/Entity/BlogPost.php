<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\BlogPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Image;
use App\Entity\AuthoredEntityInterface;

/**
 * @ORM\Entity(repositoryClass=BlogPostRepository::class)
 */
#[
    ApiResource(
        collectionOperations: [
            'get' => ["access_control" => "is_granted('IS_AUTHENTICATED_FULLY')"],
            'post' => ["access_control" => "is_granted('IS_AUTHENTICATED_FULLY')"]
        ],
        itemOperations: [
            'get',
            'put' => ["access_control" => "is_granted('IS_AUTHENTICATED_FULLY') and object.getAuthor() == user"]
        ],
        denormalizationContext: [
            'groups' => [
                'post'
            ]
        ]
    )
]
class BlogPost implements AuthoredEntityInterface, PublishedDateEntityInterface
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
        Assert\Length(min: 10),
        Groups(['post'])
    ]
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    #[
        Groups(['post'])
    ]
    private $published;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    #[
        Assert\NotBlank,
        Assert\Length(min: 20),
        Groups(['post'])
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
        Assert\NotBlank,
        Groups(['post'])
    ]
    private $slug;


    /**
     * @ORM\ManyToMany(targetEntity="Image")
     * @ORM\JoinTable()
     * @ApiSubResource()
     */
    #[
        Assert\NotBlank,
        Groups(['post'])
    ]
    private $images;


    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->images = new ArrayCollection();
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

    public function setPublished(\DateTimeInterface $published): PublishedDateEntityInterface
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
     * @param UserInterface $author
     * @return $this
     */
    public function setAuthor(UserInterface $author): AuthoredEntityInterface
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * @param Image $image
     * @return $this
     */
    public function setImage(Image $image)
    {
        $this->images->add($image);

        return $this;
    }


    /**
     * @param Image $image
     * @return $this
     */
    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);

        return $this;
    }
}
