<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\TaskSession;
use App\Models\Taxonomy;
use App\Models\TaxonomyList;
use App\Models\User;
use App\Repository\TeamRepository;
use App\Services\BaseCurrencyInterface;
use App\Services\DashboardService;
use Cache;
use Illuminate\Http\Request;

class EmployeeStatusController extends Controller
{
    private $api;
    private $dashboardService;
    private $teamRepository;

    public function __construct(BaseCurrencyInterface $api, DashboardService $dashboardService, TeamRepository $teamRepository)
    {
        $this->api = $api;
        $this->dashboardService = $dashboardService;
        $this->teamRepository = $teamRepository;
    }

    public function getProductiveUsers(Request $request)
    {
        $draw = $request->get('draw');

        $reportees = null;

        if ($request->get('type') == 'team') {
            $reportees = $this->teamRepository->getTeamOfUser(auth()->user()->id)->pluck('id')->toArray();
            if (! $reportees) {
                return response()->json([
                    'draw' => intval($draw),
                    'iTotalRecords' => 0,
                    'iTotalDisplayRecords' => 0,
                    'aaData' => []
                ]);
            }
        }

        $totalRecords = TaskSession::returnActiveUsers($reportees)->count();
        $totalRecordswithFilter = TaskSession::returnActiveUsers($reportees, $request)->count();
        $activeUsers = TaskSession::returnActiveUsers($reportees, $request);
        $userList = $this->dashboardService->setUserList($activeUsers, $request);

        $response = [
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordswithFilter,
            'aaData' => $this->dashboardService->formatUserList($userList)
        ];

        return response()->json($response);
    }

    public function getUpskillingUsers(Request $request)
    {
        $draw = $request->get('draw');

        $reportees = null;
        if ($request->get('type') == 'team') {
            $reportees = $this->teamRepository->getTeamOfUser(auth()->user()->id)->pluck('id')->toArray();
            if (! $reportees) {
                return response()->json([
                    'draw' => intval($draw),
                    'iTotalRecords' => 0,
                    'iTotalDisplayRecords' => 0,
                    'aaData' => []
                ]);
            }
        }

        $totalRecords = TaskSession::returnUpskillingUsers($reportees)->count();
        $totalRecordswithFilter = TaskSession::returnUpskillingUsers($reportees, $request)->count();
        $upSkillingUsers = TaskSession::returnUpskillingUsers($reportees, $request);
        $userList = $this->dashboardService->setUserList($upSkillingUsers, $request);

        $response = [
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordswithFilter,
            'aaData' => $this->dashboardService->formatUserList($userList)
        ];

        return response()->json($response);
    }

    public function getIdleUsers(Request $request)
    {
        $draw = $request->get('draw');

        $reportees = null;
        if ($request->get('type') == 'team') {
            $reportees = $this->teamRepository->getTeamOfUser(auth()->user()->id)->pluck('id')->toArray();
            if (! $reportees) {
                return response()->json([
                    'draw' => intval($draw),
                    'iTotalRecords' => 0,
                    'iTotalDisplayRecords' => 0,
                    'aaData' => []
                ]);
            }
        }

        $totalRecords = User::returnAllIdleUsers($reportees)->count();
        $totalRecordswithFilter = User::returnAllIdleUsers($reportees, $request)->count();
        $idleUsers = User::returnAllIdleUsers($reportees, $request);
        $userList = $this->dashboardService->setIdleUserList($idleUsers, $request);
        $response = [
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordswithFilter,
            'aaData' => $this->dashboardService->formatIdleUserList($userList)
        ];

        return response()->json($response);
    }

    public function getOnLeaveUsers(Request $request)
    {
        $draw = $request->get('draw');
        $reportees = null;
        if ($request->get('type') == 'team') {
            $reportees = $this->teamRepository->getTeamOfUser(auth()->user()->id)->pluck('id')->toArray();
            if (! $reportees) {
                return response()->json([
                    'draw' => intval($draw),
                    'iTotalRecords' => 0,
                    'iTotalDisplayRecords' => 0,
                    'aaData' => []
                ]);
            }
        }

        $totalRecords = count(User::returnOnLeaveUsers($reportees));
        $totalRecordswithFilter = count(User::returnOnLeaveUsers($reportees, $request));
        $onLeaveUsers = User::returnOnLeaveUsers($reportees, $request);
        $userList = $this->dashboardService->setOnLeaveUserList($onLeaveUsers, $request);
        $response = [
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordswithFilter,
            'aaData' => $this->dashboardService->formatOnLeaveUserList($userList)
        ];

        return response()->json($response);
    }

    public function setChartData()
    {
        $financialYearSettings = Settings::getCompanyFinancialYear();
        if (empty($financialYearSettings)) {
            return response()->json(['error' => 'Financial year settings not found. Please add it in settings to view bar chart.']);
        }
        $year = $this->dashboardService->getStartYear($financialYearSettings, request('date'));
        $finYrData = $this->dashboardService->getFinYearData($financialYearSettings, $year);

        $baseCurrency = TaxonomyList::where('taxonomy_id', Taxonomy::where('title', 'Base Currency')->first()->id)->first()->title;

        $base = 1;
        if ($baseCurrency != 'INR') {
            $base = Cache::remember('currency-api-data', config('general.currency-api-cache-time'), function () use ($finYrData, $baseCurrency) {
                return $this->api->getOldCurrencyRate('INR', $finYrData['startDate'])['rates'][$baseCurrency];
            });
        }

        if (Cache::get($year)) {
            $data = Cache::get($year);

            return response()->json(['income' => $data['income'], 'expense' => $data['expense'], 'profit' => $data['profit']]);
        } else {
            $data2 = array_fill(0, count($finYrData['months']), 0);

            $startDate = $finYrData['startDate'];
            $endDate = $finYrData['endDate'];

            $general = $this->dashboardService->getGeneralForChartData($startDate, $endDate);
            $overheads = $this->dashboardService->getOverheadsForChartData($startDate, $endDate);
            $payroll = $this->dashboardService->getPayrollForChartData($startDate, $endDate);

            foreach ($finYrData['monthsNumber'] as $key => $month) {
                $data2[$key] = $this->dashboardService->getExpenseForChartData($base, $month, $general, $overheads, $payroll);
            }

            return response()->json(['expense' => $data2, 'year' => $year, 'months' => $finYrData['months'], 'baseCurrency' => $baseCurrency]);
        }
    }

    public function clearChartCache()
    {
        Cache::forget(request('date'));

        return response()->json(['success' => 'Cache cleared!']);
    }

    public function getPieChartData()
    {
        $data = [];
        $data['productiveUsers'] = TaskSession::returnActiveUsers()->count();
        $data['upskillingUsers'] = TaskSession::returnUpskillingUsers()->count();
        $data['idleUsers'] = User::returnAllIdleUsers()->count();
        $data['onLeaveUsers'] = count(User::returnOnLeaveUsers());

        return response()->json(['data' => $data]);
    }
}
