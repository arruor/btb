<?php declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\News")
 * @ORM\Table(
 *     name="news",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="newsTitleUnique", columns={"title"})},
 *     indexes={
 *      @ORM\Index(name="categoryId", columns={"categoryId"}),
 *      @ORM\Index(name="authorId", columns={"authorId"})
 *     }
 * )
 */
class News extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(name="categoryId", type="integer", nullable=false, options={"unsigned"=true})
     * @Assert\NotBlank()
     */
    protected $categoryId;

    /**
     * @var int
     *
     * @ORM\Column(name="authorId", type="integer", nullable=false, options={"unsigned"=true})
     * @Assert\NotBlank()
     */
    protected $authorId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=128, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min=16)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="subTitle", type="string", length=128, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min=16)
     */
    private $subTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min=64)
     */
    private $body;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="publishedDate", type="datetime", nullable=false)
     */
    private $publishedDate;

    /**
     * @var NewsCategory
     *
     * @ORM\ManyToOne(targetEntity="NewsCategory", inversedBy="news")
     * @ORM\JoinColumn(name="categoryId", referencedColumnName="id")
     */
    private $category;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="news")
     * @ORM\JoinColumn(name="authorId", referencedColumnName="id")
     */
    private $author;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="NewsImage", mappedBy="news", cascade={"persist", "remove"})
     */
    private $images;

    public function __construct()
    {
        $this->publishedDate = new DateTime();
        $this->images = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return News
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubTitle(): string
    {
        return $this->subTitle;
    }

    /**
     * @param string $subTitle
     * @return News
     */
    public function setSubTitle(string $subTitle): self
    {
        $this->subTitle = $subTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return News
     */
    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getPublishedDate(): ?DateTimeInterface
    {
        return $this->publishedDate;
    }

    /**
     * @param DateTimeInterface|null $publishedDate
     * @return News
     */
    public function setPublishedDate(?DateTimeInterface $publishedDate): self
    {
        $this->publishedDate = $publishedDate;
        return $this;
    }

    /**
     * @return NewsCategory
     */
    public function getCategory(): NewsCategory
    {
        return $this->category;
    }

    /**
     * @param NewsCategory $category
     * @return News
     */
    public function setCategory(NewsCategory $category): self
    {
        if (null !== $this->category) {
            $this->category->getNews()->removeElement($this);
        }

        $this->category = $category;
        $this->category->getNews()->add($this);

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     * @return News
     */
    public function setAuthor(User $author): self
    {
        if (null !== $this->author) {
            $this->author->getNews()->removeElement($this);
        }

        $this->author = $author;
        $this->author->getNews()->add($this);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getImages(): Collection
    {
        return $this->images;
    }
}
