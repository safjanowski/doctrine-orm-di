<?php

namespace App;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string')]
    protected string $name;

    #[ORM\ManyToMany(targetEntity: Bug::class, mappedBy: 'reporter')]
    private ?Collection $reportedBugs = null;

    #[ORM\ManyToMany(targetEntity: Bug::class, mappedBy: 'engineer')]
    private ?Collection $assignedBugs = null;

    public function __construct()
    {
        $this->reportedBugs = new ArrayCollection();
        $this->assignedBugs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function addReportedBug($bug): void
    {
        $this->reportedBugs[] = $bug;
    }

    public function assignedToBug($bug): void
    {
        $this->assignedBugs[] = $bug;
    }
}
