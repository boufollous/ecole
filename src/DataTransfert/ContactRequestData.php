<?php

declare(strict_types=1);

namespace App\DataTransfert;

use Symfony\Component\Validator\Constraints as Assert;

class ContactRequestData
{
    /**
     * @Assert\NotBlank()
     */
    public ?string $subject = null;

    /**
     * @Assert\NotBlank()
     */
    public ?string $message = null;
}
