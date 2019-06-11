<?php

namespace App\Models\Ap;

use App\Models\BaseModel;
use App\Models\Requisition\Supplier;

/**
 * @property integer $id
 * @property integer $supplier_id
 * @property string $document_no
 * @property string $duration_from
 * @property string $duration_to
 * @property string $is_duration
 * @property string $frequency
 * @property string $remarks
 * @property float $amount
 * @property string $disabled
 * @property string $date_disabled
 * @property string $disabled_by
 * @property string $logs
 * @property string $last_modified
 * @property string $created_at
 * @property string $updated_at
 * @property Supplier $supplier
 * @property mixed voucher
 * @property mixed recurringPaymentDates
 * @property mixed bankAccount
 */
class RecurringPayment extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ap.recurring_payments';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['supplier_id', 'bank_account_id', 'document_no', 'duration_from', 'duration_to', 'is_duration', 'frequency', 'remarks', 'amount', 'disabled', 'date_disabled', 'disabled_by', 'logs', 'last_modified', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recurringPaymentDates()
    {
        return $this->hasMany(RecurringPaymentDates::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recurringPaymentDistributions()
    {
        return $this->hasMany(RecurringPaymentDistribution::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function voucher()
    {
        return $this->hasOne(Voucher::class);
    }
}
