<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller {

    /**
     * Middleware for access to admin panel categories.
     *
     * ProductController constructor.
     */
    public function __construct() {
    }

    /**
     * Display admin panel categories.
     *
     * @return mixed
     */
    public function adminPage() {
        return view('categories');
    }

    /**
     * Get all categories.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request) {
        $categories = new Category;

        $data = $categories->get();

        $result_data = [
            'count' => $data->count(),
            'data' => $data,
        ];

        return response()->json($result_data);
    }

    /**
     * Save new category.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function store(Request $request) {
        $data = $request->validate(
            [
                'name' => 'required|string',
            ],
            [
                'name.required' => 'Поле Название обязательно для заполенния!',
            ]
        );

        $category = Category::create($data);
        $category->save();

        return response()->json('success!');
    }

    /**
     * Update existing category data.
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
            ],
            [
                'name.required' => 'Поле Название обязательно для заполенния!',
            ]
        );

        $category = Category::find($data['id']);
        $category->fill($data);
        $category->save();

        return response()->json($category);
    }

    /**
     * Delete existing category.
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

        $category = Category::find($data['id']);

        if ($category && $category->products->count() == 0) {
            $category->delete();
            return response()->json('success!');
        }

        return response()->json(['errors' => ['has_products' => ['Невозможно удалить катгорию, она привязана к товару!']]], 400);
    }
}
