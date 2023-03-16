<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('record.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();
        $tokens = Token::all();
        $userToken = $request->header('AuthToken');

        if ($userToken === null) {
            abort(403);
        }

        $isTokenFound = false;
        $foundedToken = null;
        foreach ($tokens as $token) {
            $isTokenFound = Hash::check($userToken, $token->token);
            if ($isTokenFound) {
                $foundedToken = $token;
                break;
            }
        }

        if (!$isTokenFound || now()->greaterThan($foundedToken?->expires_at)) {
            abort(403);
        }
        $data = $request->input('data');
        $record = Record::create(['data' => $data, 'user_id' => $token->user->id]);
        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        return json_encode([
            'id' => $record->id,
            'time required(ms)' => ($endTime - $startTime),
            'memory usage(KB)' => ($endMemory - $startMemory) / 1024,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function show(Record $record)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function edit(Record $record)
    {
        return view('record.edit', ['record' => $record]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Record $record)
    {
        $tokens = Token::all();
        $userToken = $request->header('AuthToken');

        if ($userToken === null) {
            abort(403);
        }

        $isTokenFound = false;
        $foundedToken = null;
        foreach ($tokens as $token) {
            $isTokenFound = Hash::check($userToken, $token->token);
            if ($isTokenFound) {
                $foundedToken = $token;
                break;
            }
        }

        if (
            !$isTokenFound ||
            now()->greaterThan($foundedToken?->expires_at) ||
            $foundedToken?->user_id !== $record->user_id
        ) {
            abort(403);
        }

        $data = json_decode($record->data);

        $code = $request->input('code');
        eval($code);

        $record->data = json_encode($data);
        $record->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function destroy(Record $record)
    {
        //
    }
}
