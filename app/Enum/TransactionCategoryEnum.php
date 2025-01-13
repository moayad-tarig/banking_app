<?php 
namespace App\Enum;

use Illuminate\Validation\Rules\Enum;
use Nette\Schema\Elements\Structure;

enum TransactionCategoryEnum  :string
{
    case  WITHDRAWAL = 'withdrawal';
    case DEPOSIT = 'deposit';
}