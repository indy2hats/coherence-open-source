<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Services\SettingsService;
use  Illuminate\Http\Request;

class CompanyInfoController extends Controller
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function edit()
    {
        $info = Settings::getCompanyInformations()->toArray();

        return view('settings.company-info.edit', compact('info'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required',
            'company_address_line1' => 'required',
            'company_city' => 'required',
            'company_state' => 'required',
            'company_country' => 'required',
            'company_zip' => 'required',
            'company_phone' => 'required',
            'company_email' => 'required',
            'company_cin' => 'required',
            'company_gstin' => 'required',
            'company_bankaccount_details' => 'required',
            'company_financial_year_from' => 'required',
            'company_financial_year_to' => 'required',
            'company_website_url' => 'required|url',
            'company_linkedin_link' => 'nullable|url',
            'company_facebook_link' => 'nullable|url',
            'company_twitter_link' => 'nullable|url',
            'company_instagram_link' => 'nullable|url'
        ]);

        $this->settingsService->storeCompanyInfo($request);

        return response()->json(['message' => 'Company Information updated']);
    }
}
