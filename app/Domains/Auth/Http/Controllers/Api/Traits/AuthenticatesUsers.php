<?php

namespace App\Domains\Auth\Http\Controllers\Api\Traits;

use App\Domains\Auth\Http\Resources\UserResource;
use App\Domains\Auth\Models\User;
use App\Domains\Candidate\Models\Candidate;
use App\Domains\Leader\Models\Leader;
use App\Http\Requests\Api\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use F9Web\ApiResponseHelpers;
trait AuthenticatesUsers
{
    use ThrottlesLogins;
    use ApiResponseHelpers;
    use UserMisc;

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $request->merge([ 'phone' => $request->get('username') ]);

        if ($this->attemptLogin($request)) {
            $token = auth()->user()->createToken($request->get('device_name'), $this->permissions(auth()->user())->toArray());

            $candidateDetails = $this->getCandidateDetails();
            $roles = auth()->user()->roles->toArray();
            $role = [
                'type' => $roles[0]['type'],
                'name' => $roles[0]['name']
            ];
            $leader = null;

            $leaderScope = null;
            if(auth()->user()->hasRole('Purok Leader') || auth()->user()->hasRole('Barangay Leader'))
            {
                $leader = Leader::where('user_id', auth()->user()->id)->first();
                $leaderScope = $this->getLeaderScope($leader);
            }

            $result = [
                'access_token' => $token->plainTextToken,
                'user' => new UserResource(auth()->user()),
                'role' => $role,
                'role_details' => $leader,
                'scope' => $leaderScope,
                'address' => $this->address(auth()->user()),
                'permissions' => $this->permissions(auth()->user()),
                'candidate' => $candidateDetails
            ];

            return $this->respondWithSuccess($result);
        }

        $this->incrementLoginAttempts($request);
        return $this->respondError('Login Attemp Failed');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
                    ? new JsonResponse([], 204)
                    : redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        //
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'phone';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
