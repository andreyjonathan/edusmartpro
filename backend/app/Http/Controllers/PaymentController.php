<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentCategory;
use App\Models\Student;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        return Inertia::render('Payments/Index', [
            'payments' => $this->paymentService->getPayments($request->all()),
            'filters' => $request->only(['search', 'status', 'category_id', 'month']),
            'categories' => PaymentCategory::where('is_active', true)->get(),
            'months' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        ]);
    }

    public function create()
    {
        return Inertia::render('Payments/Create', [
            'students' => Student::where('status', 'Active')->select('id', 'name', 'nis')->get(),
            'categories' => PaymentCategory::where('is_active', true)->get(),
            'months' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'payment_category_id' => 'required|exists:payment_categories,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'month' => 'nullable|string',
            'year' => 'nullable|integer',
            'status' => 'required|in:Paid,Pending,Failed',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $this->paymentService->createPayment($validated);

        return redirect()->route('payments.index')->with('message', 'Payment recorded successfully.');
    }

    public function edit(Payment $payment)
    {
        return Inertia::render('Payments/Edit', [
            'payment' => $payment->load(['student', 'category']),
            'students' => Student::where('status', 'Active')->select('id', 'name', 'nis')->get(),
            'categories' => PaymentCategory::where('is_active', true)->get(),
            'months' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        ]);
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'payment_category_id' => 'required|exists:payment_categories,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'month' => 'nullable|string',
            'year' => 'nullable|integer',
            'status' => 'required|in:Paid,Pending,Failed',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $this->paymentService->updatePayment($payment, $validated);

        return redirect()->route('payments.index')->with('message', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $this->paymentService->deletePayment($payment);

        return redirect()->route('payments.index')->with('message', 'Payment record deleted successfully.');
    }
}
