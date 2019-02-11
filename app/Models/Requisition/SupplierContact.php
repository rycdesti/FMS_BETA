<?php

namespace App\Models\Requisition;

use App\Models\BaseModel;

/**
 * @property integer $id
 * @property integer $supplier_id
 * @property string $contact_person
 * @property string $phone_number1
 * @property string $phone_number2
 * @property string $phone_number3
 * @property string $fax_number
 * @property string $logs
 * @property string $last_modified
 * @property string $created_at
 * @property string $updated_at
 * @property Supplier $supplier
 */
class SupplierContact extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'requisition.supplier_contacts';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['supplier_id', 'contact_person', 'phone_number1', 'phone_number2', 'phone_number3', 'fax_number', 'logs', 'last_modified', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
