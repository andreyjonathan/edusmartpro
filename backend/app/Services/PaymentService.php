<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Pagination\LengthAwarePaginator;

class PaymentService
{
    public function getPayments(array $filters = []): LengthAwarePaginator
    {
        $query = Payment::with(['student', 'category']);

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('student', function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
                })->orWhere('reference_number', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['category_id']) && $filters['category_id'] !== '') {
            $query->where('payment_category_id', $filters['category_id']);
        }

        if (isset($filters['month']) && $filters['month'] !== '') {
            $query->where('month', $filters['month']);
        }

        return $query->latest()->paginate(10)->withQueryString();
    }

    public function createPayment(array $data): Payment
    {
        return Payment::create($data);
    }

    public function updatePayment(Payment $payment, array $data): bool
    {
        return $payment->update($data);
    }

    public function deletePayment(Payment $payment): ?bool
    {
        return $payment->delete();
    }
}
