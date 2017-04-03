<?php declare(strict_types=1);

namespace Minimalcode\Optional;

class Book
{
    /**
     * @var int
     */
    private $pages;

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
