<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCourseRequest;
use App\Http\Requests\Admin\UpdateCourseRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Course::class);

        $courses = Course::query()
            ->with(['category', 'lecturer'])
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('admin.courses.index', compact('courses'));
    }

    public function create(): View
    {
        $this->authorize('create', Course::class);

        $categories = Category::query()->where('is_active', true)->orderBy('name')->get();
        $lecturers = User::query()->where('role', User::ROLE_LECTURER)->orderBy('name')->get();
        $weekdays = Course::weekdayLabels();

        return view('admin.courses.create', compact('categories', 'lecturers', 'weekdays'));
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['image']);
        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('courses/images', 'public');
        }
        $data['image_path'] = $path;

        Course::query()->create($data);

        return redirect()->route('admin.courses.index')->with('status', __('Course created.'));
    }

    public function edit(Course $course): View
    {
        $this->authorize('update', $course);

        $categories = Category::query()->where('is_active', true)->orderBy('name')->get();
        $lecturers = User::query()->where('role', User::ROLE_LECTURER)->orderBy('name')->get();
        $weekdays = Course::weekdayLabels();

        return view('admin.courses.edit', compact('course', 'categories', 'lecturers', 'weekdays'));
    }

    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $data = $request->validated();
        unset($data['image']);
        if ($request->hasFile('image')) {
            if ($course->image_path) {
                Storage::disk('public')->delete($course->image_path);
            }
            $data['image_path'] = $request->file('image')->store('courses/images', 'public');
        }

        $course->update($data);

        return redirect()->route('admin.courses.index')->with('status', __('Course updated.'));
    }

    public function destroy(Course $course): RedirectResponse
    {
        $this->authorize('delete', $course);

        if ($course->hasBlockingEnrollments()) {
            return redirect()->route('admin.courses.index')
                ->with('warning', __('Cannot delete a course with active or completed enrollments.'));
        }

        if ($course->image_path) {
            Storage::disk('public')->delete($course->image_path);
        }

        $course->delete();

        return redirect()->route('admin.courses.index')->with('status', __('Course deleted.'));
    }

    public function activate(Course $course): RedirectResponse
    {
        $this->authorize('update', $course);
        $course->update(['status' => Course::STATUS_ACTIVE]);

        return back()->with('status', __('Course activated.'));
    }

    public function inactivate(Course $course): RedirectResponse
    {
        $this->authorize('update', $course);
        $course->update(['status' => Course::STATUS_INACTIVE]);

        return back()->with('status', __('Course deactivated.'));
    }
}
