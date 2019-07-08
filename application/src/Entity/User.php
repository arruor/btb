<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User")
 * @ORM\Table(
 *     name="user",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="userNameUnique", columns={"username"})},
 *     indexes={@ORM\Index(name="roleId", columns={"roleId"})}
 * )
 */
class User extends AbstractEntity
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
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=32, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Unique()
     * @Assert\Length(min=3)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="password", length=60, nullable=false)
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @var int
     *
     * @ORM\Column(name="roleId", type="integer", nullable=false, options={"unsigned"=true})
     * @Assert\NotBlank()
     */
    protected $roleId;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="users")
     * @ORM\JoinColumn(name="roleId", referencedColumnName="id")
     */
    private $role;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="News", mappedBy="author", cascade={"persist", "remove"})
     */
    private $news;

    public function __construct()
    {
        $this->news = new ArrayCollection();
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param Role $role
     *
     * @return User
     */
    public function setRole(Role $role): self
    {
        if (null !== $this->role) {
            $this->role->getUsers()->removeElement($this);
        }

        $this->role = $role;
        $this->role->getUsers()->add($this);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getNews(): Collection
    {
        return $this->news;
    }
}
