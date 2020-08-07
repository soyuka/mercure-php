<?php

namespace App\Entity;

use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TopicRepository::class)
 */
class Topic
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=255)
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Message::class, mappedBy="topics")
     */
    private $updates;

    public function __construct(string $name)
    {
        $this->updates = new ArrayCollection();
        $this->id = $name;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->updates;
    }

    public function addMessage(Message $update): self
    {
        if (!$this->updates->contains($update)) {
            $this->updates[] = $update;
            $update->addTopic($this);
        }

        return $this;
    }

    public function removeMessage(Message $update): self
    {
        if ($this->updates->contains($update)) {
            $this->updates->removeElement($update);
            $update->removeTopic($this);
        }

        return $this;
    }
}
