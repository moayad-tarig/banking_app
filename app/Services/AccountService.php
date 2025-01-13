<?php

namespace App\Services;

use App\Dtos\AccountDto;
use App\Dtos\DepositDto;
use App\Dtos\TransactionDto;
use App\Dtos\UserDto;
use App\Events\TransactionEvent;
use App\Exceptions\AccountNumberExistsException;
use App\Exceptions\DepositAmountToLowException;
use App\Exceptions\InvalidAccountNumberException;
use App\Interfaces\AccountServiceInterface;
use App\Models\Account;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class AccountService implements AccountServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly UserService        $userService,
        private readonly TransactionService $transactionService,
    )
    {
        //
    }


    public function modelQuery() : Builder {
        return Account::query();
    }

    public function hasAccountNumber(UserDto $userDto): bool
    {
        return $this->modelQuery()->where('user_id', $userDto->getId())->exists();
    }

    public function getAccount(int|string $accountNumberOrUserID): Account{
        return $this->modelQuery()->where('account_number', $accountNumberOrUserID)->orWhere('user_id', $accountNumberOrUserID)->first();
    }

      
    public function createAccountNumber(UserDto $userDto) : Account {

        if ($this->hasAccountNumber($userDto)) {
            throw new AccountNumberExistsException();
        }

        return  $this->modelQuery()->create([
           'account_number' => substr($userDto->getPhoneNumber(), -10),
            'user_id' => $userDto->getId(),
      
        ]);
       ;

    }  

    public function getAccountByAccountNumber(string $accountNumber): Account {
        return $this->modelQuery()->where('account_number', $accountNumber)->first();
    }
    public function getAccountByUserID(int $userID): Account {
        return $this->modelQuery()->where('user_id', $userID)->first();
    }

    // public function getAccountByUserID(int $userID): Account
    // {
    //     $account = $this->modelQuery()->where('user_id', $userID)->first();
    //     if (!$account) {
    //         throw new ANotFoundException("Account number could not be found");
    //     }
    //     /** @var Account $account */
    //     return $account;
    // }


    public function accountExist(Builder $accountQuery): void
    {
        if (!$accountQuery->exists()) {
            throw new InvalidAccountNumberException();
        }
    }

    public function deposit(DepositDto  $depositDto): TransactionDto {

        $minimum_depoit = 500;
        if($depositDto->getAmount() < $minimum_depoit) {
            throw new DepositAmountToLowException($minimum_depoit);
        }

        try {
            DB::beginTransaction();
            $transactionDto = new TransactionDto();

            $accountQuery = $this->modelQuery()->where('account_number', $depositDto->getAccountNumber());
            $this->accountExist($accountQuery);
            $lockedAccount = $accountQuery->lockForUpdate()->first();
            $accountDto = AccountDto::fromModel($lockedAccount);
            $refrence = $this->transactionService->generateReferenceNumber();
            $transactionDto = $transactionDto->forDeposit($accountDto ,$refrence ,$depositDto->getAmount(), $depositDto->getDescription());

            event(new TransactionEvent($transactionDto, $accountDto, $lockedAccount));

            

            DB::commit();
            return $transactionDto;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
