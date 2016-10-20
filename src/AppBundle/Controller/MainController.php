<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Product;
use AppBundle\Repository\CommentRepository;
use AppBundle\Repository\ProductRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('AppBundle::index.html.twig', [
            'products' => $this->getAllProducts(),
        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function productAction(Request $request, $id)
    {
        /** @var Product $product */
        $product = $this->getProductsRepository()->findOneById($id);
        if (null === $product){
            throw new NotFoundHttpException('Product not found');
        }

        return $this->render('AppBundle::product.html.twig', [
            'product'  => $product,
            'comments' => $this->getOrderedComments($product),
        ]);
    }
    /**
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function commentAction(Request $request, $id)
    {
        /** @var Product $product */
        $product = $this->getProductsRepository()->findOneById($id);
        if (null === $product){
            throw new NotFoundHttpException('Product not found');
        }
        $form = $this->createForm('comment');
        $form->handleRequest($request);
        if($form->isValid()){
            $comment = $form->getData();
            $comment
                ->setProduct($product)
                ->setDateTime(new DateTime())
            ;
            $this->getEntityManager()->persist($comment);
            $this->getEntityManager()->flush();

            return $this->render('AppBundle::comment_single.html.twig', [
                'comment' => $comment,
            ]);
        } elseif ($form->isSubmitted()){

            return new Response(
                $this->renderView('AppBundle::comment.html.twig', [
                'form' => $form->createView(),
                'id'   => $id,
            ]), Response::HTTP_BAD_REQUEST);
        }

        return $this->render('AppBundle::comment.html.twig', [
            'form' => $form->createView(),
            'id'   => $id,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $searchString = $request->get('searchString');

        $result = $this->getProductsRepository()->search($searchString);

        return $this->render('AppBundle::search.html.twig', [
            'products' => $result,
        ]);
    }

    /**
     * @return Product[]
     */
    protected function getAllProducts()
    {
        return $this->getProductsRepository()->findAll();
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * @return ProductRepository
     */
    private function getProductsRepository()
    {
        return $this->getEntityManager()->getRepository('AppBundle:Product');
    }

    /**
     * @return CommentRepository
     */
    private function getCommentsRepository()
    {
        return $this->getEntityManager()->getRepository('AppBundle:Comment');
    }

    /**
     * @param $product
     *
     * @return Comment[]|null
     */
    private function getOrderedComments($product)
    {
        return $this->getCommentsRepository()->getOrderedProductComments($product);
    }
}
