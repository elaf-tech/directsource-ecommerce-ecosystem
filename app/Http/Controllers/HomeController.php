<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $cat=Category::all();
        $brand=Brand::all();
         $product = Product::paginate(4);
        $sup=Supplier::all()->where('status', 'confirmed');
        return view('Users.index',compact('cat','brand','product','sup'));
    }
    public function profile()
{
    $user = Auth::user(); // المستخدم الحالي
    return view('Users.profile', compact('user'));
}

public function editprofile(){
    $user = Auth::user(); // المستخدم الحالي
    return view('Users.editprofile', compact('user'));
}
public function profileupdate(Request $request){
    $user = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'address' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
    ]);

    $user->update($request->all());

    return redirect()->route('profile')->with('success', 'تم تحديث الملف الشخصي بنجاح ✅');
}
}
