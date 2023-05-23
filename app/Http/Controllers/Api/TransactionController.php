<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $accountNumber): JsonResponse
    {
        /** @var Account $account */
        $account = Account::byNumber($accountNumber)->firstOrFail();

        if (auth()->user()->cannot('view', $account)) {
            abort(404, "Account does not exist.");
        }
        $date = Carbon::create(
            year: request()->query('year', now()->year),
            month: request()->query('month', now()->month)
        );
        if (request()->query('audit', false)) {
            return response()->json(
                TransactionResource::collection(
                    $account->transactions($date)->get()
                )
            );
        }
        return response()->json($account->groupMonthlyTransactions($date));
    }
}
