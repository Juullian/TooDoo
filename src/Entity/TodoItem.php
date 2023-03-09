<?php

namespace App\Entity;

use App\Repository\TodoItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TodoItemRepository::class)]
class TodoItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $todoname = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTodoname(): ?string
    {
        return $this->todoname;
    }

    public function setTodoname(string $todoname): self
    {
        $this->todoname = $todoname;

        return $this;
    }
}
