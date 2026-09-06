<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class UserController extends Controller {
 public function index(Request $r){$users=User::query()->when($r->search,fn($q,$v)=>$q->where(fn($x)=>$x->where('name','like',"%$v%")->orWhere('email','like',"%$v%")->orWhere('phone','like',"%$v%")))->when($r->role,fn($q,$v)=>$q->where('role',$v))->when($r->status!==null&&$r->status!=='',fn($q)=>$q->where('is_active',$r->boolean('status')))->latest()->paginate(15)->withQueryString();return view('users.index',compact('users'));}
 public function create(){return view('users.form',['user'=>new User]);}
 public function store(Request $r){$d=$this->valid($r);$d['password']=$d['password']??'password';User::create($d);return redirect()->route('admin.users.index')->with('success','User created successfully.');}
 public function edit(User $user){return view('users.form',compact('user'));}
 public function update(Request $r,User $user){$d=$this->valid($r,$user);if(empty($d['password']))unset($d['password']);if(($d['role']??$user->role)!=='teacher')$d['supervisor_id']=null;$user->update($d);return back()->with('success','User updated successfully.');}
 public function toggle(User $user){abort_if($user->is(request()->user()),422,'You cannot deactivate your own account.');$user->update(['is_active'=>!$user->is_active]);return back()->with('success','Account status updated successfully.');}
 public function destroy(User $user){abort_if($user->is(request()->user()),422); if($user->teachingReports()->exists()||$user->evaluationsReceived()->exists())return back()->withErrors(['user'=>'Deactivate accounts with historical records instead of deleting them.']);$user->delete();return back()->with('success','User deleted successfully.');}
 private function valid(Request $r,?User $u=null):array{return $r->validate(['name'=>'required|string|max:100','email'=>['required','email',Rule::unique('users')->ignore($u)],'phone'=>['required','string','max:20',Rule::unique('users')->ignore($u)],'role'=>['required',Rule::in(User::ROLES)],'subject'=>'nullable|string|max:100','grade'=>'nullable|string|max:50','password'=>[$u?'nullable':'required','string','min:8','confirmed'],'is_active'=>'sometimes|boolean']);}
}
