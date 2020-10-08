<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use function response;

class ProductController extends Controller {

    /**
     * Middleware for access to admin panel products.
     *
     * ProductController constructor.
     */
    public function __construct() {
    }

    /**
     * Display admin panel products.
     *
     * Js variable for choose categories.
     *
     * @return mixed
     */
    public function adminPage() {
        $categories_list = Category::get()
            ->map(function ($item) {
                return [
                    'key' => $item->id,
                    'value' => $item->name,
                ];
            })->toArray();

        return view('products', compact('categories_list'));
    }

    /**
     * Get all products.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getData(Request $request) {
        $data = $this->validate($request, [
            'name' => 'nullable|string',
            'deleted' => 'nullable',
            'publish' => 'nullable',
            'unpublish' => 'nullable',
            'category' => 'nullable|integer',
            'price_from' => 'nullable|integer',
            'price_to' => 'nullable|integer',
        ]);

        $result = new Product;

        if ($data['name']) {
            $result = $result->where('name', 'LIKE', '%' . $data['name'] . '%');
        }
        if ($data['deleted']) {
            $result = $result->whereDeleted(!$data['deleted']);
        }
        if ($data['publish']) {
            $result = $result->wherePublish($data['publish']);
        }
        if ($data['unpublish']) {
            $result = $result->wherePublish(!$data['unpublish']);
        }
        if ($data['category']) {
            $category = $data['category'];
            $result = $result->whereHas('categories', function ($q) use ($category) {
                $q->where('id', $category);
            });
        }
        if ($data['price_from']) {
            $result = $result->where('price', '>=', $data['price_from']);
        }
        if ($data['price_to']) {
            $result = $result->where('price', '<=', $data['price_to']);
        }

        $data = $result->with('categories')
            ->get();

        $result_data = [
            'count' => $data->count(),
            'data' => $data,
        ];

        return response()->json($result_data);
    }

    /**
     * Save new product.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function store(Request $request) {
        $data = $request->validate(
            [
                'name' => 'required|string',
                'price' => 'required|integer',
                'publish' => 'required',
                'image' => 'required',
            ],
            [
                'name.required' => 'Поле Название обязательно для заполенния!',
                'name.price' => 'Поле Цена обязательно для заполенния!',
            ]
        );

        $data_categories = $request->validate([
            'categories' => 'nullable|array',
        ]);

        $product = Product::create($data);
        $product->save();

        if (!empty($data_categories['categories'])) {
            foreach ($data_categories['categories'] as $category) {
                $product->categories()->attach($category);
            }
        }

        return response()->json('success!');
    }

    /**
     * Update existing product data.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function update(Request $request) {
        $data = $request->validate(
            [
                'id' => 'nullable|integer',
                'name' => 'required|string',
                'price' => 'required|integer',
                'publish' => 'required',
                'image' => 'required',
            ],
            [
                'name.required' => 'Поле Название обязательно для заполенния!',
                'name.price' => 'Поле Цена обязательно для заполенния!',
            ]
        );

        $data_categories = $request->validate([
            'categories' => 'nullable|array',
        ]);

        $product = Product::find($data['id']);
        $product->fill($data);
        $product->save();

        if (!empty($data_categories['categories'])) {
            $product->categories()->detach();
            foreach ($data_categories['categories'] as $category) {
                $product->categories()->attach($category);
            }
        }

        return response()->json($product);
    }

    /**
     * Delete existing product.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete(Request $request) {
        $data = $this->validate($request, [
            'id' => 'integer',
        ]);

        $product = Product::find($data['id']);
        $product->deleted = 1;
        $product->save();

        return response()->json($product);
    }
}
