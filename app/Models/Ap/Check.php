<?php

namespace App\Models\Ap;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $bank_account_id
 * @property string $acct_no
 * @property string $check_from
 * @property string $check_to
 * @property string $check_no
 * @property string $voucher_no
 * @property string $voided
 * @property string $date_voided
 * @property string $voided_by
 * @property string $logs
 * @property string $last_modified
 * @property string $remarks
 * @property string $created_at
 * @property string $updated_at
 * @property BankAccount $bankAccount
 * @property mixed vouchers
 */
class Check extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ap.checks';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['bank_account_id', 'check_from', 'check_to', 'check_no', 'voucher_no', 'voided', 'date_voided', 'voided_by', 'logs', 'last_modified', 'remarks', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vouchers() {
        return $this->hasMany(Voucher::class);
    }

    public function scopeGroupCheck($query)
    {
        return $query
            ->groupBy('bank_account_id')
            ->groupBy('check_from')
            ->groupBy('check_to')
            ->groupBy('logs')
            ->groupBy('created_at')
            ->orderByRaw('len(check_from)')
            ->orderBy('check_from');
    }
}
