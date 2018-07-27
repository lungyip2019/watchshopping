<?php

namespace Venice\Product\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Store\Model\StoreManagerInterface;
use Venice\Product\Api\Data\NoteInterface;
use Venice\Product\Api\NoteRepositoryInterface;
use Venice\Product\Api\Data\NoteInterfaceFactory;
use Venice\Product\Model\ResourceModel\Note as ResourceNote;
use Venice\Product\Model\ResourceModel\Note\CollectionFactory as NoteCollectionFactory;

class NoteRepository implements NoteRepositoryInterface
{

    protected $instances = [];

    protected $resource;

    protected $storeManager;

    protected $noteCollectionFactory;

    protected $searchResultsFactory;

    protected $noteInterfaceFactory;

    protected $dataObjectHelper;

    public function __construct(
        ResourceNote $resource,
        StoreManagerInterface $storeManager,
        NoteCollectionFactory $noteCollectionFactory,
        NoteInterfaceFactory $noteInterfaceFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->resource                 = $resource;
        $this->storeManager             = $storeManager;
        $this->noteCollectionFactory    = $noteCollectionFactory;
        $this->noteInterfaceFactory     = $noteInterfaceFactory;
        $this->dataObjectHelper         = $dataObjectHelper;
    }

    public function getByProductId($productId)
    {
        if (!isset($this->instances[$productId])) {
            $note = $this->noteInterfaceFactory->create();
            $this->resource->load($note, $productId, 'product_id');
            $this->instances[$productId] = $note;
        }
        return $this->instances[$productId];
    }

    public function save(NoteInterface $note)
    {
        // TODO
    }


}
