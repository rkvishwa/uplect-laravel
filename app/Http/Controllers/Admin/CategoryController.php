<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Category::class);

        $categories = Category::query()
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $this->authorize('create', Category::class);

        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::query()->create([
            'name' => $request->validated('name'),
            'slug' => $request->validated('slug'),
            'description' => $request->validated('description'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('status', __('Category created.'));
    }

    public function edit(Category $category): View
    {
        $this->authorize('update', $category);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update([
            'name' => $request->validated('name'),
            'slug' => $request->validated('slug'),
            'description' => $request->validated('description'),
            'is_active' => $request->boolean('is_active', $category->is_active),
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('status', __('Category updated.'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);

        if ($category->courses()->exists()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('warning', __('Cannot delete a category that has courses.'));
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('status', __('Category deleted.'));
    }

    public function activate(Category $category): RedirectResponse
    {
        $this->authorize('update', $category);
        $category->update(['is_active' => true]);

        return back()->with('status', __('Category activated.'));
    }

    public function deactivate(Category $category): RedirectResponse
    {
        $this->authorize('update', $category);
        $category->update(['is_active' => false]);

        return back()->with('status', __('Category deactivated.'));
    }
}
