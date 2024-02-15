<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CompanyDataTable;
use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Campaign;
use App\Models\Company;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Pcsaini\ResponseBuilder\ResponseBuilder;

class CompanyController extends Controller
{
    public function index(CompanyDataTable $dataTable)
    {
        return $dataTable->render('admin.companies.index');
    }

    public function create(): Factory|\Illuminate\Foundation\Application|View|Application|RedirectResponse
    {
        try {


            return view('admin.companies.create');
        } catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')]);
        }
    }

    public function save(Request $request): RedirectResponse
    {


        if ($request->get('id')) {
            $company = Company::query()->find($request->get('id'));

            if (!$company) {
                return back()->with(['error' => __('Company not found')]);
            }
        }

        $data = $request->only(['name']);
        if ($request->file('logo')) {
            $extension = $request->file('logo')->getClientOriginalExtension();
            $name = $request->file('logo')->getClientOriginalName();
            $name = Str::slug(explode('.', $name)[0]) . '-' . time() . '.' . $extension;
            $path = 'uploads/companies/logo';
            $data['logo'] = $request->file('logo')->storePubliclyAs($path, $name);
        }

        if ($request->file('company_image')) {
            $extension = $request->file('company_image')->getClientOriginalExtension();
            $name = $request->file('company_image')->getClientOriginalName();
            $name = Str::slug(explode('.', $name)[0]) . '-' . time() . '.' . $extension;
            $path = 'uploads/companies/company_image';
            $data['company_image'] = $request->file('company_image')->storePubliclyAs($path, $name);
        }

        if (isset($company)) {
            $company->update($data);
            $message = __('Updated');
        } else {
            Company::query()->create($data);
            $message = __('Created');
        }


        return to_route('admin.companies.list')->with(['success' => $message]);
    }


    public function edit($id)
    {
        try {
            $company = Company::query()->find($id);

            if (!$company) {
                return back()->with('error', __('Introuvable'));
            }

            $title = 'Update';
            return view('admin.companies.create', compact('title', 'company'));
        } catch (\Exception $e) {
            Log::error(request()->route()->getActionName() . ' | ' . $e->getMessage());
            return back()->with(['error' => __('messages.admin.default_error_message')]);
        }
    }

    public function delete($id): Response|RedirectResponse
    {
        try {
            $survey = Company::query()->find($id);
            if (!$survey) {
                return back()->with(['error' => __('Company not found')]);
            }

            $survey->delete();

            return ResponseBuilder::success(null, __('Company deleted'));
        } catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')]);
        }
    }
}
