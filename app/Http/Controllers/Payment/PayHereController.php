<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Services\EnrollmentService;
use App\Services\PayHereService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayHereController extends Controller
{
    public function return(Request $request): RedirectResponse
    {
        return redirect()->route('student.enrollments.index')
            ->with('status', __('Payment completed. Your enrollment will update shortly.'));
    }

    public function notify(Request $request, PayHereService $payHere, EnrollmentService $enrollmentService): \Illuminate\Http\Response
    {
        $payload = $request->all();
        if (! $payHere->notifyIsValid($payload)) {
            return response('INVALID', 400);
        }

        $orderId = (string) ($payload['order_id'] ?? '');
        if (! ctype_digit($orderId)) {
            return response('BAD_ORDER', 400);
        }

        $enrollment = Enrollment::query()->find((int) $orderId);
        if ($enrollment === null) {
            return response('NOT_FOUND', 404);
        }

        $status = (int) ($payload['status_code'] ?? 0);
        if ($status === 2) {
            $paymentId = (string) ($payload['payment_id'] ?? $payload['payhere_payment_id'] ?? $orderId);
            $enrollmentService->markPayHerePaid($enrollment, $paymentId);
        }

        return response('OK', 200);
    }

    public function checkoutForm(Enrollment $enrollment, PayHereService $payHere): View|RedirectResponse
    {
        $user = request()->user();
        if ($user === null || $enrollment->student_id !== $user->id) {
            abort(403);
        }

        if ($enrollment->payment_method !== Enrollment::PAYMENT_PAYHERE) {
            abort(404);
        }

        $enrollment->load('course');

        $amount = (float) $enrollment->amount_lkr;
        $currency = (string) config('payhere.currency', 'LKR');
        $orderId = (string) $enrollment->id;
        $hash = $payHere->checkoutHash($orderId, $amount, $currency);

        return view('student.payhere-checkout', [
            'enrollment' => $enrollment,
            'hash' => $hash,
            'amount' => number_format($amount, 2, '.', ''),
            'currency' => $currency,
            'orderId' => $orderId,
        ]);
    }
}
