<?php

namespace App;

class Product extends Model {

    /**
     * Related entity.
     *
     * @return mixed.
     */
    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Selection of published articles.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeActive($query) {
        return $query->wherePublished(TRUE);
    }
}
