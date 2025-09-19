<?php

namespace App\Entity;

use App\Repository\AppRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: AppRepository::class)]
class App
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $uuid = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Env>
     */
    #[ORM\OneToMany(targetEntity: Env::class, mappedBy: 'app')]
    private Collection $envs;

    public function __construct()
    {
        $this->envs = new ArrayCollection();
        $this->uuid = Uuid::v4();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Env>
     */
    public function getEnvs(): Collection
    {
        return $this->envs;
    }

    public function addEnv(Env $env): static
    {
        if (!$this->envs->contains($env)) {
            $this->envs->add($env);
            $env->setApp($this);
        }

        return $this;
    }

    public function removeEnv(Env $env): static
    {
        if ($this->envs->removeElement($env)) {
            // set the owning side to null (unless already changed)
            if ($env->getApp() === $this) {
                $env->setApp(null);
            }
        }

        return $this;
    }
}
