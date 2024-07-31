<?php

namespace App\Services;

use App\Repository\ClientRepository;
use Carbon\Carbon;

class ClientService
{
    protected $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function getClients()
    {
        return $this->clientRepository->getClients();
    }

    public function getClientCompanies()
    {
        return $this->clientRepository->getClientCompanies();
    }

    public function getOneClient()
    {
        return $this->clientRepository->getOneClient();
    }

    public function getClientUsers()
    {
        return $this->clientRepository->getClientUsers();
    }

    public function store($request)
    {
        return $this->clientRepository->store($request);
    }

    public function update($request, $id)
    {
        $this->clientRepository->update($request, $id);
    }

    public function destroy($id)
    {
        $this->clientRepository->destroy($id);
    }

    public function getClientsForClientsGrid($filter)
    {
        return $this->clientRepository->getClientsForClientsGrid($filter);
    }

    public function getOneClientForClientsGrid($filter)
    {
        return $this->clientRepository->getOneClientForClientsGrid($filter);
    }

    public function getOneClientSingleClientAjax()
    {
        return $this->clientRepository->getOneClientSingleClientAjax();
    }

    public function getAnniversaryClients($date = null)
    {
        $date ?? Carbon::now()->format('m-d');

        return $this->clientRepository->getAnniversaryClients($date);
    }
}
