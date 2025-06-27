<?php
namespace App\Application\Services;

use App\Domain\Cart\Contracts\CartRepositoryInterface;
use App\Domain\Cart\Entities\Cart;
use App\Domain\Cart\Entities\CartItem;

class CartService
{
    public function __construct(
        private CartRepositoryInterface $repo
    ) {}

    public function list(): Cart
    {
        return $this->repo->current();
    }

    public function add(array $data): Cart
    {
        $item = new CartItem(
            $data['variant_id'],
            $data['name'],
            (float) $data['price'],
            $data['quantity']
        );

        return $this->repo->addItem($item);
    }

    public function update(int $id, int $qty): Cart
    {
        return $this->repo->updateQty($id, $qty);
    }

    public function remove(int $id): Cart
    {
        return $this->repo->removeItem($id);
    }

    public function clear(): void
    {
        $this->repo->clear();
    }
}
