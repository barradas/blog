<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var \Faker\Factory
     */
    private $faker;

    private const USERS = [
        [
            'username' => 'admin',
            'name' => 'jane',
            'email' => 'admin@blog.com',
            'password' => 'secret123#',
            'roles' => [User::ROLE_SUPERADMIN],
        ],
        [
            'username' => 'john',
            'name' => 'john',
            'email' => 'john@blog.com',
            'password' => 'secret123#',
            'roles' => [User::ROLE_ADMIN],
        ],
        [
            'username' => 'barradas',
            'name' => 'barradas',
            'email' => 'barradas@blog.com',
            'password' => 'secret123#',
            'roles' => [User::ROLE_WRITER],
        ],
        [
            'username' => 'pestana',
            'name' => 'pestana',
            'email' => 'pestana@blog.com',
            'password' => 'secret123#',
            'roles' => [User::ROLE_WRITER],
        ],
        [
            'username' => 'tony',
            'name' => 'tony',
            'email' => 'tony@blog.com',
            'password' => 'secret123#',
            'roles' => [User::ROLE_EDITOR],
        ],
        [
            'username' => 'andy',
            'name' => 'andy',
            'email' => 'andy@blog.com',
            'password' => 'secret123#',
            'roles' => [User::ROLE_COMMENTATOR],
        ]
    ];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->passwordEncoder = $passwordEncoder;
        $this->faker = \Faker\Factory::create();
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
        $this->loadComments($manager);
    }

    public function loadComments(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; $i++) {
            for ($j = 0; $j < rand(1, 10); $j++) {
                $comment = new Comment();
                $authorReference = $this->getRandomUserReference($comment);
                $comment->setContent($this->faker->realText(200));
                $comment->setPublished($this->faker->dateTimeThisYear);
                $comment->setAuthor($authorReference);
                $comment->setBlogPost($this->getReference("blog_post_$i"));

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    public function loadBlogPosts(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; $i++) {
            $blogPost = new BlogPost();
            $authorReference = $this->getRandomUserReference($blogPost);
            $blogPost->setTitle($this->faker->realText(30));
            $blogPost->setPublished($this->faker->dateTimeThisYear);
            $blogPost->setContent($this->faker->realText());
            $blogPost->setAuthor($authorReference);
            $blogPost->setSlug("blog_post_$i");
            $this->setReference("blog_post_$i", $blogPost);

            $manager->persist($blogPost);
        }

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {

        foreach (self::USERS as $userFixture) {
            $user = new User();
            $user->setUsername($userFixture['username']);
            $user->setEmail($userFixture['email']);
            $user->setName($userFixture['name']);
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user, $userFixture['password']));
            $user->setRoles($userFixture['roles']);

            $this->addReference('user_' . $userFixture['username'], $user);

            $manager->persist($user);
        }

        $manager->flush();
    }

    protected function getRandomUserReference($entity): User
    {
        $randomUser = self::USERS[rand(0, 5)];

        if (
            $entity instanceof BlogPost && !count(array_intersect(
                $randomUser['roles'],
                [
                    User::ROLE_SUPERADMIN,
                    User::ROLE_ADMIN,
                    User::ROLE_WRITER
                ]
            ))) {
            return $this->getRandomUserReference($entity);
        }

        if (
            $entity instanceof Comment && !count(array_intersect(
                $randomUser['roles'],
                [
                    User::ROLE_SUPERADMIN,
                    User::ROLE_ADMIN,
                    User::ROLE_WRITER,
                    User::ROLE_COMMENTATOR
                ]
            ))) {
            return $this->getRandomUserReference($entity);
        }

        return $this->getReference(
            'user_' . $randomUser['username']
        );
    }
}
