<?php

namespace Venice\Product\Api\Data;

interface NoteInterface
{
    const NOTE_ID           = 'note_id';
    const PRODUCT_ID        = 'product_id';
    const TITLE             = 'title';
    const DESCRIPTION       = 'description';
    const CREATED_AT        = 'created_at';
    const UPDATED_AT        = 'updated_at';
    const STORE_ID          = 'store_id';
    const WEBSITE_ID        = 'website_id';
    const STATUS            = 'status';

    public function getNoteId();

    public function getProductId();

    public function getDescription();

    public function setNoteId($noteId);

    public function setProductId($productId);

    public function setDescription($description);

    public function getCreatedAt();

    public function setCreatedAt($createdAt);

    public function getUpdatedAt();

    public function setUpdatedAt($updatedAt);

    public function getStoreId();

    public function setStoreId($storeId);

    public function getWebsiteId();

    public function setWebsiteId($websiteId);

    public function getStatus();

    public function setStatus($status);

    public function getTitle();

    public function setTitle($title);

}

