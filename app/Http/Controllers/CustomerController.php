<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customers.index', [
            'customers' => Customer::latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('customers.form', [
            'customer' => new Customer(),
        ]);
    }

    public function store(Request $request)
    {
        Customer::create($this->validatedData($request));

        return redirect()
            ->route('customers.index')
            ->with('status', 'Customer created.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.form', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $customer->update($this->validatedData($request, $customer));

        return redirect()
            ->route('customers.index')
            ->with('status', 'Customer updated.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('status', 'Customer deleted.');
    }

    private function validatedData(
        Request $request,
        ?Customer $customer = null
    ): array {
        return $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('customers')->ignore($customer?->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);
    }
}