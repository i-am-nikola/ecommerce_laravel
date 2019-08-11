<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Category;
use Illuminate\Validation\Validator;
use function Opis\Closure\unserialize;

class CategoryController extends Controller
{

  public function index()
  {
    $breadcrumbs = [
      'parent' => t('category.manage'),
      'level_1' => t('category.category')
    ];
    $categories = Category::all();
    return view('admin.category.index', compact('breadcrumbs', 'categories'));
  }

  public function store(CategoryRequest $request)
  {
    if ($request->ajax()) {
      Category::create($request->all());
      return response()->json($request->all());
    }
  }

  public function edit(Request $request)
  {
    $category = Category::findOrFail($request->id);
    return response()->json($category);
  }

  public function update(Request $request)
  {
    $userRequest = new CategoryRequest();
    $this->validate($request, $userRequest->rules(true, $request->id), $userRequest->messages(), $userRequest->attributes());
    if ($request->ajax()) {
      $category = Category::findOrFail($request->id);
      $category->update($request->all());
      return response($request->all());
    }
  }

  public function destroy(Request $request)
  {
    $category = Category::findOrFail($request->id);
    $category->delete();
  }

  public function move(Request $request)
  {
    $category = Category::findOrFail($request->id);
    $category->parent_id = $request->parent_id;
    $category->save();
  }
}