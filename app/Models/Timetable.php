<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Timetable extends Model { use SoftDeletes; protected $fillable=['admin_id','teacher_id','day','start_time','end_time','classroom','subject']; public function teacher(){return $this->belongsTo(User::class,'teacher_id');} public function admin(){return $this->belongsTo(User::class,'admin_id');} }
