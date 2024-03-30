<?php

declare(strict_types=1);

namespace App\DTOs;

class TaskDTO
{
    private ?int $id;
    private string $title;
    private ?string $description;
    private string $date;
    private string $location;
    private ?string $details;
    private ?int $userId;

    public function __construct(
        int $id = null,
        string $title,
        ?string $description,
        string $date,
        string $location,
        ?string $details,
        ?int $userId
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->date = $date;
        $this->location = $location;
        $this->details = $details;
        $this->userId = $userId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        return $this->userId = $userId;
    }
}
