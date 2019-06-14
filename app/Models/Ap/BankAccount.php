<?php

namespace App\Models\Ap;

use App\Models\BaseModel;
use App\Models\Requisition\Currency;
use App\Models\System\Branch;

/**
 * @property integer $id
 * @property integer $bank_id
 * @property string $bank_address
 * @property string $acct_code
 * @property string $acct_no
 * @property string $acct_type
 * @property string $currency
 * @property float $beginning_balance
 * @property string $as_of
 * @property string $disabled
 * @property string $date_disabled
 * @property string $disabled_by
 * @property string $logs
 * @property string $last_modified
 * @property string $created_at
 * @property string $updated_at
 * @property Bank $bank
 * @property mixed checks
 * @property mixed branch
 */
class BankAccount extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ap.bank_accounts';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['bank_id', 'bank_address', 'acct_code', 'acct_no', 'acct_type', 'currency_id', 'branch_id', 'beginning_balance', 'as_of', 'disabled', 'date_disabled', 'disabled_by', 'logs', 'last_modified', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function checks()
    {
        return $this->hasMany(Check::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recurringPayments()
    {
        return $this->hasMany(RecurringPayment::class);
    }
}
