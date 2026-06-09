<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        return view(
            'admin.settings.index',
            compact('setting')
        );
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'open_hours' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:1000',
            'description' => 'nullable|string|max:1000',
            'footer' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'email.email' => 'Format email tidak valid',
            'logo.image' => 'Logo harus berupa gambar',
            'logo.mimes' => 'Logo harus JPG, JPEG, atau PNG',
            'logo.max' => 'Ukuran logo maksimal 2 MB',
        ]);

        $setting = Setting::first();

        if (!$setting) {
            $setting = new Setting();
        }

        $logoPath = $setting->logo;

        if ($request->hasFile('logo')) {

            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }

            $logo = $request->file('logo');

            $logoName = time() . '_' . str_replace(' ', '_', $logo->getClientOriginalName());

            $logo->storeAs('settings', $logoName, 'public');

            $logoPath = 'settings/' . $logoName;
        }

        $setting->site_name = $request->site_name;
        $setting->logo = $logoPath;
        $setting->description = $request->description;
        $setting->footer = $request->footer;
        $setting->email = $request->email;
        $setting->phone = $request->phone;
        $setting->address = $request->address;
        $setting->open_hours = $request->open_hours;

        $setting->save();

        return back()->with(
            'success',
            'Pengaturan berhasil disimpan'
        );
    }
}