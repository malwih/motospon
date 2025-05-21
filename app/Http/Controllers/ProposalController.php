<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;

class ProposalController extends Controller
{
    public function index()
    {
        $proposals = Proposal::with('sponsor')->get();

        return view('dashboard.proposals.index', compact('proposals'));
    }

    // Semua proposal untuk company
    public function companyDashboard()
    {
        $proposals = Proposal::where('submit', true)->latest()->get();
    return view('dashboard.index', compact('proposals'));
    }

    // Proposal milik user saat ini
    public function userDashboard()
    {
        $proposals = Proposal::where('user_id', auth()->id())->get();
        return view('dashboard.index', compact('proposals'));
    }

    // Submit proposal dari preview
    public function submitProposal(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
        ]);

        $proposal = new Proposal();
        $proposal->user_id = auth()->id();
        $proposal->company_name = $request->input('company_name');
        $proposal->sponsor_id = $request->input('sponsor_id'); // jika ada
        $proposal->is_active = true;
        $proposal->is_completed = false;
        $proposal->is_reject = false;
        $proposal->save();

        return redirect()->route('dashboard.index')->with('success', 'Proposal submitted.');
    }

    // Tampilkan preview proposal
    public function preview(Request $request)
    {
        $proposalHtml = $this->generateHtmlPreview($request->input('company_name'));

        return view('dashboard.proposal-preview', [
            'proposal' => $proposalHtml,
            'raw_proposal' => $request->all(),
            'sponsor_id' => $request->input('sponsor_id', null),
        ]);
    }

    // Helper untuk HTML preview
    private function generateHtmlPreview($raw)
    {
        return nl2br(e($raw));
    }

    // Update status (approve, reject, cancel)
    public function updateStatus(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);

        if ($request->action === 'accept') {
            $proposal->update([
                'is_completed' => true,
                'is_active' => false,
                'is_reject' => false,
            ]);
            return back()->with('status', 'Proposal accepted successfully.');
        }

        if ($request->action === 'reject') {
            $proposal->update([
                'is_reject' => true,
                'is_active' => false,
                'is_completed' => false,
            ]);
            return back()->with('status', 'Proposal rejected successfully.');
        }

        return back()->with('status', 'Invalid action.');
    }

    // Detail proposal
    public function show($id)
    {
        $proposal = Proposal::findOrFail($id);
        return view('proposal.show', compact('proposal'));
    }

    // Hapus proposal
    public function destroy($id)
    {
        Proposal::destroy($id);
        return back()->with('success', 'Proposal berhasil dihapus.');
    }

    public function showSubmittedProposals()
{
    $proposals = Proposal::with('user', 'sponsor')->get(); // Sesuaikan relasi jika ada
    return view('dashboard.company', compact('proposals'));
}

public function acceptProposal($id)
{
    $proposal = Proposal::findOrFail($id);
    $proposal->is_completed = true;
    $proposal->is_reject = false;
    $proposal->save();

    return back()->with('success', 'Proposal accepted.');
}

public function rejectProposal($id)
{
    $proposal = Proposal::findOrFail($id);
    $proposal->is_completed = false;
    $proposal->is_reject = true;
    $proposal->save();

    return back()->with('success', 'Proposal rejected.');
}


}
