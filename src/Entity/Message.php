<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 * @ORM\Table(name="`message`")
 */
class Message
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Topic::class, inversedBy="updates")
     */
    private $topics;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $data;

    /**
     * @ORM\Column(type="boolean")
     */
    private $private;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $retry;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    public function __construct()
    {
        $this->topics = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return Collection|Topic[]
     */
    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(Topic $topic): self
    {
        if (!$this->topics->contains($topic)) {
            $this->topics[] = $topic;
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        if ($this->topics->contains($topic)) {
            $this->topics->removeElement($topic);
        }

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(?string $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getPrivate(): ?bool
    {
        return $this->private;
    }

    public function setPrivate(bool $private): self
    {
        $this->private = $private;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRetry(): ?int
    {
        return $this->retry;
    }

    public function setRetry(?int $retry): self
    {
        $this->retry = $retry;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function print() {
        printf("id: %s\n", $this->getId());
        if ($this->getRetry()) {
            printf("retry: %s\n", $this->getRetry());
        }

        printf("event: %s\n", $this->getType() ?: 'message');
        printf("data: %s\n\n", $this->getData());
    }
}
