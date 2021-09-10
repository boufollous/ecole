<?php

namespace App\DataTransfert;

use Symfony\Component\Validator\Constraints as Assert;

class RegistrationRequestData
{

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=255)
     */
    public ?string $name = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=255)
     */
    public ?string $surname = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public ?string $email = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=6, max=4096)
     */
    public ?string $password = null;
}
