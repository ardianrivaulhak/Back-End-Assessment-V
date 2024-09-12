<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    /**
     * @OA\Schema(
     *     schema="Expense",
     *     type="object",
     *     @OA\Property(property="id", type="integer", format="int64", description="The ID of the expense"),
     *     @OA\Property(property="amount", type="integer", description="The amount of the expense"),
     *     @OA\Property(property="status_id", type="integer", description="The ID of the status"),
     *     @OA\Property(property="approver_id", type="integer", description="The ID of the approver")
     * )
     */

    use HasFactory;

    protected $fillable = [
        'amount',
        'status_id',
        'approver_id',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function approver()
    {
        return $this->belongsTo(Approver::class);
    }
}
