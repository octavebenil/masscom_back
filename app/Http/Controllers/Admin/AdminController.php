<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Pcsaini\ResponseBuilder\ResponseBuilder;

class AdminController extends Controller
{
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('admin.admins.index');
    }

    public function save(Request $request): RedirectResponse
    {
        if ($request->get('id')) {
            $user = User::query()->find($request->get('id'));

            if (!$user) {
                return back()->with(['error' => __('User not found')]);
            }
        }


        $data = $request->only(['name', 'email', 'password']);


        $data['password_text'] = $request->password;
        $data['password'] = bcrypt($request->password);

        if (isset($user)) {
            $user1 = User::query()->where('email', '!=', $user->email)->where("email", $request->email)->first();

            if ($user1) {
                return back()->with(['error' => __('User already exist')]);
            }

            $user->update($data);
            $message = __('Updated');
        } else {
            $user = User::query()->where("email", $request->email)->first();

            if ($user) {
                return back()->with(['error' => __('User already exist')]);
            }
            User::query()->create($data);
            $message = __('Created');
        }

        return to_route('admin.admins.list')->with(['success' => $message]);
    }

    public function create(): Factory|\Illuminate\Foundation\Application|View|Application|RedirectResponse
    {
        try {
            return view('admin.admins.create');
        }
        catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')]);
        }
    }

    public function delete($id): Response|RedirectResponse
    {
        try {
            $survey = User::query()->find($id);
            if (!$survey) {
                return back()->with(['error' => __('User not found')]);
            }

            $survey->delete();

            return ResponseBuilder::success(null, __('User deleted'));
        }
        catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')]);
        }
    }

    public function edit($id)
    {
        try {
            $admin = User::query()->find((int)$id);

            if (!$admin) {
                return back()->with('error', __('Introuvable'));
            }
    
            $title = 'Update';
            return view('admin.admins.create', compact('title', 'admin'));
        }
        catch (Exception $e) {
            Log::error(request()->route()->getActionName() . ' | ' . $e->getMessage());
            return back()->with(['error' => __('messages.admin.default_error_message')]);
        }
    }
}
