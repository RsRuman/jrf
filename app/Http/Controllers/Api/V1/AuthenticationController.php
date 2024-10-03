<?php

namespace App\Http\Controllers\Api\V1;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Interfaces\AuthenticationInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;

#[AllowDynamicProperties]
class AuthenticationController extends Controller
{
    public function __construct(AuthenticationInterface $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * Sign up / Registration
     * @param SignUpRequest $request
     * @return JsonResponse
     */
    public function signUp(SignUpRequest $request): JsonResponse
    {
        $data             = $request->safe()->only(['name', 'email']);
        $data['password'] = bcrypt($request->input('password'));

        $user = $this->authentication->createUser($data);

        if (!$user) {
            return Response::json([
                'message' => 'Sign up is not successful. Please try later.'
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json([
            'message' => 'Sign up is successful.'
        ], HttpResponse::HTTP_CREATED);
    }

    /**
     * Login / Access token
     * @param SignInRequest $request
     * @return JsonResponse
     */
    public function login(SignInRequest $request): JsonResponse
    {
        $data = $request->safe()->only(['email', 'password']);

        $token = $this->authentication->getToken($data);

        if (!$token) {
            return Response::json([
                'message' => 'Invalid credentials.'
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json([
            'message' => 'Sign in is successful.',
            'token' => $token
        ], HttpResponse::HTTP_OK);
    }

    /**
     * Logout / Remove token
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user('api');

        $this->authentication->removeToken($user);

        return Response::json([
            'message' => 'Logout successful.'
        ], HttpResponse::HTTP_OK);
    }
}
