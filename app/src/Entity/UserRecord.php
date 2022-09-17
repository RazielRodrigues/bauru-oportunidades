<?php

namespace App\Entity;

use App\Repository\UserRecordRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRecordRepository::class)
 */
class UserRecord
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
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $record;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userRecords")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fk_user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $job_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRecord(): ?string
    {
        return $this->record;
    }

    public function setRecord(string $record): self
    {
        $this->record = $record;

        return $this;
    }

    public function getFkUser(): ?User
    {
        return $this->fk_user;
    }

    public function setFkUser(?User $fk_user): self
    {
        $this->fk_user = $fk_user;

        return $this;
    }

    public function getJobType(): ?string
    {
        return $this->job_type;
    }

    public function setJobType(string $job_type): self
    {
        $this->job_type = $job_type;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
