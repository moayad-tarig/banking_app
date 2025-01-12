<?php 

namespace App\Interfaces;

use App\Dtos\UserDto;
use App\Models\Account;
use Illuminate\Database\Eloquent\Builder;

interface AccountServiceInterface {


    public function modelQuery() : Builder;
    public function getAccount(int|string $accountNumberOrUserID): Account;
    public function createAccountNumber(UserDto $userDto) : Account;  
    public function getAccountByAccountNumber(string $accountNumber): Account;
    public function getAccountByUserID(int $userID): Account;
    public function accountExist(Builder $accountQuery): void;



}