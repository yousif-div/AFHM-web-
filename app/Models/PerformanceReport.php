<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PerformanceReport extends Model { protected $fillable=['generated_by','teacher_id','title','content','period','date_from','date_to','generated_at']; protected function casts():array{return ['content'=>'array','date_from'=>'date','date_to'=>'date','generated_at'=>'datetime'];} public function generator(){return $this->belongsTo(User::class,'generated_by');} public function teacher(){return $this->belongsTo(User::class);} }
