<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Passport\Repositories\ClientRepository;

class AddPassportClientToRequestMiddleware
{
    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->isTokenRoute($request) && $request->input('grant_type') === 'password') {
            $passwordClient = $this->clientRepository->getPasswordClient();

            if (!$passwordClient) {
                return $next($request);
            }

            $request->merge([
                'client_id' => $passwordClient->id,
                'client_secret' => $passwordClient->secret,
            ]);
        }

        return $next($request);
    }

    protected function isTokenRoute(Request $request): bool
    {
        // remove first / character from both routes
        $authRoute = ltrim(route('passport.token', [], false), '/');
        $currentRoute = ltrim($request->path(), '/');

        return $authRoute === $currentRoute;
    }
}
