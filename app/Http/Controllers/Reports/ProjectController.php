<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\ReportFilterService;
use App\Services\ReportService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $reportService;
    protected $reportFilterService;

    use GeneralTrait;

    public function __construct(
        ReportService $reportService,
        ReportFilterService $reportFilter
        ) {
        $this->reportService = $reportService;
        $this->reportFilterService = $reportFilter;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function projectReport()
    {
        $date = $this->reportService->getDateForReport();

        return view('reports.projects.index', compact('date'));
    }

    public function projectSearch(Request $request)
    {
        $projects = $this->reportService->getProjects($request);
        $content = view('reports.projects.sheet', compact('projects'))->render();

        $res = [
            'data' => $content,
            'status' => 'success'
        ];

        return response()->json($res);
    }

    public function taskReport($id)
    {
        $project = Project::where('id', $id)->first();

        $tasks = $this->reportService->getTasks($id);

        return view('reports.projects.tasks', compact('tasks', 'project'));
    }

    public function projectBillabilityReport(Request $request)
    {
        $billableClients = $this->reportService->getBillableClients($request);
        $projects = $this->reportService->getProjectBillability($request);
        $projectList = $this->reportService->getAllBillableProjects($request);
        $savedFilters = $this->reportFilterService->getSavedFilters('billability-report');
        $sessionTypes = $this->getSessionTypes();

        $sessionType = $request->sessionType ?? [];
        $project = $request->project ?? [];
        $selectedClients = $request->client ?? [];
        $selectedSavedFilter = $request->savedFilter ?? [];

        return view('reports.projects.billability.index', compact('sessionType', 'sessionTypes', 'projects', 'projectList', 'project',
            'billableClients', 'selectedClients', 'savedFilters', 'selectedSavedFilter'));
    }

    public function projectBillabilityGraph(Request $request)
    {
        $projects = $this->reportService->getProjectBillability($request);
        $data1 = $data2 = $data3 = $projectData = $percentage = [];

        foreach ($projects as $project) {
            $data1[] = floor($project->time_spent / 60);
            $data2[] = floor($project->billed_time / 60);
            $data3[] = floor(($project->time_spent - $project->billed_time) / 60);
            $projectData[] = $project->project_name;
            $percentage[] = $project->time_spent == 0 ? 0 : number_format(($project->billed_time / $project->time_spent) * 100, 2);
        }

        return response()->json(['timeSpent' => $data1, 'billedHours' => $data2, 'nonBilledHours' => $data3, 'projects' => $projectData, 'percentage' => $percentage]);
    }

    /**
     * Retrieves the project billability based on the given request and returns it as a JSON response.
     *
     * @param  Request  $request  The request object containing the necessary parameters for fetching the project billability.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the project billability.
     */
    public function projectBillabilityReportAjaxProjectFilter(Request $request)
    {
        $projectList = $this->reportService->getProjectBillability($request);

        return response()->json($projectList);
    }

    /**
     * Retrieves project billability hours graph based on the request parameters and returns it as a view.
     *
     * @param  Request  $request  The request object containing the necessary parameters.
     * @return View The view with the project billability hours graph data.
     *
     * @throws \Exception If there is an error.
     */
    public function projectBillabilityHoursGraph(Request $request)
    {
        $projectId = $request->projectId;
        $projectList = Project::whereNull('deleted_at')->get();

        $weeks = $timeSpent = $billableHours = [];
        $projectName = '';
        $selectedDataDisplayType = $this->reportService->getSelectedDataDisplayType($request);
        $xAxisTitle = ucwords($selectedDataDisplayType);
        $projectProgressData = $this->reportService->getProjectBillableHours($request, $selectedDataDisplayType);

        if (count($projectProgressData) > 0) {
            $projectName = $projectProgressData[0]['project_name']; // Corrected assignment to fetch project_name

            foreach ($projectProgressData as $projectProgress) {
                // Check if the properties exist before accessing them
                $weeks[] = isset($projectProgress['date_range']) ? $projectProgress['date_range'] : null;
                $timeSpent[] = isset($projectProgress['total_hours']) ? $projectProgress['total_hours'] : 0;
                $billableHours[] = isset($projectProgress['billable_hours']) ? $projectProgress['billable_hours'] : 0;
            }
        }

        return view('reports.projects.billability.graph', compact('xAxisTitle', 'weeks', 'timeSpent', 'billableHours', 'projectName', 'projectList', 'projectId', 'selectedDataDisplayType'));
    }
}
