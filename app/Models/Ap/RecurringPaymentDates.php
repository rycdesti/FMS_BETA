<?php

namespace App\Models\Ap;

use App\Models\BaseModel;

/**
 * @property integer $id
 * @property integer $recurring_payment_id
 * @property int $month
 * @property int $day
 * @property int $weekday
 * @property string $is_current
 * @property string $frequency_type
 * @property string $logs
 * @property string $created_at
 * @property string $updated_at
 */
class RecurringPaymentDates extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ap.recurring_payment_dates';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['recurring_payment_id', 'month', 'day', 'weekday', 'is_current', 'frequency_type', 'logs', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recurringPayment()
    {
        return $this->belongsTo(RecurringPayment::class);
    }
}
