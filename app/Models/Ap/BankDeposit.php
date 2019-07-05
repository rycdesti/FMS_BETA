<?php

namespace App\Models\Ap;

use App\Models\BaseModel;

/**
 * @property integer $id
 * @property integer $bank_account_id
 * @property string $date_deposit
 * @property string $time_deposit
 * @property string $ref_no
 * @property float $cash_deposit
 * @property string $logs
 * @property string $last_modified
 * @property string $created_at
 * @property string $updated_at
 * @property BankAccount $bankAccount
 * @property mixed checkDeposits
 */
class BankDeposit extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ap.bank_deposits';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['bank_account_id', 'date_deposit', 'time_deposit', 'ref_no', 'cash_deposit', 'logs', 'last_modified', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function checkDeposits() {
        return $this->hasMany(CheckDeposit::class);
    }
}
