<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request): ?RedirectResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:6'
            ]);

            if ($validator->fails()) {
                return back()->with(['error' => $validator->errors()->first()])->withInput($request->all());
            }

            $admin = User::query()->where('email', $request->get('email'))->first();

            if (empty($admin)) {
                return back()->with(['error' => __('admin.email_not_registered')])->withInput($request->all());
            }

            $credentials = $request->only(['email', 'password']);

            if (!auth()->attempt($credentials)) {
                return back()->with(['error' => __('admin.invalid_password')])->withInput($request->all());
            }

            return redirect()->route('admin.admins.list');
        } catch (Exception $e) {
            Log::error($e);
            return back()->with('error', __('admin.default_error_message'));
        }
    }

    public function forgotPassword(Request $request): ?RedirectResponse
    {
        return $this->extracted($request);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function extracted(Request $request): RedirectResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return back()->with(['error' => $validator->errors()->first()])->withInput($request->all());
            }

            $admin = User::query()->where('email', $request->get('email'))->first();

            if (empty($admin)) {
                return back()->with(['error' => __('admin.email_not_registered')])->withInput($request->all());
            }

            return redirect()->route('admin.admins.list');
        } catch (Exception $e) {
            Log::error($e);
            return back()->with('error', __('admin.default_error_message'));
        }
    }

    public function resetPassword(Request $request): ?RedirectResponse
    {
        return $this->extracted($request);
    }

    public function logout(): ?RedirectResponse
    {
        try {
            auth()->logout();

            return redirect()->route('admin.login.get');
        } catch (Exception $e) {
            Log::error($e);
            return back()->with('error', __('admin.default_error_message'));
        }
    }
}
