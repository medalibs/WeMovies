<?php

namespace App\ValueObject;

class Trailer
{
    public function __construct(
        public string $name,
        public string $url,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
