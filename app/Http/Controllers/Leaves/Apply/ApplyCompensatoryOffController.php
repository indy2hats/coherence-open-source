<?php

namespace App\Http\Controllers\Leaves\Apply;

use App\Http\Controllers\Controller;
use App\Services\CompensatoryService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ApplyCompensatoryOffController extends Controller
{
    use GeneralTrait;

    private $compensatoryService;

    public function __construct(CompensatoryService $compensatoryService)
    {
        $this->compensatoryService = $compensatoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $year = $this->getYear();
        $list = $this->compensatoryService->getCompensatoryList($year);

        return view('compensatory.index', compact('list', 'year'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $date = $this->compensatoryService->getIp();
        $userId = $this->getCurrentUserId();

        $request->validate([
            'date' => Rule::unique('compensatories')->where(function ($query) use ($date, $userId) {
                return $query->where('date', $date)->where('user_id', $userId);
            }),
            'session' => 'required',
            'reason' => 'required'
        ]);

        if ($this->compensatoryService->isCompensatoryAlreadyAppliedIntheDate($userId, $date)) {
            throw  ValidationException::withMessages(['date' => 'Already applied for this date']);
        }

        $this->compensatoryService->createCompensatory();

        return response()->json(['status' => 'Ok']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = $this->findCompensatoryById($id);

        return view('compensatory.admin.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required',
            'session' => 'required',
            'reason' => 'required'
        ]);

        $this->compensatoryService->updateCompensatory($id);

        return response()->json(['status' => 'Ok']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->deleteCompensatory($id);

        return response()->json(['status' => 'Ok']);
    }

    public function userSearch()
    {
        $list = $this->compensatoryService->getCompensatoryList(request('date'));

        $content = view('compensatory.list', compact('list'))->render();
        $res = [
            'status' => 'Ok',
            'data' => $content,
        ];

        return response()->json($res);
    }
}
