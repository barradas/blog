<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;


#[
    ApiResource(
        collectionOperations: [
            'post' => [
                'path' => '/users/confirm']
        ],
        itemOperations: []
    ),

]
class UserConfirmation
{
    #[
        ApiProperty(
            identifier: true
        ),
        Assert\NotBlank,
        Assert\Length(min:5, max:300)
    ]
    public $username;
}
