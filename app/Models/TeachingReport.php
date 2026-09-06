<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TeachingReport extends Model { protected $fillable=['teacher_id','title','content','report_type','file_path','submitted_at']; protected function casts():array{return ['submitted_at'=>'datetime'];} public function teacher(){return $this->belongsTo(User::class);} }
