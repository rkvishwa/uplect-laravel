<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $q = Course::query()->active()->with(['category', 'lecturer']);

        if ($request->filled('category_id')) {
            $q->where('category_id', $request->integer('category_id'));
        }

        $courses = $q->orderBy('title')->paginate(12)->withQueryString();
        $categories = Category::query()->where('is_active', true)->orderBy('name')->get();

        return view('student.catalog.index', compact('courses', 'categories'));
    }
}
