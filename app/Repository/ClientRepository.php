<?php

namespace App\Repository;

use App\Models\Client;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ClientRepository
{
    use GeneralTrait;

    protected $model;

    public function __construct(Client $client)
    {
        $this->model = $client;
    }

    public function getClients()
    {
        return $this->model::orderBy('company_name', 'ASC')->get();
    }

    public function getClientCompanies()
    {
        return $this->model::select('company_name')->get();
    }

    public function getOneClient()
    {
        return $this->model::with(['project', 'account_manager'])->orderBy('company_name', 'ASC')->first();
    }

    public function getClientUsers()
    {
        return User::clients()->orderBy('first_name')->orderBy('last_name')->get();
    }

    public function store($request)
    {
        $data = [
            'user_id' => request('user_id') ?: null,
            'email' => request('email'),
            'company_name' => request('company_name'),
            'address' => request('address'),
            'phone' => request('phone'),
            'city' => request('city'),
            'post_code' => request('post_code'),
            'country' => request('country'),
            'state' => request('state'),
            'currency' => request('currency'),
            'vat_gst_tax_label' => request('vat_gst_tax_label'),
            'vat_gst_tax_id' => request('vat_gst_tax_id'),
            'vat_gst_tax_percentage' => request('vat_gst_tax_percentage'),
            'account_manager_id' => request('account_manager_id') ?: null,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('clientimage');

            $data += [
                'image' => $path,
            ];
        }

        $this->model::create($data);
    }

    public function update($request, $id)
    {
        $data = [
            'user_id' => request('user_id') ?: null,
            'email' => request('email'),
            'company_name' => request('company_name'),
            'address' => request('address'),
            'phone' => request('phone'),
            'city' => request('city'),
            'post_code' => request('post_code'),
            'country' => request('country'),
            'state' => request('state'),
            'currency' => request('currency'),
            //'vat_id' => request('vat_id'),
            'vat_gst_tax_label' => request('vat_gst_tax_label'),
            'vat_gst_tax_id' => request('vat_gst_tax_id'),
            'vat_gst_tax_percentage' => request('vat_gst_tax_percentage'),
            'account_manager_id' => request('account_manager_id') ?: null,
        ];

        if ($request->hasFile('image')) {
            $getimagepath = $this->getClientImagePath($id);
            $path = $request->file('image')->store('clientimage');
            $this->deleteStorage($getimagepath->image);
            $data += [
                'image' => $path,
            ];
        }

        $this->model::find($id)->update($data);
    }

    public function destroy($id)
    {
        $getimagepath = $this->getClientImagePath($id);

        $this->deleteStorage($getimagepath->image);

        $this->model::find($id)->delete();
    }

    public function getClientsForClientsGrid($filter)
    {
        $clientSearchQuery = Client::with('project');

        if ($filter['company']) {
            $company = $filter['company'];
            $clientSearchQuery->where(function ($query) use ($company) {
                return $query->where('company_name', 'like', '%'.$company.'%')
                    ->orWhere('email', 'like', '%'.$company.'%');
            });
        }
        if ($filter['country']) {
            $clientSearchQuery->where('country', '=', $filter['country']);
        }
        if ($filter['accountManagerId']) {
            $clientSearchQuery->where('account_manager_id', '=', $filter['accountManagerId']);
        }

        if ($filter['dateRange']) {
            $fromDate = null;
            $toDate = null;

            if ($filter['dateRange'] != '') {
                $daterange = explode(' - ', $filter['dateRange']);
                $fromDate = Carbon::parse($daterange[0])->startOfDay()->toDateTimeString();
                $toDate = Carbon::parse($daterange[1])->endOfDay()->toDateTimeString();
            }

            $clientSearchQuery->when(! empty($filter['dateRange']), function ($q) use ($fromDate, $toDate) {
                $q->where('created_at', '>=', $fromDate);
                $q->where('created_at', '<=', $toDate);
            });
        }

        return $clientSearchQuery->orderBy('company_name', 'ASC')->get();
    }

    public function getOneClientForClientsGrid($filter)
    {
        $clientCompany = $filter['company'];
        $clientSearchQuery = Client::with('project')
            ->where('company_name', 'like', '%'.$clientCompany.'%')
            ->orWhere('email', 'like', '%'.$clientCompany.'%');

        return $clientSearchQuery->first() == null ? $this->model::with('project')->first() : $clientSearchQuery->first();
    }

    public function getOneClientSingleClientAjax()
    {
        $clientCompany = request('companyId');

        return $this->model::with('project')->where('id', $clientCompany)->first();
    }

    public function getAnniversaryClients($date)
    {
        $clients = Client::with(['account_manager' => function ($query) {
            $query->active();
        }])
                    ->select(
                        'clients.*',
                        DB::raw('YEAR(CURDATE()) - YEAR(created_at) as acquisition_years')
                    )
                   ->where('created_at', 'like', '%'.$date.'%')
                   ->get();

        return $clients;
    }
}
