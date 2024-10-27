<?php

namespace App\ValueObject;

readonly class Movie
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public string $posterPath,
        public int $year,
        public int $voteCount,
        public int $rate,
        public string $backDropPath,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPosterPath(): string
    {
        return $this->posterPath;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getVoteCount(): int
    {
        return $this->voteCount;
    }

    public function getRate(): int
    {
        return $this->rate;
    }

    public function getBackDropPath(): string
    {
        return $this->backDropPath;
    }
}
