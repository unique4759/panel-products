<?php

namespace App;

class Category extends Model {

    /**
     * Disable timestamps.
     *
     * @var bool
     */
    public $timestamps = FALSE;

    /**
     * Related entity.
     *
     * @return mixed.
     */
    public function products() {
        return $this->belongsToMany(Product::class);
    }
}
