<?php
declare(strict_types=1);

namespace devnullius\cms\entities;

final class Meta
{
    public string $title;
    public string $description;
    public string $keywords;

    public function __construct(string $title, string $description, string $keywords)
    {
        $this->title = $title;
        $this->description = $description;
        $this->keywords = $keywords;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }
}
