<?php
namespace App\Domains\Misc\Traits;

use App\Domains\Misc\Models\Address;
use App\Domains\Misc\Models\DocumentType;

trait HasDocumentType
{
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

}
