<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserDataTable;
use App\DataTables\ParrainageDataTable;
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
use Illuminate\Support\Str;
use Pcsaini\ResponseBuilder\ResponseBuilder;

class ParrainageController extends Controller
{
    public function index(ParrainageDataTable $dataTable)
    {
        $user = User::query()->where("profile_parrain", 1)->first();

        $objectif_1 = 0;
        $lot_1 = 0;

        $objectif_2 = 0;
        $lot_2 = 0;

        $objectif_3 = 0;
        $lot_3 = 0;

        $objectif_4 = 0;
        $lot_4 = 0;

        if($user){
            $objectif_1 = $user->objectif_1;
            $lot_1 = $user->lot_1;

            $objectif_2 = $user->objectif_2;
            $lot_2 = $user->lot_2;

            $objectif_3 = $user->objectif_3;
            $lot_3 = $user->lot_3;

            $objectif_4 = $user->objectif_4;
            $lot_4 = $user->lot_4;
        }



        return $dataTable->render('admin.parrainages.index', compact(
            'objectif_1',
                    'lot_1',

            'objectif_2',
            'lot_2',

            'objectif_3',
            'lot_3',

            'objectif_4',
            'lot_4'
        ));
    }


    public function objectif(Request $request): RedirectResponse
    {
        $data = $request->only([
            'objectif_1',
            'objectif_2',
            'objectif_3',
            'objectif_4',

            'lot_1',
            'lot_2',
            'lot_3',
            'lot_4'

        ]);

        $users = User::query()->where("profile_parrain", 1)->get();

        if($users){
            foreach ($users as $user){
                $user->objectif_1 = $data["objectif_1"];
                $user->objectif_2 = $data["objectif_2"];
                $user->objectif_3 = $data["objectif_3"];
                $user->objectif_4 = $data["objectif_4"];

                $user->lot_1 = $data["lot_1"];
                $user->lot_2 = $data["lot_2"];
                $user->lot_3 = $data["lot_3"];
                $user->lot_4 = $data["lot_4"];
                $user->save();
            }
        }

        return to_route('admin.parrainages.list')->with(['success' => "Effectué"]);
    }

    public function save(Request $request): RedirectResponse
    {
        $old_code_affiliation = null;
        if ($request->get('id')) {
            $user = User::query()->find($request->get('id'));

            if (!$user) {
                return back()->with(['error' => __('User not found')]);
            }

            $old_code_affiliation = $user->code_affiliation;
        }

        $data = $request->only(['email', 'commune']);

        if (isset($user)) {
            $user->update($data);
            $message = __('Updated');

//            //on met à jour les filles
//            $code_affiliation = $data["code_affiliation"];
//            if(!empty($code_affiliation) && !empty($old_code_affiliation)){
//                $users = User::query()->where('code_parrain', $old_code_affiliation)->get();
//                if($users){
//                    foreach ($users as $us){
//                        $us->code_parrain = $code_affiliation;
//                        $us->save();
//                    }
//                }
//            }
        }
        return to_route('admin.parrainages.list')->with(['success' => $message]);
    }


    public function edit($id)
    {
        try {
            $user = User::query()->find($id);

            if (!$user) {
                return back()->with('error', __('Introuvable'));
            }

            $title = 'Update';
            return view('admin.parrainages.create', compact('title', 'user'));
        } catch (\Exception $e) {
            Log::error(request()->route()->getActionName() . ' | ' . $e->getMessage());
            return back()->with(['error' => __('messages.admin.default_error_message')]);
        }
    }

    public function delete($id): Response|RedirectResponse
    {
        try {
            $user = User::query()->find($id);
            if (!$user) {
                return back()->with(['error' => __('User not found')]);
            }

            $code_affiliation = $user->code_affiliation;

            $user->delete();

            //on met à jour les filles
            if(!empty($code_affiliation)){
                $users = User::query()->where('code_parrain', $code_affiliation)->get();
                if($users){
                    foreach ($users as $us){
                        $us->code_parrain = null;
                        $us->save();
                    }
                }
            }

            return ResponseBuilder::success(null, __('Parrain effacé avec succès'));
        } catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')]);
        }
    }


    public function reset($id): Response|RedirectResponse
    {
        try {
            $user = User::query()->find($id);
            if (!$user) {
                return back()->with(['error' => __('User not found')]);
            }

            $code_affiliation = $user->code_affiliation;

            //on met à jour les filles
            if(!empty($code_affiliation)){
                $users = User::query()->where('code_parrain', $code_affiliation)->get();
                if($users){
                    foreach ($users as $us){
                        $us->comptabilise = 1;
                        $us->save();
                    }
                }
            }

            return ResponseBuilder::success(null, __('Le nombre de filleuls a été remis à zero.'));
        } catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')]);
        }
    }
}
