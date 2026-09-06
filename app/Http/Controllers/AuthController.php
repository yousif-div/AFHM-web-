<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller {
 public function create(){return view('auth.login');}
 public function store(Request $r){$data=$r->validate(['email'=>'required|email','password'=>'required|string']); if(!Auth::attempt($data)){return back()->withErrors(['email'=>'Invalid email or password.'])->onlyInput('email');} if(!$r->user()->is_active){Auth::logout(); return back()->withErrors(['email'=>'Account inactive. Please contact the administrator.']);} $r->session()->regenerate(); return redirect()->intended(route($r->user()->role.'.dashboard'));}
 public function destroy(Request $r){Auth::logout();$r->session()->invalidate();$r->session()->regenerateToken();return redirect()->route('login');}
}
