<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Entity\BlogPost;
use App\Entity\AuthoredEntityInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        'get' => [
            "normalization_context" => [
                "groups" => ["get-comment-with-author"],
            ]
        ],
        'post' => ["access_control" => "is_granted('ROLE_COMMENTATOR')"],
        'api_blog_posts_comments_get_subresource' => [
            "normalizationContext" => ['groups' => ['get-comment-with-author']],
        ],
    ],
    itemOperations: [
        'get' => [
            "normalization_context" => [
                "groups" => ["get-comment-with-author"],
            ]
        ],
        'put' => ["access_control" => "is_granted(ROLE_EDITOR) or (is_granted('ROLE_COMMENTATOR') and object.getAuthor() == user)"]
    ],
    attributes: [
        'order' => ['published' => 'DESC']
    ],
    denormalizationContext: [
        'groups' => [
            'post'
        ]
    ]
),
    ApiFilter(
        SearchFilter::class,
        properties: [
            'id' => 'exact',
            'content' => 'partial',
            'author' => 'partial'
        ]
    )
]
class Comment implements AuthoredEntityInterface, PublishedDateEntityInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    #[
        Groups(['post', 'get-comment-with-author']),
        Assert\NotBlank,
        Assert\Length(min:5, max:3000)
    ]
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    #[
        Groups(['get-comment-with-author']),
    ]
    private $published;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    #[
        Groups(['get-comment-with-author'])
    ]
    private $author;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BlogPost", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    #[
        Groups(['get-comment-with-author']),
    ]
    private BlogPost $blogPost;

    /**
     * @return BlogPost
     */
    public function getBlogPost(): BlogPost
    {
        return $this->blogPost;
    }

    /**
     * @param mixed $blogPost
     */
    public function setBlogPost(BlogPost $blogPost): self
    {
        $this->blogPost = $blogPost;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): PublishedDateEntityInterface
    {
        $this->published = $published;

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
     * @return Comment
     */
    public function setAuthor(UserInterface $author): AuthoredEntityInterface
    {
        $this->author = $author;

        return $this;
    }

}
