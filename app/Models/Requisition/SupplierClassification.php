<?php

namespace App\Models\Requisition;

use App\Models\BaseModel;

/**
 * @property integer $id
 * @property string $classification_code
 * @property string $description
 * @property string $disabled
 * @property string $date_disabled
 * @property string $disabled_by
 * @property string $logs
 * @property string $last_modified
 * @property string $created_at
 * @property string $updated_at
 */
class SupplierClassification extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'requisition.supplier_classifications';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['classification_code', 'description', 'disabled', 'date_disabled', 'disabled_by', 'logs', 'last_modified', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function suppliers() {
        return $this->hasMany(Supplier::class);
    }
}
