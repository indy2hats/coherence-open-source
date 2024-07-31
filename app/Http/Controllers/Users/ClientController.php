<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Services\ClientService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    use GeneralTrait;

    private $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /** Used to fetch all details needed for clients.index */
    public function index()
    {
        $clients = $this->clientService->getClients();
        $clientCompanies = $this->clientService->getClientCompanies();
        $oneClient = $this->clientService->getOneClient();
        $users = $this->clientService->getClientUsers();
        $countries = $this->clientService->getClients()->groupBy('country');
        $employees = $this->getUserNotClients();
        $currencyList = config('currency');

        return view('clients.index', compact('clientCompanies', 'clients', 'oneClient', 'users', 'countries', 'employees', 'currencyList'));
    }

    /** used to store new client details. */
    public function store(StoreClientRequest $request)
    {
        $this->clientService->store($request);

        return response()->json(['message' => 'Client created successfully']);
    }

    /** fetch and displays client details with the id. for view option */
    public function show($id)
    {
        $client = $this->getClientById($id);

        return view('clients.view', compact('client'));
    }

    /** fetch client details with the id. for edit option  */
    public function edit($id)
    {
        $client = $this->getClientById($id);
        $users = $this->clientService->getClientUsers();
        $employees = $this->getUserNotClients();
        $currencyList = config('currency');

        return view('clients.edit', compact('client', 'users', 'employees', 'currencyList'));
    }

    /** used to store edited client details. */
    public function update(UpdateClientRequest $request, $id)
    {
        $this->clientService->update($request, $id);

        return response()->json(['message' => 'Client details updated']);
    }

    /** removes client of the specified id from the table */
    public function destroy($id)
    {
        $this->clientService->destroy($id);

        return response()->json(['message' => 'Client deleted successfully']);
    }

    /* Return Client Search Result */
    public function getClientsGrid(Request $request)
    {
        $filter['company'] = request('company');
        $filter['country'] = request('country');
        $filter['accountManagerId'] = request('accountManagerId');
        $filter['dateRange'] = request('dateRange');

        $clients = $this->clientService->getClientsForClientsGrid($filter);

        $oneClient = $this->clientService->getOneClientForClientsGrid($filter);
        $countries = $this->clientService->getClients()->groupBy('country');
        $employees = $this->getUserNotClients();

        $clientCompany = request('company');
        $accountManagerId = request('accountManagerId');
        $dateRange = request('dateRange');

        $content = view('clients.grid', compact('clients', 'oneClient', 'clientCompany', 'accountManagerId', 'countries', 'employees', 'dateRange'))->render();
        $res = [
            'status' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /** Ajax function to return the client details */
    public function getSingleClientAjax()
    {
        $oneClient = $this->clientService->getOneClientSingleClientAjax();
        $content = view('clients.view', compact('oneClient'))->render();
        $res = [
            'status' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /** return the company name for typeAhead */
    public function getTypheadDataClient()
    {
        $typehead = Client::select('company_name')->get();

        return response()->json(['data' => $typehead]);
    }
}
