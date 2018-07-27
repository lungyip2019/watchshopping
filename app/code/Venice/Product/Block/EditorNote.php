<?php

namespace Venice\Product\Block;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Venice\Product\Api\NoteRepositoryInterface;

class EditorNote extends Template
{
    protected $coreRegistry;
    protected $noteRepository;

    public function __construct(
        Context $context,
        Registry $registry,
        NoteRepositoryInterface $noteRepository,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->noteRepository = $noteRepository;
        parent::__construct($context, $data);
    }

    public function getCurrentNote()
    {
        $product = $this->coreRegistry->registry('current_product');
        $note = $this->noteRepository->getByProductId($product->getEntityId());
        return $note;
    }
}



