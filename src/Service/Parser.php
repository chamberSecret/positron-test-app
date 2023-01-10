<?php

namespace App\Service;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Settings;
use App\Repository\SettingsRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class Parser
{
    private ObjectManager $manager;
    private string $PATH = "images";
    private ParameterBagInterface $parameterBag;

    public function __construct(ManagerRegistry $doctrine, ParameterBagInterface $parameterBag)
    {
        $this->manager = $doctrine->getManager();
        $this->parameterBag = $parameterBag;
    }

    /**
     * @throws Exception
     */
    public function parse(): bool
    {
        $settings = $this->manager->getRepository(Settings::class)->find(1);

        if ($settings) {
            $url = $settings->getParseUrl();
            echo "=> Parsing from: " . $url . PHP_EOL;

            $data = file_get_contents($url);
            $data = json_decode($data, true);

            if (!file_exists($this->parameterBag->get('kernel.project_dir') . '/public/images')) {
                mkdir($this->parameterBag->get('kernel.project_dir') . '/public/images');
            }

            return $this->_parse($data);
        }

        return false;
    }

    public function saveImage(string $title, string $image_url): string|null
    {
        $pathToImage = null;

        try {
            $image = file_get_contents($image_url);
            $pathToImage = join("/", array($this->PATH, $title)) . ".jpg";
            file_put_contents(join("/", array("public", $pathToImage)), $image);
        } catch (Exception $exception) {
            echo $exception . PHP_EOL;
        }

        return $pathToImage;
    }

    /**
     * @throws Exception
     */
    private function _parse($data): bool
    {
        $slugger = new AsciiSlugger();

        if ($data) {
            foreach ($data as $key => $book) {
                $bookModel = $this->manager->getRepository(Book::class)->findOneBy(["title" => $book["title"]]);

                if (!$bookModel) {
                    $bookModel = new Book();
                    $bookModel->setTitle($book['title']);
                    $bookModel->setSlug($slugger->slug($book['title']));
                }

                if (key_exists("authors", $book)) {
                    foreach ($book["authors"] as $author) {
                        $authorModel = $this->manager->getRepository(Author::class)->findOneBy(["name" => $author]);

                        if (!$authorModel) {
                            $authorModel = new Author();
                            $authorModel->setName($author);
                            $this->manager->persist($authorModel);
                            $this->manager->flush();
                        }

                        $bookModel->addAuthor($authorModel);
                        $authorModel->addBook($bookModel);

                        unset($book["categories"][$author]);
                    }
                }

                if (key_exists("categories", $book)) {
                    foreach ($book["categories"] as $category) {
                        if (!$category) $category = "NEW";

                        $categoryModel = $this->manager->getRepository(Category::class)->findOneBy(array('name' => $category));

                        if (!$categoryModel) {
                            $categoryModel = new Category();
                            $categoryModel->setName($category);
                            $categoryModel->setSlug($slugger->slug($category));
                            $this->manager->persist($categoryModel);
                            $this->manager->flush();
                        }

                        $bookModel->addCategory($categoryModel);
                        $categoryModel->addBook($bookModel);

                        unset($book["categories"][$category]);
                    }
                }

                if (key_exists("isbn", $book)) {
                    $bookModel->setIsbn($book["isbn"]);
                }

                if (key_exists("pageCount", $book)) {
                    $bookModel->setPages($book["pageCount"]);
                }

                if (key_exists("publishedDate", $book)) {
                    $bookModel->setDate(new DateTime($book["publishedDate"]["\$date"]));
                }

                if (key_exists("shortDescription", $book)) {
                    $bookModel->setShorDescription($book["shortDescription"]);
                }

                if (key_exists("longDescription", $book)) {
                    $bookModel->setLongDescription($book["longDescription"]);
                }

                if (key_exists("status", $book)) {
                    $bookModel->setStatus($book["status"]);
                }

                if (key_exists("thumbnailUrl", $book)) {
                    $bookModel->setImage($this->saveImage($bookModel->getSlug(), $book["thumbnailUrl"]));
                }

                $this->manager->persist($bookModel);

                unset($data[$key]);
            }

            $this->manager->flush();
            return true;
        }

        return false;
    }
}