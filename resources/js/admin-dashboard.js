import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

function isDark() {
    return document.documentElement.classList.contains('dark');
}

function chartText() {
    return isDark() ? '#a1a1aa' : '#52525b';
}

function chartGrid() {
    return isDark() ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
}

function parseData() {
    const el = document.getElementById('admin-dashboard-chart-data');
    if (!el?.textContent) {
        return null;
    }
    try {
        return JSON.parse(el.textContent);
    } catch {
        return null;
    }
}

function initCharts() {
    const data = parseData();
    if (!data?.enrollmentTrend) {
        return;
    }

    const lineCanvas = document.getElementById('chart-enrollment-trend');
    const doughnutCanvas = document.getElementById('chart-enrollment-status');
    const barCanvas = document.getElementById('chart-courses-by-category');

    const axisStyle = {
        grid: { color: chartGrid() },
        ticks: { color: chartText(), font: { family: 'Inter', size: 11 } },
        border: { display: false },
    };

    if (lineCanvas) {
        const t = data.enrollmentTrend;
        new Chart(lineCanvas, {
            type: 'line',
            data: {
                labels: t.labels,
                datasets: [
                    {
                        label: data.strings?.newEnrollments ?? 'Enrollments',
                        data: t.values,
                        borderColor: 'rgb(79, 70, 229)',
                        backgroundColor: 'rgba(79, 70, 229, 0.08)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: { color: chartText(), font: { family: 'Inter', size: 12 } },
                    },
                },
                scales: {
                    x: { ...axisStyle },
                    y: {
                        ...axisStyle,
                        beginAtZero: true,
                        ticks: { color: chartText(), font: { family: 'Inter', size: 11 }, precision: 0 },
                    },
                },
            },
        });
    }

    if (doughnutCanvas) {
        const s = data.enrollmentStatus;
        const colors = [
            'rgba(245, 158, 11, 0.85)',
            'rgba(34, 197, 94, 0.85)',
            'rgba(239, 68, 68, 0.85)',
            'rgba(59, 130, 246, 0.85)',
            'rgba(113, 113, 122, 0.75)',
        ];
        new Chart(doughnutCanvas, {
            type: 'doughnut',
            data: {
                labels: s.labels,
                datasets: [
                    {
                        data: s.values,
                        backgroundColor: colors.slice(0, s.labels.length),
                        borderWidth: 0,
                        hoverOffset: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: chartText(),
                            font: { family: 'Inter', size: 11 },
                            padding: 12,
                            boxWidth: 10,
                        },
                    },
                },
                cutout: '62%',
            },
        });
    }

    if (barCanvas) {
        const c = data.coursesByCategory;
        new Chart(barCanvas, {
            type: 'bar',
            data: {
                labels: c.labels,
                datasets: [
                    {
                        label: data.strings?.courses ?? 'Courses',
                        data: c.values,
                        backgroundColor: 'rgba(79, 70, 229, 0.75)',
                        borderRadius: 8,
                        borderSkipped: false,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    x: {
                        ...axisStyle,
                        ticks: {
                            color: chartText(),
                            font: { family: 'Inter', size: 11 },
                            maxRotation: 45,
                            minRotation: 0,
                        },
                    },
                    y: {
                        ...axisStyle,
                        beginAtZero: true,
                        ticks: { color: chartText(), font: { family: 'Inter', size: 11 }, precision: 0 },
                    },
                },
            },
        });
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCharts);
} else {
    initCharts();
}
