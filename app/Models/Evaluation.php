<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Evaluation extends Model { public const MIN_SCORE=1; public const MAX_SCORE=5; protected $fillable=['supervisor_id','teacher_id','score','comments','evaluated_at']; protected function casts():array{return ['evaluated_at'=>'datetime','score'=>'decimal:2'];} public function supervisor(){return $this->belongsTo(User::class,'supervisor_id');} public function teacher(){return $this->belongsTo(User::class,'teacher_id');} }
