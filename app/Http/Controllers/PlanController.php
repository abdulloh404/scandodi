<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\VarDumper\VarDumper;

class PlanController extends Controller
{
    public function planList()
    {
        $data['plans'] = Plan::where('status', 'active')->where('id', '!=', 1)->get();
        return view('plans.index', $data);
    }
    public function index()
    {
        $data['plans'] = Plan::where('id', '!=', 1)->get();
        return view('plans.plan_table', $data);
    }
    public function create()
    {
        return view('plans.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:191',
            'cost' => 'required|numeric|gt:-1',
            'recurring_type' => 'required|in:onetime,monthly,weekly,yearly',
            'table_limit' => 'required|numeric|gt:-1',
            'restaurant_limit' => 'required|numeric|gt:-1',
            'item_limit' => 'required|numeric|gt:-1',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:300',
        ]);


        if ($request->is_item_unlimited == 'yes') {
            unset($request['is_item_unlimited']);
            $request['item_unlimited'] = 'yes';
            $request['item_limit'] = 0;
        } else {
            $request['item_unlimited'] = 'no';
        }

        if ($request->is_table_unlimited == 'yes') {
            unset($request['is_table_unlimited']);
            $request['table_unlimited'] = 'yes';
            $request['table_limit'] = 0;
        } else {
            $request['table_unlimited'] = 'no';
        }

        if ($request->is_restaurant_unlimited == 'yes') {
            unset($request['is_restaurant_unlimited']);
            $request['restaurant_unlimited'] = 'yes';
            $request['restaurant_limit'] = 0;
        }
        if ($request->hasfile('image')) {

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('/uploads-images'), $filename);
            $filename = "$filename";
            $filename = $request->file('image');
        } else {
            $request['restaurant_unlimited'] = 'no';
        }

        // Plan::create([
        //     '_token' => $request->plans('_token'),
        //     'title' => $request->plans('title'),
        //     'recurring_type' => $request->plans('tags'),
        //     'status' => $request->plans('content'),
        //     'table_limit' => ($request->has('featured')),
        //     'restaurant_limit' => ($request->has('featured')),
        //     'item_unlimited' => ($request->has('featured')),
        //     'table_unlimited' => ($request->has('featured')),
        //     'restaurant_unlimited' =>$request->plans('content')
        //     'images' => $request->plans('content')
        // ]);

        // array(12) { ["_token"]=> string(40) "afpl0XDSZhjZ0bvEWSsXdQgxSWWXjubvKnJyb0Hj" ["title"]=> string(37) "Gain More Control Over Your Businness" ["recurring_type"]=> string(6) "weekly" ["status"]=> string(6) "active" ["cost"]=> string(3) "599" ["table_limit"]=> int(0) ["restaurant_limit"]=> int(0) ["item_limit"]=> int(0) ["item_unlimited"]=> string(3) "yes" ["table_unlimited"]=> string(3) "yes" ["restaurant_unlimited"]=> string(3) "yes" ["image"]=> object(Illuminate\Http\UploadedFile)#1541 (7) { ["test":"Symfony\Component\HttpFoundation\File\UploadedFile":private]=> bool(false) ["originalName":"Symfony\Component\HttpFoundation\File\UploadedFile":private]=> string(15) "img-price-3.png" ["mimeType":"Symfony\Component\HttpFoundation\File\UploadedFile":private]=> string(9) "image/png" ["error":"Symfony\Component\HttpFoundation\File\UploadedFile":private]=> int(0) ["hashName":protected]=> NULL ["pathName":"SplFileInfo":private]=> string(25) "C:\wamp64\tmp\phpCF56.tmp" ["fileName":"SplFileInfo":private]=> string(11) "phpCF56.tmp" } }

        // var_dump($request->all());
        // exit('XXXXXXXXXXXXXXXXXXXX');
        return redirect()->route('plan.index');
    }
    public function edit(Plan $plan)
    {
        $data['plan'] = $plan;
        if ($plan->id == 1) return redirect()->route('plan.index')->withErrors(['msg' => trans('layout.message.invalid_request')]);

        return view('plans.edit', $data);
    }
    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'title' => 'required|max:191',
            'cost' => 'required|numeric|gt:-1',
            'recurring_type' => 'required|in:onetime,monthly,weekly,yearly',
            'table_limit' => 'required|numeric|gt:-1',
            'restaurant_limit' => 'required|numeric|gt:-1',
            'item_limit' => 'required|numeric|gt:-1',
        ]);
        if ($plan->id == 1) return redirect()->route('plan.index')->withErrors(['msg' => trans('layout.message.invalid_request')]);
        if ($request->is_item_unlimited == 'yes') {
            unset($request['is_item_unlimited']);
            $request['item_unlimited'] = 'yes';
            $request['item_limit'] = 0;
        } else {
            $request['item_unlimited'] = 'no';
        }

        if ($request->is_table_unlimited == 'yes') {
            unset($request['is_table_unlimited']);
            $request['table_unlimited'] = 'yes';
            $request['table_limit'] = 0;
        } else {
            $request['table_unlimited'] = 'no';
        }

        if ($request->is_restaurant_unlimited == 'yes') {
            unset($request['is_restaurant_unlimited']);
            $request['restaurant_unlimited'] = 'yes';
            $request['restaurant_limit'] = 0;
        } else {
            $request['restaurant_unlimited'] = 'no';
        }
        $plan->update($request->all());

        return redirect()->route('plan.index')->with('success', trans('layout.message.plan_update'));
    }
    public function destroy(Plan $plan)
    {
        if ($plan->id == 1) return redirect()->route('plan.index')->withErrors(['msg' => trans('layout.message.invalid_request')]);

        $user_plan = UserPlan::where('plan_id', $plan->id)->first();
        if ($user_plan) return redirect()->back()->withErrors(['msg' => trans('layout.message.plan_not_delete')]);

        $plan->delete();
        return redirect()->back()->with('success', trans('layout.message.plan_delete_msg'));
    }
}
