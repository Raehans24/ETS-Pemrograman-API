<?php

namespace App\Http\Controllers;

use App\Models\SupermarketSale;
use Illuminate\Http\Request;

class SupermarketSaleController extends Controller
{
    private function checkPort(Request $request)
    {
        if ($request->getPort() != 3111) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak!',
                'current_port' => $request->getPort()
            ], 403);
        }
        return null;
    }

    public function index()
    {
        // Mengambil data supermarket (15 data per halaman agar ringan)
        $sales = SupermarketSale::paginate(15);

        return response()->json([
            'status' => 'success',
            'message' => 'Data transaksi supermarket berhasil diambil',
            'data' => $sales
        ], 200);
    }
    
    public function search(Request $request)
    {
        if ($error = $this->checkPort($request)) return $error;

        $query = SupermarketSale::query();

        // Filter berdasarkan description dari payload JSON
        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->input('description') . '%');
        }

        // Filter berdasarkan country dari payload JSON
        if ($request->filled('country')) {
            $query->where('country', 'like', '%' . $request->input('country') . '%');
        }

        // Filter berdasarkan price range dari payload JSON
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Eksekusi dengan pagination
        $sales = $query->paginate(15);

        return response()->json([
            'status' => 'success',
            'message' => 'Data hasil pencarian payload berhasil dimuat',
            'data' => $sales
        ], 200);
    }

    public function statistics(Request $request)
    {
        if ($error = $this->checkPort($request)) return $error;

        $country = $request->input('country');

        $query = SupermarketSale::query();
        if ($country) {
            $query->where('country', $country);
        }

        $totalTransactions = $query->count();
        $averagePrice = $query->avg('price');
        $totalQuantity = $query->sum('quantity');

        return response()->json([
            'status' => 'success',
            'message' => 'Statistik data supermarket',
            'filter_country' => $country ?? 'Semua Negara',
            'statistics' => [
                'total_transactions' => $totalTransactions,
                'average_price' => round($averagePrice, 2),
                'total_quantity_sold' => $totalQuantity
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        if ($error = $this->checkPort($request)) return $error;

        // Validasi input payload
        $validated = $request->validate([
            'invoice' => 'required|string',
            'stock_code' => 'required|string',
            'description' => 'nullable|string',
            'quantity' => 'required|integer',
            'invoice_date' => 'required|date',
            'price' => 'required|numeric',
            'customer_id' => 'nullable|string',
            'country' => 'required|string',
        ]);

        $sale = SupermarketSale::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data transaksi baru berhasil ditambahkan',
            'data' => $sale
        ], 201);
    }
}
