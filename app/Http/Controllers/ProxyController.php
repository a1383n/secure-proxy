<?php

namespace App\Http\Controllers;

use App\Services\ProxyService;
use App\Services\ResolverService;
use Illuminate\Http\Request;

class ProxyController extends Controller
{
    function resolveIp(Request $request)
    {
        $request->validate([
            'q' => ['required', 'string', 'regex:/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/im'],
            'ip' => ['required', 'ip']
        ]);

        $domain = $request->input('q');

        if ($domain[-1] === '.') {
            $domain = substr($domain, 0, -1);
        }

        $resolver = app(ResolverService::class);
        $proxy = app(ProxyService::class);

        if ($proxy->isDomainAllowed($domain)) {
            if ($upstream = $proxy->getUpstream()) {
                return response(['ok' => true, 'result' => ['domain' => $domain, 'address' => $upstream]]);
            } else {
                return response(['ok' => false, 'message' => 'No upstream server available'], 503);
            }
        }

        $address = $resolver->resolve($domain);

        if ($address !== false) {
            return response(['ok' => true, 'result' => ['status' => 'bypass', 'domain' => $domain, 'address' => $address]]);
        } else {
            return response(['ok' => false, 'message' => 'No such host']);
        }
    }


    public function check(Request $request)
    {
        $request->validate([
            'q' => ['required', 'string', 'regex:/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/im'],
            'ip' => ['required', 'ip'],
        ]);

        $domain = $request->input('q');

        $proxy = app(ProxyService::class);

        if ($proxy->isDomainAllowed($domain)) {
            return response(['ok' => true]);
        } else {
            return response(['ok' => false]);
        }
    }
}
