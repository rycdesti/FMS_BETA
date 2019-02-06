<?php

namespace App\Models\Requisition;

use App\Models\Ap\Bank;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $description
 * @property string $code
 * @property string $symbol
 * @property string $disabled
 * @property string $date_disabled
 * @property string $disabled_by
 * @property string $logs
 * @property string $last_modified
 * @property string $created_at
 * @property string $updated_at
 */
class Currency extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'requisition.currencies';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['description', 'currency_code', 'symbol', 'disabled', 'date_disabled', 'disabled_by', 'logs', 'last_modified', 'created_at', 'updated_at'];

    protected $dates = ['created_at', 'updated_at', 'date_disabled'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function banks() {
        return $this->hasMany(Bank::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function suppliers() {
        return $this->hasMany(Supplier::class);
    }
}
