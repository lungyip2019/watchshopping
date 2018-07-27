<?php

namespace Venice\Checkout\Model\Plugin;


class TotalsInfo
{

    protected $cartRepository;
    protected $productRepository;
    protected $cartId;

    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $cartRepository,
        \Magento\Catalog\Model\ProductRepository $productRepository
    ) {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    public function beforeCalculate(
        \Magento\Checkout\Model\TotalsInformationManagement $totalsInformationManagement,
        $cartId,
        \Magento\Checkout\Api\Data\TotalsInformationInterface $addressInformation
    ) {
        $this->cartId = $cartId;

        return array(
            $cartId,
            $addressInformation
        );
    }

    public function afterCalculate(
        \Magento\Checkout\Model\TotalsInformationManagement $totalsInformationManagement,
        $totals
    ) {
        $quote = $this->cartRepository->get($this->cartId);
        $items = $quote->getItems();
        $totalRetailPrice = 0;
        foreach ($items as $item){
            $product = $this->productRepository->getById($item->getProductId());
            if (!$product->getRetailPrice())
                $totalRetailPrice += intval($product->getPrice()) * ($item->getQty());
            else
                $totalRetailPrice += intval($product->getRetailPrice()) * ($item->getQty());
        }
        $subtotal = $quote->getSubtotal();
        if($subtotal > 0 && $totalRetailPrice >0){
            $saved = (int) 100 - intval(($subtotal/$totalRetailPrice) * 100);
        }else{
            $saved = 0 ;
        }

        $totals->setData('saved', $saved);
        return $totals;
    }

}