<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TeachingMaterial extends Model { protected $fillable=['uploaded_by','title','description','subject','grade','material_type','file_path','uploaded_at']; protected function casts():array{return ['uploaded_at'=>'datetime'];} public function uploader(){return $this->belongsTo(User::class,'uploaded_by');} }
