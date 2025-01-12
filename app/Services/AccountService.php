<?php

namespace App\Services;

use App\Dtos\UserDto;
use App\Exceptions\AccountNumberExistsException;
use App\Exceptions\InvalidAccountNumberException;
use App\Interfaces\AccountServiceInterface;
use App\Models\Account;
use Illuminate\Database\Eloquent\Builder;

class AccountService implements AccountServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
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
}
