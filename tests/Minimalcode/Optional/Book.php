<?php declare(strict_types=1);

namespace Minimalcode\Optional;

class Book
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $pages;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @param int $pages
     */
    public function setPages($pages)
    {
        $this->pages = $pages;
    }
}
