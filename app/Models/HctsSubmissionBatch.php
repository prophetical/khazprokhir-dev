<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HctsSubmissionBatch extends Model
{
    protected $guarded = ['id'];

    public function submission()
    {
        return $this->belongsTo(HctsSubmission::class, 'hcts_submission_id');
    }
}
