<?php

namespace App\Models\ManagementSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class LoginSetting extends Model
{
    public const MEDIA_DEFAULTS = [
        'desktop_video' => 'videos/grokvideo.mp4',
        'welcome_logo' => 'images/pos/xtricate.png',
        'welcome_image' => 'images/pos/image 11.png',
        'slide_1_image' => 'images/pos/login.png',
        'slide_2_image' => 'images/pos/image3.png',
        'slide_3_image' => 'images/pos/image4.png',
        'mobile_image' => 'images/pos/image 14.png',
    ];

    /** Intro-slide text shown when the column is empty. */
    public const TEXT_DEFAULTS = [
        'slide_1_title' => 'Tons of furniture collections',
        'slide_1_text' => 'Experience the future of POS with our innovative system.',
        'slide_2_title' => 'Fast Deliveries to your doorstep',
        'slide_2_text' => 'Intuitive interface designed for seamless user experience.',
        'slide_3_title' => 'Bring aesthetics to your home',
        'slide_3_text' => 'Your data is protected with top-tier security measures.',
    ];

    protected $fillable = [
        'desktop_video',
        'welcome_logo',
        'welcome_image',
        'slide_1_image', 'slide_1_title', 'slide_1_text',
        'slide_2_image', 'slide_2_title', 'slide_2_text',
        'slide_3_image', 'slide_3_title', 'slide_3_text',
        'mobile_image',
    ];

    public static function current(): self
    {
        if (!Schema::hasTable('login_settings')) {
            return new self();
        }

        return self::query()->first() ?? new self();
    }

    /** URL of the uploaded file for $column, or of its built-in default. */
    public function mediaUrl(string $column): string
    {
        return $this->uploadedUrl($this->{$column}) ?? asset(self::MEDIA_DEFAULTS[$column]);
    }

    public function isCustom(string $column): bool
    {
        return $this->uploadedUrl($this->{$column}) !== null;
    }

    /** Saved slide title/text, or its default when left empty. */
    public function text(string $column): string
    {
        return filled($this->{$column}) ? $this->{$column} : self::TEXT_DEFAULTS[$column];
    }

    private function uploadedUrl(?string $path): ?string
    {
        if (empty($path) || !Storage::disk('public')->exists($path)) {
            return null;
        }

        return asset('storage/' . $path);
    }
}
