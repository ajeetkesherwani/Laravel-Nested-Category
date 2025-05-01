<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;

use App\Http\Requests\UpdateCategoryRequest;
use App\Interfaces\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    private CategoryRepositoryInterface $CategoryRepositoryInterface;

    public function __construct(CategoryRepositoryInterface $CategoryRepositoryInterface)
    {
        $this->CategoryRepositoryInterface = $CategoryRepositoryInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $user = auth()->user();
        // dd($user);
        // if (!$user) {
        //     return ApiResponseClass::sendResponse('Unauthorized', '', 401);
        // }
        $data = $this->CategoryRepositoryInterface->index();


        return ApiResponseClass::sendResponse(CategoryResource::collection($data), '', 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $details = [
            'name' => $request->name,
            'description' => $request->description,
            'image' => $request->image,
            'parent_id' => $request->parent_id ?? '0',
            'slug' => Str::slug($request->name),
            'is_active' => $request->is_active ?? '1',
            'is_featured' => $request->is_featured ?? '0',
            'sort_order' => $request->sort_order ?? '0'
        ];

        DB::beginTransaction();
        try {
            $product = $this->CategoryRepositoryInterface->store($details);

            DB::commit();
            return ApiResponseClass::sendResponse(new CategoryResource($product), 'Product Create Successful', 201);
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = $this->CategoryRepositoryInterface->getById($id);

        return ApiResponseClass::sendResponse(new CategoryResource($product), '', 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $updateDetails = [
            'name' => $request->name,
            'descriptions' => $request->descriptions,
            'image' => $request->image,
            'parent_id' => $request->parent_id ?? '0',
            'slug' => Str::slug($request->name),
            'is_active' => $request->is_active ?? '1',
            'is_featured' => $request->is_featured ?? '0',
            'sort_order' => $request->sort_order ?? '0'
        ];
        DB::beginTransaction();
        try {
            $product = $this->CategoryRepositoryInterface->update($updateDetails, $id);

            DB::commit();
            return ApiResponseClass::sendResponse('Product Update Successful', '', 201);
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->CategoryRepositoryInterface->delete($id);

        return ApiResponseClass::sendResponse('Product Delete Successful', '', 204);
    }

    public function getCategoryList(Request $request)
    {
        $category = $this->CategoryRepositoryInterface->getCategoryList();

        return ApiResponseClass::sendResponse(CategoryResource::collection($category), '', 200);
    }
}
