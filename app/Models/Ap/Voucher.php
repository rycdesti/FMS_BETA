<?php

namespace App\Models\Ap;

use App\Models\BaseModel;

/**
 * @property integer $id
 * @property integer $recurring_payment_id
 * @property integer $bank_id
 * @property integer $check_id
 * @property string $voucher_no
 * @property string $date
 * @property string $document_type
 * @property string $document_no
 * @property string $explanation
 * @property string $check_date
 * @property float $amount
 * @property string $tax_id
 * @property string $last_updated
 * @property string $status
 * @property string $prepared_by
 * @property string $checked_by
 * @property string $reviewed_by
 * @property string $noted_by
 * @property string $approved_by
 * @property string $date_cancelled
 * @property string $cancelled_by
 * @property string $logs
 * @property string $last_modified
 * @property string $created_at
 * @property string $updated_at
 * @property RecurringPayment $recurringPayment
 * @property Bank $bank
 * @property Check $check
 */
class Voucher extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ap.vouchers';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['recurring_payment_id', 'bank_account_id', 'check_id', 'voucher_no', 'date', 'document_type', 'document_no', 'explanation', 'check_date', 'amount', 'withholding_tax_id', 'last_updated', 'status', 'prepared_by', 'checked_by', 'recommended_by', 'approved_by', 'date_cancelled', 'cancelled_by', 'logs', 'last_modified', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function voucherDistributions()
    {
        return $this->hasMany(VoucherDistribution::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recurringPayment()
    {
        return $this->belongsTo(RecurringPayment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function check()
    {
        return $this->belongsTo(Check::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function withholdingTax()
    {
        return $this->belongsTo(WithholdingTax::class);
    }
}
