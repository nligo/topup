<?php

namespace App\Http\Controllers;

use App\Models\PassportUser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    public function checkUser(Request $request)
    {
        $username = $request->request->get('param','');
        $info = PassportUser::where('username',$username)->first();
        $status =  !empty($info) ? 'y' : 'n';
        $info = !empty($info) ? '' : '该账号不存在';
        return new JsonResponse(['status' => $status,'info' => $info]);
    }
}
