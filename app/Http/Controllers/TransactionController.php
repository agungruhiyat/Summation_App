<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('customer', 'like', "%{$search}%");
        }

        $transactions = $query->paginate(5); // gunakan query yang sudah difilter
        return response()->json(["success" => true, "transactions" => $transactions]);
    }

    public function create()
    {
        //
    }

    public function store(StoreTransactionRequest $request)
    {
        $transactions = Transaction::create($request->validated());
        return response()->json(["status" => "success", "transactions" => $transactions]);
    }

    public function show(Transaction $transaction)
    {
        //
    }

    public function edit(Transaction $transaction)
    {
        //
    }

    public function update(UpdateTransactionRequest  $request, $id)
    {
        Transaction::where("id", $id)->update($request->validated());
        return response()->json(["success" => true]);
    }

    public function delete($id)
    {
        Transaction::where("id", $id)->delete();
        return response()->json(["success" => true]);
    }
}
