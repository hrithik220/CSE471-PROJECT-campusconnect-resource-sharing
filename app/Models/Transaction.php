<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id', 'owner_id', 'borrower_id',
        'issued_at', 'due_date', 'returned_at', 'status',
    ];

    protected $casts = [
        'issued_at'   => 'datetime',
        'due_date'    => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function resource()  { return $this->belongsTo(Resource::class); }
    public function owner()     { return $this->belongsTo(User::class, 'owner_id'); }
    public function borrower()  { return $this->belongsTo(User::class, 'borrower_id'); }
}


// ──────────────────────────────────────────────────────────────────────────────


class BorrowRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id', 'requester_id', 'proposed_pickup',
        'proposed_return', 'message', 'status', 'transaction_id',
    ];

    protected $casts = [
        'proposed_pickup' => 'datetime',
        'proposed_return' => 'datetime',
    ];

    public function resource()      { return $this->belongsTo(Resource::class); }
    public function requester()     { return $this->belongsTo(User::class, 'requester_id'); }
    public function transaction()   { return $this->belongsTo(Transaction::class); }
}
