<?php

namespace App\Controller\Cart;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CartController
 * @package App\Controller\Cart
 *
 * @Route("/cart")
 */
class CartController extends AbstractController
{

    /**
     * @var CartService
     */
    private CartService $cartService;

    /**
     * @var Request
     */
    private ?Request $request;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->request = null;
    }

    /**
     * @Route("/show", name="cart_show")
     */
    public function show()
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $this->cartService->getFullcart()
        ]);
    }

    /**
     * @Route("/add/{id}", name="cart_add")
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function add(Request $request, $id): RedirectResponse
    {
        $referer = filter_var($request->headers->get('referer'), FILTER_SANITIZE_URL);

        $this->cartService->add($id);
        return $this->redirect($referer);
    }

    /**
     * @Route("/remove/{id}", name="cart_remove")
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function remove(Request $request, $id): RedirectResponse
    {
        $referer = filter_var($request->headers->get('referer'), FILTER_SANITIZE_URL);

        $this->cartService->remove($id);
        return $this->redirect($referer);
    }

    /**
     * @Route("/delete/{id}", name="cart_delete")
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function delete(Request $request, $id): RedirectResponse
    {
        $referer = filter_var($request->headers->get('referer'), FILTER_SANITIZE_URL);

        $this->cartService->delete($id);
        return $this->redirect($referer);
    }

    /**
     * @Route("/erease", name="cart_erase")
     * @param Request $request
     * @return RedirectResponse
     */
    public function erase(Request $request)
    {
        $referer = filter_var($request->headers->get('referer'), FILTER_SANITIZE_URL);

        $this->cartService->erase();
        return $this->redirect($referer);
    }

}
