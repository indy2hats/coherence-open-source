<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Newsletter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class NewsletterController extends Controller
{
    /** Used to fetch all details needed for clients.index */
    public function index()
    {
        $newsletters = Newsletter::active()->orderBy('publish_date', 'DESC')->get();

        return view('newsletters.index', compact('newsletters'));
    }

    /** used to store new client details. */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'publish_date' => 'required',
            'screenshot' => 'required',
            'newsletter' => 'required',
        ]);

        $data = [
            'title' => request('title'),
            'publish_date' => Carbon::parse('01-'.request('publish_date'))->format('Y-m-d')
        ];

        if ($request->hasFile('screenshot')) {
            $path = $request->file('screenshot')->store('newsletters/screenshots');

            $data += [
                'screen_shot' => $path,
            ];
        }

        if ($request->hasFile('newsletter')) {
            $doc = $request->file('newsletter');
            $docName = $doc->getClientOriginalName();
            $path = $doc->storeAs('newsletters/documents', $docName);

            $data += [
                'newsletter' => $path,
            ];
        }

        Newsletter::create($data);

        return response()->json(['message' => 'Newsletter created successfully']);
    }

    /** fetch and displays client details with the id. for view option */
    public function show($id)
    {
        $client = Client::where('id', $id)->first();

        return view('clients.view', compact('client'));
    }

    /** fetch client details with the id. for edit option  */
    public function edit($id)
    {
        $client = Client::where('id', $id)->first();
        $users = User::clients()->orderBy('first_name')->orderBy('last_name')->get();

        return view('clients.edit', compact('client', 'users'));
    }

    /** used to store edited client details. */
    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'required',
            'country' => 'required',
            'company_name' => 'required',
            'currency' => 'required',
        ]);

        $data = [
            'user_id' => request('user_id'),
            'email' => request('email'),
            'company_name' => request('company_name'),
            'address' => request('address'),
            'phone' => request('phone'),
            'city' => request('city'),
            'post_code' => request('post_code'),
            'country' => request('country'),
            'state' => request('state'),
            'currency' => request('currency'),
        ];

        if ($request->hasFile('image')) {
            $getimagepath = Client::select('image')->where('id', $id)->first();
            $path = $request->file('image')->store('clientimage');
            Storage::delete($getimagepath->image);
            $data += [
                'image' => $path,
            ];
        }

        Client::find($id)->update($data);

        return response()->json(['message' => 'Client details updated']);
    }

    /** removes client of the specified id from the table */
    public function destroy($id)
    {
        $getimagepath = Client::select('image')->where('id', $id)->first();

        Storage::delete($getimagepath->image);

        Client::find($id)->delete();

        return response()->json(['message' => 'Client deleted successfully']);
    }

    /* Return Client Search Result */
    public function getNewslettersGrid(Request $request)
    {
        $newsletters = Newsletter::active()->orderBy('publish_date', 'DESC')->get();
        $content = view('newsletters.grid', compact('newsletters'))->render();

        $res = [
            'status' => 'OK',
            'data' => $content,
        ];

        return response()->json($res);
    }

    /** Ajax function to return the client details */
    public function getSingleClientAjax()
    {
        $clientCompany = request('companyId');

        $oneClient = Client::with('project')->where('id', $clientCompany)->first();

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
