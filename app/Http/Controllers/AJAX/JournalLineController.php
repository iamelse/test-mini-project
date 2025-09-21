<?php

namespace App\Http\Controllers\AJAX;

use App\Http\Controllers\Controller;
use App\Models\JournalLine;
use Illuminate\Http\Request;

class JournalLineController extends Controller
{
    public function list()
    {
        $data = JournalLine::with(['journal', 'account'])->get();
        return response()->json(['data' => $data]);
    }

    public function detail($id)
    {
        $line = JournalLine::with(['journal', 'account'])->find($id);
        if (!$line) {
            return response()->json(['status' => 'error', 'message' => 'Journal line not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $line]);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'journal_id' => 'required|exists:journals,id',
            'account_id' => 'required|exists:chart_of_accounts,id',
            'dept_id' => 'nullable|integer',
            'debit' => 'required|numeric|min:0',
            'credit' => 'required|numeric|min:0',
        ]);

        $line = JournalLine::create($data);
        return response()->json(['status' => 'success', 'message' => 'Journal line created', 'data' => $line]);
    }

    public function edit(Request $request, $id)
    {
        $line = JournalLine::find($id);
        if (!$line) {
            return response()->json(['status' => 'error', 'message' => 'Journal line not found'], 404);
        }

        $data = $request->validate([
            'journal_id' => 'required|exists:journals,id',
            'account_id' => 'required|exists:chart_of_accounts,id',
            'dept_id' => 'nullable|integer',
            'debit' => 'required|numeric|min:0',
            'credit' => 'required|numeric|min:0',
        ]);

        $line->update($data);
        return response()->json(['status' => 'success', 'message' => 'Journal line updated', 'data' => $line]);
    }

    public function delete($id)
    {
        $line = JournalLine::find($id);
        if (!$line) {
            return response()->json(['status' => 'error', 'message' => 'Journal line not found'], 404);
        }
        $line->delete();
        return response()->json(['status' => 'success', 'message' => 'Journal line deleted']);
    }
}