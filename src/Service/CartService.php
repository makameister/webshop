<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var array
     */
    private $cart;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * clé du panier en session
     */
    private const SESSION_KEY = 'cart';

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->cart = $this->session->get(self::SESSION_KEY, []);
        $this->productRepository = $productRepository;
    }

    public function add(int $id): void
    {
        if (isset($this->cart[$id])) {
            $this->cart[$id]++;
        } else {
            $this->cart[$id] = 1;
        }
        $this->setToSession();
    }

    public function remove(int $id): void
    {
        if ($this->cart[$id] > 1) {
            $this->cart[$id]--;
        } else {
            unset($this->cart[$id]);
        }
        $this->setToSession();
    }

    public function delete(int $id): void
    {
        unset($this->cart[$id]);
        $this->setToSession();
    }

    public function erase(): void
    {
        $this->cart = [];
        $this->setToSession();
    }

    public function getFullcart(): array
    {
        $fullCart = ['item' => []];
        $total = 0;
        foreach ($this->cart as $id => $quantity) {
            $product = $this->productRepository->find($id);
            $totalRow = $product->getPrice() * $quantity;
            $fullCart['item'][] = [
                'product' => $product,
                'quantity' => $quantity,
                'total' => $totalRow
            ];
            $total += $totalRow;
        }
        $fullCart['subTotal'] = $total;
        $fullCart['port'] = round($total * 0.19,2);
        $fullCart['total'] = $total + $fullCart['port'];

        return $fullCart;
    }

    public function getTotal(): float
    {
        $total = 0;

        /** @var Product item **/
        foreach ($this->getFullcart() as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }

        return $total;
    }

    private function setToSession(): void
    {
        $this->session->set(self::SESSION_KEY, $this->cart);
    }

}
