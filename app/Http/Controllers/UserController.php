<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\UserRequest;
use App\Models\User;
use App\Repositories\Contract\UserContract;

class UserController extends Controller
{
    public function __construct(UserContract $userContract)
    {
        $this->user = $userContract;
        //routingに描きたいがresourceでまとめているため、controllerで指定
        $this->middleware('auth')->only(['index']);
    }

    public function index()
    {
        $user = Auth::user();

        return view('user.index')->with('user', $user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        return $this->user->registUser($request);
    }

    public function signup(Request $request)
    {
        if ($request->isMethod('post')) {
            $authinfo = [
                'email' => $request->email,
                'password' => $request->password,
            ];
            if (Auth::attempt($authinfo)) {
                return redirect()->route('user.index');
            } else {
                return redirect()->back()->with('message', 'Failed to login!');
            }
        }
    }

    public function login()
    {
        return view('user.login');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('user.login');
    }
}
