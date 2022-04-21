<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=App\Repository\CategoryRepository::class)
 * @Serializer\ExclusionPolicy(policy="none")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="uuid")
     */
    private string $uuid;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private bool $enabled;

    /**
     * @ORM\Column(type="string", length="255")
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length="255", unique=true)
     */
    private string $slug;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Type("DateTime<'d-m-Y H:i:s'>")
     */
    private \DateTime $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Serializer\Type("DateTime<'d-m-Y H:i:s'>")
     */
    private \DateTime $updated;

    public function __construct(
        string $name,
        bool $enabled
    ) {
        $this->name = $name;
        $this->enabled = $enabled;

        $this->initialize();
    }

    public function initialize(): self {
        $this
            ->generateSlug()
            ->generateUuid()
            ->generateCreated()
        ;

        return $this;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): self {
        $this->id = $id;
        $this->generateUpdated();

        return $this;
    }

    public function getUuid(): string {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self {
        $this->uuid = $uuid;
        $this->generateUpdated();

        return $this;
    }

    public function generateUuid(): self {
        $this->uuid = Uuid::v6();

        return $this;
    }

    public function isEnabled(): bool {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self {
        $this->enabled = $enabled;
        $this->generateUpdated();

        return $this;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;
        $this->generateUpdated();

        return $this;
    }

    public function getSlug(): string {
        return $this->slug;
    }

    public function setSlug(string $slug): self {
        $this->slug = $slug;
        $this->generateUpdated();

        return $this;
    }

    public function generateSlug(): self {
        $slugger = new AsciiSlugger();

        $this->setSlug(
            $slugger->slug($this->getName())
        );

        return $this;
    }

    public function getCreated(): \DateTime {
        return $this->created;
    }

    public function setCreated(\DateTime $created): self {
        $this->created = $created;
        $this->generateUpdated();

        return $this;
    }

    public function generateCreated(): self {
        return $this->setCreated(new \DateTime());
    }

    public function getUpdated(): \DateTime {
        return $this->updated;
    }

    public function setUpdated(\DateTime $updated): self {
        $this->updated = $updated;

        return $this;
    }

    public function generateUpdated(): self {
        return $this->setUpdated(new \DateTime());
    }

    public function update(Category $other): void {
        $this
            ->setName($other->getName())
            ->setEnabled($other->isEnabled())
            ->generateSlug()
        ;
    }
}