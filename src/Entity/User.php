<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 *
 */
#[
    ORM\UniqueConstraint(fields: ['email', 'username']),
    UniqueEntity(
      fields: ['email'],
      message: 'This email already exists'
    ),
    UniqueEntity(
        fields: ['username'],
        message: 'This username already exists'
    ),
    ApiResource(
    collectionOperations: [
        "get" => [
            "access_control" => "is_granted('IS_AUTHENTICATED_FULLY')"
        ],
        'post' => [
            "denormalization_context" => ["groups" => ["post"]],
            "normalization_context" => ['groups' => ['get']]
        ]
    ],
    itemOperations: [
        'get' => [
            "access_control" => "is_granted('IS_AUTHENTICATED_FULLY')",
            "normalization_context" => ["get"]
            ],
        'put' => [
            "access_control" => "is_granted('IS_AUTHENTICATED_FULLY') and object == user",
            "denormalization_context" => ["groups" => ["put"]],
            "normalization_context" => ["groups" => ["get"]]
        ]
    ],
    normalizationContext: ['groups' => ['get']]
)]
class User implements UserInterface
{
    const ROLE_COMMENTATOR = 'ROLE_COMMENTATOR';
    const ROLE_WRITER = 'ROLE_WRITER';
    const ROLE_EDITOR = 'ROLE_EDITOR';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SUPERADMIN = 'ROLE_SUPERADMIN';

    const DEFAULT_ROLES = [self::ROLE_COMMENTATOR];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[
        Groups(['get'])
    ]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Groups(['put', 'get', 'post', 'get-comment-with-author', 'get-blog-post-with-author']),
        Assert\NotBlank
    ]
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Groups(['put', 'post']),
        Assert\NotBlank,
    ]
    private $password;

    /**
     * @Assert\Expression(
     *     expression="this.getPassword() === this.getRetypedPassword()",
     *     message="Passwords do not match."
     * )
     */
    #[
        Groups(["put", "post"]),
        Assert\NotBlank,
        ]
    private $retypedPassword;

    /**
     * @return mixed
     */
    public function getRetypedPassword()
    {
        return $this->retypedPassword;
    }

    /**
     * @param mixed $retypedPassword
     */
    public function setRetypedPassword(string $retypedPassword): void
    {
        $this->retypedPassword = $retypedPassword;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Groups(["put", "post"]),
        Assert\NotBlank,
        Assert\Email,
        ]
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BlogPost", mappedBy="author")
     */
    private $posts;

    /**
     * @ORM\OnetoMany(targetEntity="App\Entity\Comment", mappedBy="author")
     */
    #[
        Groups(['read'])
    ]
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[
        Groups(['get', 'put', 'post', 'get-comment-with-author', 'get-blog-post-with-author']),
        Assert\NotBlank
    ]
    private $name;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->roles = self::DEFAULT_ROLES;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * @return Collection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @ORM\Column(type="simple_array", length=200)
     */
    private $roles;


    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored in a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return string[] The user roles
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Returns the salt that was originally used to hash the password.
     *
     * This can return null if the password was not hashed using a salt.
     *
     * This method is deprecated since Symfony 5.3, implement it from {@link LegacyPasswordAuthenticatedUserInterface} instead.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }
}
