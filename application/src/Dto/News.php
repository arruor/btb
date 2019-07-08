<?php declare(strict_types=1);

namespace App\Dto;

use App\Entity\News as NewsEntity;

class News
{
    public $id;
    public $authorId;
    public $authorName;
    public $categoryId;
    public $categoryName;
    public $title;
    public $subTitle;
    public $body;
    public $publishedDate;

    public function __construct(NewsEntity $news)
    {
        $this->id = $news->getId();
        $this->categoryId = $news->getCategory()->getId();
        $this->categoryName = $news->getCategory()->getName();
        $this->authorId = $news->getAuthor()->getId();
        $this->authorName = $news->getAuthor()->getName();
        $this->title = $news->getTitle();
        $this->subTitle = $news->getSubTitle();
        $this->body = $news->getBody();
        $this->publishedDate = $news->getPublishedDate()->format('d M, Y');
    }
}
