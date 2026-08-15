<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model {
    protected $fillable = ['complaint_id', 'admin_id', 'response'];

    public function complaint() {
        return $this->belongsTo(Complaint::class);
    }
    public function admin() {
        return $this->belongsTo(User::class, 'admin_id');
    }
}