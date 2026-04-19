<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\View\View;

class CourseDetailController extends Controller
{
    public function __invoke(Course $course): View
    {
        $this->authorize('viewCatalogDetail', $course);

        $course->load(['category', 'lecturer']);

        return view('student.catalog.show', compact('course'));
    }
}
