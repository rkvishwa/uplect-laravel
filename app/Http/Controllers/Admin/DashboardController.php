<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'courses' => Course::query()->count(),
            'courses_active' => Course::query()->where('status', Course::STATUS_ACTIVE)->count(),
            'categories' => Category::query()->count(),
            'students' => User::query()->where('role', User::ROLE_STUDENT)->count(),
            'lecturers' => User::query()->where('role', User::ROLE_LECTURER)->count(),
            'enrollments_active' => Enrollment::query()->where('status', Enrollment::STATUS_ACTIVE)->count(),
            'enrollments_pending' => Enrollment::query()->pending()->count(),
        ];

        $enrollmentTrend = ['labels' => [], 'values' => []];
        for ($i = 5; $i >= 0; $i--) {
            $start = now()->subMonths($i)->startOfMonth();
            $end = now()->subMonths($i)->endOfMonth();
            $enrollmentTrend['labels'][] = $start->isoFormat('MMM');
            $enrollmentTrend['values'][] = Enrollment::query()
                ->whereBetween('created_at', [$start, $end])
                ->count();
        }

        $categories = Category::query()->withCount('courses')->orderBy('name')->get();
        if ($categories->isEmpty()) {
            $coursesByCategory = [
                'labels' => [__('No categories yet')],
                'values' => [0],
            ];
        } else {
            $coursesByCategory = [
                'labels' => $categories->pluck('name')->all(),
                'values' => $categories->pluck('courses_count')->all(),
            ];
        }

        $statusConfig = [
            [Enrollment::STATUS_PENDING, __('Pending')],
            [Enrollment::STATUS_ACTIVE, __('Active')],
            [Enrollment::STATUS_DECLINED, __('Declined')],
            [Enrollment::STATUS_COMPLETED, __('Completed')],
            [Enrollment::STATUS_DROPPED, __('Dropped')],
        ];
        $enrollmentStatus = ['labels' => [], 'values' => []];
        foreach ($statusConfig as [$status, $label]) {
            $enrollmentStatus['labels'][] = $label;
            $enrollmentStatus['values'][] = Enrollment::query()->where('status', $status)->count();
        }

        $chartStrings = [
            'newEnrollments' => __('New enrollments'),
            'enrollmentsByStatus' => __('Enrollments by status'),
            'coursesPerCategory' => __('Courses per category'),
            'courses' => __('Courses'),
        ];

        $chartData = [
            'enrollmentTrend' => $enrollmentTrend,
            'coursesByCategory' => $coursesByCategory,
            'enrollmentStatus' => $enrollmentStatus,
            'strings' => $chartStrings,
        ];

        return view('admin.dashboard', compact('stats', 'chartData'));
    }
}
