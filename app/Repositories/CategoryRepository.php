<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Get all categories.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Category::all();
    }

    /**
     * Get a category by ID.
     *
     * @param int $id
     * @return \App\Models\Category
     */
    public function getById($id)
    {
        return Category::findOrFail($id);
    }

    /**
     * Store a new category.
     *
     * @param array $data
     * @return \App\Models\Category
     */
    public function store(array $data)
    {
        $category = new Category();
        $category->name = $data['name'];
        $category->slug = Str::slug($data['name']);
        $category->description = $data['description'] ?? '';
        $category->image = '';    //$this->uploadImage($data['image'] ?? null);
        $category->parent_id = $data['parent_id'] ?? '0';
        $category->is_active = $data['is_active'] ?? true;
        $category->is_featured = $data['is_featured'] ?? false;
        $category->sort_order = $data['sort_order'] ?? 0;

        try {
            DB::beginTransaction();
            $category->save();
            DB::commit();
            return $category;
        } catch (QueryException $e) {
            dd($e->getMessage());
            DB::rollBack();
            throw new \Exception('Error saving category: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing category.
     *
     * @param array $data
     * @param int $id
     * @return \App\Models\Category
     */
    public function update(array $data, $id)
    {
        try {
            DB::beginTransaction();
            $category = Category::findOrFail($id);
            if (isset($data['name'])) {
                $category->name = $data['name'];
                $category->slug = Str::slug($data['name']);
            }
            if (isset($data['description'])) {
                $category->description = $data['description'];
            }
            if (isset($data['image'])) {
                // Delete old image if exists
                if ($category->image) {
                    Storage::delete($category->image);
                }

                $category->image = '';   // $this->uploadImage($data['image']);
            }
            if (isset($data['parent_id'])) {
                $category->parent_id = $data['parent_id'];
            }
            if (isset($data['is_active'])) {
                $category->is_active = $data['is_active'];
            }
            if (isset($data['is_featured'])) {
                $category->is_featured = $data['is_featured'];
            }
            if (isset($data['sort_order'])) {
                $category->sort_order = $data['sort_order'];
            }
            $category->save();
            DB::commit();
            return $category;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw new \Exception('Category not found: ' . $e->getMessage());
        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception('Error updating category: ' . $e->getMessage());
        }
    }

    // public function getCategoryList()
    // {
    //     $allCategories = Category::all()->toArray();

    //     $tree = $this->buildTree($allCategories, 0);

    //     return $tree;
    // }

    public function getCategoryList()
    {
        $categories = Category::all()->toArray();

        $tree = $this->buildCategoryTree($categories);
        // dd($tree);
        return $tree;
    }

    function buildCategoryTree(array $elements, $parentId = 0)
    {
        $branch = [];

        foreach ($elements as $element) {
            // Access array elements with ['key'] syntax instead of ->key
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildCategoryTree($elements, $element['id']);
                $element['children'] = $children ?: []; // Ensure children is always an array
                $branch[] = $element;
            }
        }

        return $branch;
    }
}
