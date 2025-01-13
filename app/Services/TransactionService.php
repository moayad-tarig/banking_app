<?php

namespace App\Services;

use App\Dtos\TransactionDto;
use App\Enum\TransactionCategoryEnum;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Str;

class TransactionService
{
    public function __construct()
    {
        //
    }

    /**
     * @return Builder
     */
    public function modelQuery(): Builder
    {
        return Transaction::query();
    }



    public function generateReferenceNumber(): string {
        return 'TF' . '/' . Carbon::now()->getTimestampMs() . Str::random(4);
    }

    public function createTransaction(TransactionDto $transactionDto): Transaction {
    $data = [];

    if($transactionDto->getCategory() == TransactionCategoryEnum::DEPOSIT->value) {
        $data = $transactionDto->forDepositToArray($transactionDto);
    } else if($transactionDto->getCategory() == TransactionCategoryEnum::WITHDRAWAL->value) {
        $data = $transactionDto->forWithdrawalToArray($transactionDto);
    }
    $transaction = $this->modelQuery()->create($data);

    return $transaction;
    

}

public function updateTransactionBalance(string $reference, float|int $balance)
{
    $this->modelQuery()->where('reference', $reference)->update([
        'balance' => $balance,
        'confirmed' => true
    ]);
}
public function updateTransferID(string $reference, int $transferID)
    {
        $this->modelQuery()->where('reference', $reference)->update([
            'transfer_id' => $transferID
        ]);
    }

public function getTransactionsByUserId(int $userID, Builder $builder): Builder
{
    return $builder->where('user_id', $userID);
}

}

