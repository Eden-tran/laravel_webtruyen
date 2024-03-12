<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    use RegistersUsers;
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->redirectTo = route('register');
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ],
            [
                'required' => ':attribute yêu cầu phải nhập',
                'string' => ':attribute phải là chuỗi',
                'max' => ':attribute không được lớn hơn :max ký tự',
                'email' => ':attribute không đúng định dạng email',
                'unique' => ':attribute đã được sử dụng',
                'confirmed' => ':attribute phải giống mật khẩu',
            ],
            [
                'name' => 'Họ tên',
                'email' => 'Địa chỉ email',
                'password' => 'Mật khẩu',
                'password_confirmation' => 'Nhập lại mật khẩu',
            ]
        );
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    //! custom by Quang
    protected function create(array $data)
    {
        //! custom by Quang
        // create by register
        $user_id = 0;
        //Create by user
        $group_id = 2;
        // user only read manga and adjust they information cannot login into dashboard
        $avatar = 'default.jpg';
        $active = 2;
        //! custom by Quang

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            //! custom by quang
            'password' => Hash::make($data['password']),
            'group_id' => $group_id,
            'user_id ' => $user_id,
            'avatar' => $avatar,
            'active' => $active,
            //! custom by quang

        ]);
    }
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath())->with('msg', 'đăng ký thành công');
    }
}
