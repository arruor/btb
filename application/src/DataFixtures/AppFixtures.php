<?php

namespace App\DataFixtures;

use App\Entity\News;
use App\Entity\NewsCategory;
use App\Entity\NewsImage;
use App\Entity\Role;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class AppFixtures extends Fixture implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    private const IMAGE_PATH_EVEN = '/images/news/sample1.jpg';
    private const IMAGE_PATH_ODD = '/images/news/sample2.jpg';

    private $roles = [
        0 => ['name' => 'admin', 'display' => 'System Administrators'],
        1 => ['name' => 'user', 'display' => 'regular Users'],
    ];

    private static $categories  = [
        'news', 'regional', 'politics', 'sport'
    ];

    private $users = [
        0 => ['name' => 'admin', 'pass' => 'admin', 'roleId' => 1],
        1 => ['name' => 'user', 'pass' => 'user', 'roleId' => 2],
    ];

    public function load(ObjectManager $manager): void
    {
        // Roles and users
        foreach ($this->roles as $key => $record) {
            $role = new Role();
            $role->setName($record['name']);
            $role->setDisplayName($record['display']);
            $manager->persist($role);

            $user = new User();
            $user->setName($this->users[$key]['name']);
            $user->setPassword(password_hash($this->users[$key]['pass'], PASSWORD_BCRYPT));
            $user->setRole($role);
            $manager->persist($user);
        }

        // News Categories
        foreach (self::$categories as $record) {
            $category = new NewsCategory();
            $category->setName($record);
            $manager->persist($category);
        }

        $generator = Faker\Factory::create('en_GB');
        // News and News Images
        for ($x = 1; $x <= 10; $x++) {
            $news = new News();
            $news->setTitle($generator->realText(100));
            $news->setSubTitle($generator->realText(100));
            $news->setBody($generator->realText());
            $news->setCategory($category);
            $news->setAuthor($user);
            $news->setPublishedDate(new DateTime());

            $image = new NewsImage();
            $image->setIsHeading(1);
            $image->setImagePath(($x % 2 === 0) ? self::IMAGE_PATH_ODD : self::IMAGE_PATH_EVEN);
            $image->setNews($news);
            $manager->persist($image);
        }

        $manager->flush();
    }
}
