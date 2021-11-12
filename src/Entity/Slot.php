<?php

namespace App\Entity;

use App\Repository\SlotRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SlotRepository::class)
 */
class Slot
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $is_full_day = null;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeInterface $starting_at = null;

    /**
     * @Assert\Expression(expression="value > this.getStartingAt")
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeInterface $ending_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsFullDay(): ?bool
    {
        return $this->is_full_day;
    }

    public function setIsFullDay(bool $is_full_day): self
    {
        $this->is_full_day = $is_full_day;

        return $this;
    }

    public function getStartingAt(): \DateTimeInterface
    {
        return $this->starting_at;
    }

    public function setStartingAt(?\DateTimeImmutable $starting_at): self
    {
        $this->starting_at = $starting_at;

        return $this;
    }

    public function getEndingAt(): \DateTimeInterface
    {
        return $this->ending_at;
    }

    public function setEndingAt(?\DateTimeImmutable $ending_at): self
    {
        $this->ending_at = $ending_at;

        return $this;
    }
}
