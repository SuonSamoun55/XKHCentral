<?php

namespace App\Http\Controllers\Api\ManagementSystem;

use App\Http\Controllers\Controller;
use App\Models\ManagementSystem\LoginSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class LoginSettingsController extends Controller
{
    private const VIDEO_RULES = ['nullable', 'file', 'mimetypes:video/mp4,video/webm', 'max:30720'];
    private const IMAGE_RULES = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'];

    public function index()
    {
        $settings = LoginSetting::current();

        return view('ManagementSystemViews.AdminViews.Layouts.LoginSettings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        if (!Schema::hasTable('login_settings')) {
            return redirect()->route('login-settings.index')
                ->with('error', 'Run "php artisan migrate" first to enable login page setup.');
        }

        $rules = [];
        foreach (array_keys(LoginSetting::MEDIA_DEFAULTS) as $column) {
            $rules[$column] = $column === 'desktop_video' ? self::VIDEO_RULES : self::IMAGE_RULES;
            $rules['reset_' . $column] = ['nullable'];
        }
        foreach (array_keys(LoginSetting::TEXT_DEFAULTS) as $column) {
            $rules[$column] = ['nullable', 'string', str_ends_with($column, '_title') ? 'max:120' : 'max:500'];
        }

        $request->validate($rules, [
            'desktop_video.mimetypes' => 'The desktop video must be an MP4 or WEBM file.',
            'desktop_video.max' => 'The desktop video may not be larger than 30 MB.',
            '*.image' => 'Please choose a JPG, PNG or WEBP image.',
            '*.max' => 'An image may not be larger than 5 MB.',
        ]);

        $settings = LoginSetting::current();
        $disk = Storage::disk('public');
        $oldFiles = [];

        foreach (array_keys(LoginSetting::MEDIA_DEFAULTS) as $column) {
            if ($request->hasFile($column)) {
                $folder = $column === 'desktop_video' ? 'login_media/videos' : 'login_media/images';
                $oldFiles[] = $settings->{$column};
                $settings->{$column} = $request->file($column)->store($folder, 'public');
            } elseif ($request->boolean('reset_' . $column)) {
                $oldFiles[] = $settings->{$column};
                $settings->{$column} = null;
            }
        }

        foreach (array_keys(LoginSetting::TEXT_DEFAULTS) as $column) {
            // Saving the default wording as-is keeps the column empty, so a
            // later change to the default still reaches the login page.
            $value = trim((string) $request->input($column, ''));
            $settings->{$column} = ($value === '' || $value === LoginSetting::TEXT_DEFAULTS[$column]) ? null : $value;
        }

        $settings->save();

        foreach (array_filter($oldFiles) as $path) {
            $disk->delete($path);
        }

        return redirect()->route('login-settings.index')
            ->with('success', 'Login page updated.');
    }
}
