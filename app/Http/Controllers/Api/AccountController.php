<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\StoreAccountRequest;
use App\Http\Resources\AccountResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Account;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(AccountResource::collection(
            Account::byUser(auth()->user())->get()
        ));
    }

    /**
     * @param StoreAccountRequest $request
     * @return JsonResponse
     */
    public function store(StoreAccountRequest $request): JsonResponse
    {
        return response()->json(
            new AccountResource(Account::create(array_merge($request->validated(), ['user_id' => auth()->id()]))),
            201
        );
    }

    /**
     * @param string $accountNumber
     * @return JsonResponse
     */
    public function show(string $accountNumber): JsonResponse
    {
        $account = Account::byNumber($accountNumber)->firstOrFail();
        if (auth()->user()->cannot('view', $account)) {
            abort(404, "Account does not exist.");
        }
        return response()->json(new AccountResource($account));
    }

    /**
     * @param StoreAccountRequest $request
     * @param string $accountNumber
     * @return JsonResponse
     */
    public function update(StoreAccountRequest $request, string $accountNumber): JsonResponse
    {
        $account = Account::byNumber($accountNumber)->firstOrFail();
        if (auth()->user()->cannot('update', $account)) {
            abort(404, "Account does not exist.");
        }
        $account->update($request->validated());
        return response()->json(new AccountResource($account));
    }
}
