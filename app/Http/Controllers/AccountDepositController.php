<?php

namespace App\Http\Controllers;

use App\Dtos\DepositDto;
use App\Http\Requests\DepositRequest;
use App\Services\AccountService;
use App\Traits\ApiResponeTrait;
use Illuminate\Http\Request;

class AccountDepositController extends Controller
{
    use ApiResponeTrait;
    public function __construct(private readonly AccountService $accountService)
    {
        
    }
    


    public function store(DepositRequest $request){
        $depositDto = new DepositDto();
        $depositDto->setAccountNumber($request->account_number);
        $depositDto->setAmount($request->amount);
        $depositDto->setDescription($request->description);
        $this->accountService->deposit($depositDto);
        return $this->sendSuccess([], 'Deposit Successfully');
    }
}
