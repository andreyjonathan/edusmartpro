<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeService
{
    /**
     * Get a list of employees with filtering and pagination.
     */
    public function getEmployees(array $filters = []): LengthAwarePaginator
    {
        $query = Employee::query();

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate(10)->withQueryString();
    }

    /**
     * Create a new employee.
     */
    public function createEmployee(array $data): Employee
    {
        return Employee::create($data);
    }

    /**
     * Update an existing employee.
     */
    public function updateEmployee(Employee $employee, array $data): bool
    {
        return $employee->update($data);
    }

    /**
     * Delete an employee.
     */
    public function deleteEmployee(Employee $employee): ?bool
    {
        return $employee->delete();
    }
}
