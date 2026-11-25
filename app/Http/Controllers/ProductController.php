<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index', [
            'products' => Product::latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('products.form', [
            'product' => new Product(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $data['image'] = Photo::save($request->file('image'), 'products');
        }

        try {
            Product::create($data);
        } catch (\Throwable $exception) {
            Photo::delete($data['image'] ?? null);
            throw $exception;
        }

        return redirect()
            ->route('products.index')
            ->with('status', 'Product created.');
    }

    public function edit(Product $product)
    {
        return view('products.form', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validatedData($request);
        $oldImage = $product->image;

        if ($request->hasFile('image')) {
            $data['image'] = Photo::save($request->file('image'), 'products');
        }

        try {
            $product->update($data);
        } catch (\Throwable $exception) {
            if (isset($data['image'])) {
                Photo::delete($data['image']);
            }

            throw $exception;
        }

        if (isset($data['image'])) {
            Photo::delete($oldImage);
        }

        return redirect()
            ->route('products.index')
            ->with('status', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        try {
            DB::transaction(fn () => $product->delete());
        } catch (\Illuminate\Database\QueryException $exception) {
            return back()->withErrors([
                'delete' => 'Products with sales history cannot be deleted.',
            ]);
        }

        Photo::delete($product->image);

        return redirect()
            ->route('products.index')
            ->with('status', 'Product deleted.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|gt:0|decimal:0,2|lte:99999999.99',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);
    }
}