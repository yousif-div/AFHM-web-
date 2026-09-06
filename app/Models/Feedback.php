<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Feedback extends Model { protected $table='feedbacks'; protected $fillable=['supervisor_id','teacher_id','content','rating','submitted_at']; protected function casts():array{return ['submitted_at'=>'datetime'];} public function supervisor(){return $this->belongsTo(User::class,'supervisor_id');} public function teacher(){return $this->belongsTo(User::class,'teacher_id');} }
