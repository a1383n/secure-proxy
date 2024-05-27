<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProxyController extends Controller
{
    function resolveIp(Request $request)
    {
        $request->validate([
            'q' => ['required', 'string', 'regex:/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/igm'],
            'ip' => ['required', 'ip']
        ]);


    }
}
