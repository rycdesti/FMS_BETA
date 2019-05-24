<?php

namespace App\Models\Ap;

use App\Models\BaseModel;
use App\Models\Financial\ChartOfAccount;

/**
 * @property integer $id
 * @property integer $voucher_id
 * @property integer $chart_of_account_id
 * @property string $typical_balance
 * @property float $amount
 * @property string $logs
 * @property string $last_modified
 * @property string $created_at
 * @property string $updated_at
 * @property Voucher $voucher
 * @property ChartOfAccount $chartOfAccount
 */
class VoucherDistribution extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ap.voucher_distributions';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['voucher_id', 'chart_of_account_id', 'typical_balance', 'amount', 'logs', 'last_modified', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class);
    }
}
