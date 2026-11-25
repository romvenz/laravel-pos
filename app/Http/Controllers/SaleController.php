<?php

namespace App\Http\Controllers;

use App\Mail\SaleReceipt;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function index()
    {
        return view('sales.index', [
            'sales' => Sale::with(['product', 'customer', 'staff'])
                ->latest()
                ->paginate(20),
        ]);
    }

    public function create()
    {
        return view('sales.form', [
            'products' => Product::orderBy('name')->get(),
            'customers' => Customer::orderBy('full_name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:1000000'],
        ]);

        $sale = DB::transaction(function () use ($data, $request) {
            $product = Product::whereKey($data['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($product->stock_quantity < $data['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => "Only {$product->stock_quantity} units are available.",
                ]);
            }

            $priceInCents = (int) round((float) $product->price * 100);

            if ($priceInCents * $data['quantity'] > 9_999_999_999) {
                throw ValidationException::withMessages([
                    'quantity' => 'Sale total exceeds the supported limit.',
                ]);
            }

            $updated = Product::whereKey($product->id)
                ->where('stock_quantity', '>=', $data['quantity'])
                ->decrement('stock_quantity', $data['quantity']);

            if (! $updated) {
                throw ValidationException::withMessages([
                    'quantity' => 'Stock changed. Please try again.',
                ]);
            }

            return Sale::create([
                'product_id' => $product->id,
                'customer_id' => $data['customer_id'] ?? null,
                'sold_by' => $request->user()->id,
                'quantity' => $data['quantity'],
                'total_price' => number_format(
                    $priceInCents * $data['quantity'] / 100,
                    2,
                    '.',
                    ''
                ),
            ]);
        }, 3);

        $sale->load(['product', 'customer', 'staff']);

        if ($sale->customer) {
            try {
                Mail::to($sale->customer->email)->send(
                    new SaleReceipt($sale)
                );

                $message = 'Sale recorded and customer email sent.';
            } catch (\Throwable $exception) {
                Log::error('Sale notification failed', [
                    'sale_id' => $sale->id,
                    'error' => $exception->getMessage(),
                ]);

                $message = 'Sale recorded, but the email failed. Check your Mailtrap settings.';
            }
        } else {
            $message = 'Sale recorded. Select a customer to send an email.';
        }

        return redirect()
            ->route('sales.index')
            ->with('status', $message);
    }
}