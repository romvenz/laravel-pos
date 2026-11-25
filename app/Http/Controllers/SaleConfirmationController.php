<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class SaleConfirmationController extends Controller
{
    public function show(Request $request, Sale $sale)
    {
        abort_unless($sale->customer_id !== null, 404);

        $confirmUrl = null;

        if ($sale->confirmed_at === null) {
            $confirmUrl = URL::temporarySignedRoute(
                'sales.confirm.store',
                Carbon::createFromTimestamp((int) $request->query('expires')),
                ['sale' => $sale->id]
            );
        }

        return view('sales.confirm', compact('sale', 'confirmUrl'));
    }

    public function store(Sale $sale)
    {
        abort_unless($sale->customer_id !== null, 404);

        Sale::whereKey($sale->id)
            ->whereNull('confirmed_at')
            ->whereNotNull('customer_id')
            ->update(['confirmed_at' => now()]);

        return view('sales.confirm', [
            'sale' => $sale->fresh(),
            'confirmUrl' => null,
        ]);
    }
}