<?php

namespace App\Models\Requisition;

use App\Models\Ap\CheckPaymentRequest;
use App\Models\BaseModel;

/**
 * @property integer $id
 * @property string $payment_term_name
 * @property string $percentage
 * @property string $disabled
 * @property string $date_disabled
 * @property string $disabled_by
 * @property string $logs
 * @property string $last_modified
 * @property string $created_at
 * @property string $updated_at
 */
class PaymentTerm extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'requisition.payment_terms';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['payment_term_name', 'percentage', 'disabled', 'date_disabled', 'disabled_by', 'logs', 'last_modified', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function checkPaymentRequests() {
        return $this->hasMany(CheckPaymentRequest::class);
    }
}
