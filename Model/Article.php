<?php

declare(strict_types=1);

class Article
{
    public int $id;
    public string $title;
    public ?string $description;
    public ?string $publishDate;

    public function __construct(int $id, string $title, ?string $description, ?string $publishDate)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->publishDate = $publishDate;
    }

    public function formatPublishDate($format = 'D-M-Y')
    {
    $date = $this->publishDate;

    // Convert the date string to a DateTime object
    $dateTime = new DateTime($date);

    // Format the date according to the specified format
    $formattedDate = $dateTime->format($format);

    // Return the formatted date
    return $formattedDate;
    }
}