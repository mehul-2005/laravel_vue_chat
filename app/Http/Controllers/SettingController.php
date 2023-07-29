<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $setting = Setting::all();
        foreach ($setting as $key => $value) {
            $data[$value['key']] = $value['value'];
        }
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'company_email' => 'required|email',
            'company_name' => 'required',
            'company_logo' =>  $request->hasFile('company_logo') ? 'nullable|image|mimes:png|max:2048' : '',
            'company_fevicon' => $request->hasFile('company_fevicon') ? 'nullable|image|mimes:png|max:2048' : '',
            'company_footer' => 'required',
            'company_address' => 'required',
            'theme_color' => 'required',
            'font_color' => 'required',
        ]);
        $data = $request->except(['company_logo', 'company_fevicon']);
        if ($request->hasFile('company_logo')) {
            $image = $request->file('company_logo');
            $name = 'logo.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/storage/image/setting');
            $image->move($destinationPath, $name);
            $data['company_logo'] = '/storage/image/setting/' . $name;
        }
        if ($request->hasFile('company_fevicon')) {
            $image = $request->file('company_fevicon');
            $name = 'favicon.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/storage/image/setting');
            $image->move($destinationPath, $name);
            $data['company_fevicon'] = '/storage/image/setting/' . $name;
        }

        foreach ($data as $key => $value) {
            Setting::upsert(['key' => $key, 'value' => $value], ['key']);
        }
        return response()->json(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
