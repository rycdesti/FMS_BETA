<?php

namespace App\Models\Ap;

use App\Models\BaseModel;
use App\Models\Requisition\PaymentTerm;
use App\Models\Requisition\Supplier;

/**
 * @property integer $id
 * @property integer $supplier_id
 * @property integer $payment_term_id
 * @property string $request_date
 * @property string $request_type
 * @property integer $po_id
 * @property string $supplier_name
 * @property string $particulars
 * @property float $amount
 * @property integer $batch_code
 * @property string $requested_by
 * @property string $status
 * @property string $logs
 * @property string $last_modified
 * @property string $created_at
 * @property string $updated_at
 * @property Supplier $supplier
 * @property PaymentTerm $paymentTerm
 */
class CheckPaymentRequest extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'requisition.check_preparation_requests';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['supplier_id', 'payment_term_id', 'request_date', 'request_type', 'po_id', 'supplier_name', 'particulars', 'amount', 'batch_code', 'requested_by', 'status', 'logs', 'last_modified', 'created_at', 'updated_at'];

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
    public function paymentTerm()
    {
        return $this->belongsTo(PaymentTerm::class);
    }
}
