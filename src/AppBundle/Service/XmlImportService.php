<?php

namespace AppBundle\Service;

use AppBundle\Entity\Category;
use AppBundle\Entity\ImportResults;
use AppBundle\Entity\Product;
use AppBundle\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;

class XmlImportService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Category[]
     */
    protected $categories;

    /**
     * XmlImportService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $path
     *
     * @return ImportResults
     */
    public function load($path)
    {
        $content = file_get_contents($path);
        $xml = simplexml_load_string($content);
        $json = json_encode($xml);
        $data = json_decode($json, true);

        return
            isset($data['products']['product'])
                ? $this->handleProducts($data['products']['product'])
                : new ImportResults()
            ;
    }

    /**
     * @param array $products
     *
     * @return ImportResults
     * @throws \ParseError
     */
    protected function handleProducts(array $products)
    {
        $productId = null;
        foreach ($products as $xmlProduct){
            $product = new Product();
            if($xmlProduct['rating'] <= 3){
                $category =  $this->getCategories()[2];
            } elseif ($xmlProduct['rating'] <= 4){
                $category =  $this->getCategories()[1];
            } else {
                $category =  $this->getCategories()[0];
            }
            if(isset($xmlProduct['product_id'])){
                $productId = $xmlProduct['product_id'];
            }
            $description = is_string($xmlProduct['description']) ? $xmlProduct['description'] : '';
            $price = isset($xmlProduct['inet_price']) ? $xmlProduct['price'] : null;
            $product
                ->setProductId($productId)
                ->setTitle($xmlProduct['title'])
                ->setDescription($description)
                ->setRating($xmlProduct['rating'])
                ->setPrice($price)
                ->setImage($xmlProduct['image'])
                ->setCategory($category)
            ;
            $this->entityManager->persist($product);
        }
        try{
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \ParseError('Error creation products');
        }
        $importResult = new ImportResults();
        $importResult
            ->setNewCount(count($products))
            ->setTotalCount(count($products))
        ;
        $this->entityManager->persist($importResult);
        $this->entityManager->flush();

        return $importResult;
    }

    /**
     * @return Category[]
     */
    protected function getCategories()
    {
        if(null === $this->categories){
            $this->categories = $this->getCategoriesRepository()->findAll();
        }

        return $this->categories;
    }

    /**
     * @return CategoryRepository
     */
    private function getCategoriesRepository()
    {
        return $this->entityManager->getRepository('AppBundle:Category');
    }
}
