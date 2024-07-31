<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use  App\Services\BaseCurrencyInterface;
use App\Services\SettingsService;

class BaseCurrencyController extends Controller
{
    private $api;
    protected $settingsService;

    public function __construct(BaseCurrencyInterface $api, SettingsService $settingsService)
    {
        $this->api = $api;
        $this->settingsService = $settingsService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $api_data = $this->api->convertCurrency();
        $base = $this->settingsService->getBase();
        $currency = config('currency')[$base->title];

        return view('settings.base-currency.index', compact('api_data', 'base', 'currency'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $list = config('currency');
        $base = $this->settingsService->getBase()->title;

        return view('settings.base-currency.change', compact('list', 'base'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeCurrency()
    {
        $this->settingsService->changeCurrency();

        return response()->json(['success' => 'Ok']);
    }
}
