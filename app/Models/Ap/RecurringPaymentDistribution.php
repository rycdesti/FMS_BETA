<?php

namespace App\Models\Ap;

use App\Models\BaseModel;
use App\Models\Financial\ChartOfAccount;

/**
 * @property integer $id
 * @property integer $recurring_payment_id
 * @property integer $chart_of_account_id
 * @property string $typical_balance
 * @property float $amount
 * @property string $logs
 * @property string $last_modified
 * @property string $created_at
 * @property string $updated_at
 * @property ChartOfAccount $chartOfAccount
 * @property RecurringPayment $recurringPayment
 */
class RecurringPaymentDistribution extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ap.recurring_payment_distributions';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['recurring_payment_id', 'chart_of_account_id', 'typical_balance', 'amount', 'logs', 'last_modified', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recurringPayment()
    {
        return $this->belongsTo(RecurringPayment::class);
    }
}
