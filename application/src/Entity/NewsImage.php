<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NewsImage")
 * @ORM\Table(
 *     name="newsImage",
 *     indexes={@ORM\Index(name="newsId", columns={"newsId"})}
 * )
 */
class NewsImage extends AbstractEntity
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
     * @ORM\Column(name="newsId", type="integer", nullable=false, options={"unsigned"=true})
     */
    protected $newsId;

    /**
     * @var string
     *
     * @ORM\Column(name="imagePath", type="string", length=128, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min=3)
     */
    private $imagePath;

    /**
     * @var int
     *
     * @ORM\Column(name="isHeading", type="boolean", nullable=false, options={"default"="0", "unsigned"=false}))
     */
    private $isHeading = 0;

    /**
     * @var News
     *
     * @ORM\ManyToOne(targetEntity="News", inversedBy="images")
     * @ORM\JoinColumn(name="newsId", referencedColumnName="id")
     */
    private $news;

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
    public function getImagePath(): string
    {
        return $this->imagePath;
    }

    /**
     * @param string $path
     * @return NewsImage
     */
    public function setImagePath(string $path): self
    {
        $this->imagePath = $path;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsHeading(): bool
    {
        return (bool)$this->isHeading;
    }

    /**
     * @param int $isHeading
     * @return NewsImage
     */
    public function setIsHeading(int $isHeading): self
    {
        $this->isHeading = $isHeading;
        return $this;
    }

    /**
     * @param News $news
     *
     * @return NewsImage
     */
    public function setNews(News $news): self
    {
        if (null !== $this->news) {
            $this->news->getImages()->removeElement($this);
        }

        $this->news = $news;
        $this->news->getImages()->add($this);

        return $this;
    }
}
