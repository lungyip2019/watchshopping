<?php

namespace Venice\Product\Api;

use \Venice\Product\Api\Data\NoteInterface;

interface NoteRepositoryInterface
{
    public function save(NoteInterface $note);

    public function getByProductId($productId);
}
