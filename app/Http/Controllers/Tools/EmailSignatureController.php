<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\Settings;

class EmailSignatureController extends Controller
{
    public function index()
    {
        $socialMedia = config('general.social-media-links');
        $info = Settings::getCompanyInformations()->toArray();

        return view('tools.signature.index', compact('socialMedia', 'info'));
    }
}
