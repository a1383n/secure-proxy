<?php

namespace App\Http\Controllers;

use App\Enums\DomianFilterType;
use App\Models\ResolveLog;
use App\Services\ClientFilterService;
use App\Services\DomainFilterService;
use App\Services\ResolverService;
use App\Services\UpstreamService;
use Illuminate\Http\Request;

class ProxyController extends Controller
{
    public function resolveIp(Request $request)
    {
        $request->validate([
            'q'  => ['required', 'string', 'regex:/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/im'],
            'ip' => ['required', 'ip'],
        ]);

        $domain = $request->input('q');
        $ip = $request->input('ip');

        if ($domain[-1] === '.') {
            $domain = substr($domain, 0, -1);
        }

        $resolver = app(ResolverService::class);
        $upstreamService = app(UpstreamService::class);
        $domainFilterService = app(DomainFilterService::class);
        $clientFilterService = app(ClientFilterService::class);

        if (!$clientFilterService->isClientAllowed($ip)) {
            return response(['ok' => false], 403);
        }

        switch ($domainFilterService->getDomainFilterMode($domain)) {
            case DomianFilterType::ALLOW:
                if ($upstream = $upstreamService->getUpstream()) {
                    ResolveLog::create(['domain' => $domain, 'resolved_ip' => $upstream, 'resolve_status' => 'resolved', 'filter_status' => 'allow', 'client_ip' => $ip]);

                    return response(['ok' => true, 'result' => ['status' => 'allowed', 'domain' => $domain, 'address' => $upstream]]);
                } else {
                    ResolveLog::create(['domain' => $domain, 'resolved_ip' => null, 'resolve_status' => 'failed', 'filter_status' => 'allow', 'client_ip' => $ip]);

                    return response(['ok' => false, 'message' => 'No upstream server available'], 503);
                }
            case DomianFilterType::BLOCK:
                ResolveLog::create(['domain' => $domain, 'resolved_ip' => null, 'resolve_status' => 'failed', 'filter_status' => 'block', 'client_ip' => $ip]);

                return response(['ok' => false, 'message' => 'Blocked']);
            case DomianFilterType::BYPASS:
                if ($address = $resolver->resolve($domain)) {
                    ResolveLog::create(['domain' => $domain, 'resolved_ip' => $address, 'resolve_status' => 'resolved', 'filter_status' => 'bypass', 'client_ip' => $ip]);

                    return response(['ok' => true, 'result' => ['status' => 'bypass', 'domain' => $domain, 'address' => $address]]);
                } else {
                    ResolveLog::create(['domain' => $domain, 'resolved_ip' => null, 'resolve_status' => 'failed', 'filter_status' => 'bypass', 'client_ip' => $ip]);

                    return response(['ok' => false, 'message' => 'No such host'], 404);
                }
            default:
//                ResolveLog::create(['domain' => $domain, 'resolved_ip' => null, 'resolve_status' => 'failed', 'filter_status' => 'bypass', 'client_ip' => $ip]);
                return response(['ok' => false, 'message' => 'No matching'], 404);
        }
    }

    public function check(Request $request)
    {
        $request->validate([
            'q'  => ['required', 'string', 'regex:/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/im'],
            'ip' => ['required', 'ip'],
        ]);

        $domain = $request->input('q');
        $ip = $request->input('ip');

        $clientFilterService = app(ClientFilterService::class);
        if (!$clientFilterService->isClientAllowed($ip)) {
            return response(['ok' => false], 403);
        }

        $domainFilterService = app(DomainFilterService::class);
        if ($domainFilterService->isDomainMatchFilterMode(DomianFilterType::ALLOW, $domain)) {
            return response(['ok' => true]);
        } else {
            return response(['ok' => false]);
        }
    }
}
