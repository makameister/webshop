<?php

namespace App\Controller\Shop;

use App\Entity\Search\ProductSearch;
use App\Form\ProductSearchType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ShopController
 * @package App\Controller\Shop
 * @Route("/magasin")
 */
class ShopController extends AbstractController
{

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("", name="shop_index")
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopCategoryController',
            'products' => $productRepository->findMostRecent(),
            'categories' => $categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/{category}", name="shop_category")
     * @param $category
     * @param ProductRepository $productRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function category(
        $category,
        ProductRepository $productRepository,
        Request $request,
        PaginatorInterface $paginator
    )
    {
        /*
        $dataSearch = $request->getQueryString();
        if ($dataSearch !== "") {
            $request->getSession()->set('searchData', $request->getQueryString());
        }
        */
        dump($request);

        $search = new ProductSearch();
        $search->setCategory($category);
        $form = $this->createForm(ProductSearchType::class, $search);
        $form->handleRequest($request);

        $products = $paginator->paginate(
            $productRepository->findFilteredByCategoryQuery($category, $search),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('shop/category.html.twig', [
            'products' => $products,
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{category}/{product}", name="shop_product_view")
     * @param string $category
     * @param string $product
     * @return Response
     */
    public function productView(string $category, string $product)
    {
        $product = $this->productRepository->findOneBy(['name' => $product]);

        return $this->render('shop/product.html.twig', [
            'product' => $product,
            'category' => $category
        ]);
    }
}
