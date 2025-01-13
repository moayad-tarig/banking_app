<?php

namespace App\Dtos;

use App\Interfaces\DtoInterface;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class AccountDto
{
    private int $id;
    private int $user_id;
    private string $account_number;
    private float $balance;
    private Carbon $created_at;
    private Carbon $updated_at;

    // Getter and Setter for $id
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // Getter and Setter for $user_id
    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    // Getter and Setter for $account_number
    public function getAccountNumber(): string
    {
        return $this->account_number;
    }

    public function setAccountNumber(string $account_number): void
    {
        $this->account_number = $account_number;
    }

    // Getter and Setter for $balance
    public function getBalance(): float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    // Getter and Setter for $created_at
    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    public function setCreatedAt(Carbon $created_at): void
    {
        $this->created_at = $created_at;
    }

    // Getter and Setter for $updated_at
    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(Carbon $updated_at): void
    {
        $this->updated_at = $updated_at;
    }



    public static function fromModel(Account $account): self
    {
        $dto = new self();
        $dto->setId($account->id);
        $dto->setUserId($account->user_id);
        $dto->setAccountNumber($account->account_number);
        $dto->setBalance($account->balance);
        return $dto;
    }
}
