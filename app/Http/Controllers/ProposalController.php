<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;
use App\Models\Sponsor;
use Carbon\Carbon;



class ProposalController extends Controller
{
    // Admin - Semua proposal
    public function index()
    {
        $proposals = Proposal::with(['sponsor', 'user'])->get();
        return view('dashboard.proposals.index', compact('proposals'));
    }

    // Dashboard perusahaan - Hanya proposal yang sudah disubmit
    public function companyDashboard()
    {
        $proposals = Proposal::with(['sponsor', 'user'])
            ->where('submit', true)
            ->latest()
            ->get();

        return view('dashboard.index', compact('proposals'));
    }

    // Dashboard user - Proposal milik user
    public function userDashboard()
    {
        $proposals = Proposal::with('sponsor')
            ->where('user_id', Auth::id())
            ->get();

        return view('dashboard.index', compact('proposals'));
    }

    // Preview proposal (sebelum submit)
    public function preview(Request $request)
    {
        $proposalHtml = $this->generateHtmlPreview($request->input('company_name'));

        return view('dashboard.proposal-preview', [
            'proposal' => $proposalHtml,
            'raw_proposal' => $request->input('company_name'),
            'sponsor_id' => $request->input('sponsor_id'),
            'category' => $request->input('category'),
            'event' => $request->input('event'),
            'name_community' => $request->input('name_community'),
            'name_event' => $request->input('name_event'),
            'location' => $request->input('location'),
            'date' => $request->input('date_event'),
            'feedback_benefit' => $request->input('feedback_benefit'),
            'budget_items' => $request->input('budget_items', []),
            'budget_descriptions' => $request->input('budget_descriptions', []),
            'budget_costs' => $request->input('budget_costs', []),
            'rundown_times' => $request->input('rundown_times', []),
            'rundown_activities' => $request->input('rundown_activities', []),
        ]);
    }

    // Simpan proposal dari preview
    public function submitProposal(Request $request)
{
    $request->validate([
        'category' => 'required',
        'event' => 'required',
        'name_community' => 'required|string|max:255',
        'name_event' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'date_event' => 'required|date_format:Y-m-d',
        'feedback_benefit' => 'required|string',
        'sponsor_id' => 'required|exists:sponsors,id',
        'proposal' => 'required|string', // pastikan ini tervalidasi juga
    ]);

    

    $proposal = new Proposal();
    $proposal->user_id = auth()->id();
    $proposal->sponsor_id = $request->input('sponsor_id');
    $proposal->category = $request->input('category');
    $proposal->event = $request->input('event');
    $proposal->name_community = $request->input('name_community');
    $proposal->name_event = $request->input('name_event');
    $proposal->location = $request->input('location');
    $proposal->date_event = $request->input('date_event'); 
    $proposal->feedback_benefit = $request->input('feedback_benefit');
    $proposal->proposal_raw = $request->input('proposal');
    $proposal->submit = true;
    $proposal->is_active = true; // Aktifkan supaya tampil di dashboard
    $proposal->save();

    return redirect()->route('dashboard')->with('success', 'Proposal berhasil dikirim.');
}


    // Terima / Tolak proposal
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
                'is_completed' => false,
                'is_active' => false,
                'is_reject' => true,
            ]);
            return back()->with('status', 'Proposal rejected successfully.');
        }

        return back()->with('status', 'Invalid action.');
    }

    // Lihat detail proposal
    public function show($id)
    {
        $proposal = Proposal::with(['sponsor', 'user'])->findOrFail($id);
        return view('proposal.show', compact('proposal'));
    }

    // Hapus proposal
    public function destroy($id)
{
    $proposal = Proposal::findOrFail($id);
    $proposal->delete();

    return redirect()->route('dashboard')->with('status', 'Proposal berhasil dihapus.');
}






    // Helper - Konversi teks menjadi HTML
    private function generateHtmlPreview($raw)
    {
        return nl2br(e($raw));
    }

    private function generateProposal(array $data): string
{
    $style = '
        <style>
            #proposal-container {
                font-family: Arial, sans-serif;
                background-color: #fff7f0;
                color: #111827;
                padding: 40px;
            }
            #proposal-container h2, 
            #proposal-container h3 {
                color: #d97706;
            }
            #proposal-container table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 1rem;
                background-color: #ffffff;
                border: 1px solid #f97316;
            }
            #proposal-container th {
                background-color: #f97316;
                color: white;
                text-align: left;
                padding: 10px;
            }
            #proposal-container td {
                padding: 10px;
                border: 1px solid #f97316;
                vertical-align: top;
            }
            #proposal-container td.right {
                text-align: right;
            }
            #proposal-container p {
                margin: 10px 0;
            }
        </style>
    ';

    // Tabel Budget Plan
    $budgetPlan = '<table><thead><tr>
        <th>No</th>
        <th>Item</th>
        <th>Deskripsi</th>
        <th style="text-align:right;">Biaya (Rp)</th>
    </tr></thead><tbody>';

    foreach ($data['budget_items'] as $index => $item) {
        $description = $data['budget_descriptions'][$index] ?? '-';
        $cost = $data['budget_costs'][$index] ?? 0;
        $budgetPlan .= '<tr>';
        $budgetPlan .= '<td>' . ($index + 1) . '</td>';
        $budgetPlan .= '<td>' . htmlspecialchars($item) . '</td>';
        $budgetPlan .= '<td>' . htmlspecialchars($description) . '</td>';
        $budgetPlan .= '<td class="right">' . number_format($cost, 0, ',', '.') . '</td>';
        $budgetPlan .= '</tr>';
    }
    $budgetPlan .= '</tbody></table>';

    // Tabel Rundown
    $rundown = '<table><thead><tr>
        <th style="width: 30%;">Waktu</th>
        <th>Kegiatan</th>
    </tr></thead><tbody>';

    foreach ($data['rundown_times'] as $index => $time) {
        $activity = $data['rundown_activities'][$index] ?? '-';
        $rundown .= '<tr>';
        $rundown .= '<td>' . htmlspecialchars($time) . '</td>';
        $rundown .= '<td>' . htmlspecialchars($activity) . '</td>';
        $rundown .= '</tr>';
    }
    $rundown .= '</tbody></table>';

    // Final output with scoped container
    $proposal = <<<EOD
$style
<div id="proposal-container">
    <h2 style="text-align:center;">PROPOSAL SPONSORSHIP</h2>

    <strong>Yth.</strong><br>
    {$data['sponsor_name']}<br><br>
    
    <p>Sehubungan dengan acara {$data['name_event']} oleh {$data['name_community']} yang diselenggarakan pada tanggal {$data['date']} di {$data['location']}, kami selaku panitia pelaksana bermaksud mengajukan permohonan sponsorship kepada {$data['sponsor_name']}. Dukungan dari pihak Anda sangat kami harapkan guna mendukung kelancaran serta kesuksesan acara tersebut.</p><br>

    <strong>Informasi Komunitas dan Kegiatan</strong><br>
    <strong>Komunitas:</strong> {$data['name_community']}<br>
    <strong>Nama Event:</strong> {$data['name_event']}<br>
    <strong>Tanggal:</strong> {$data['date']}<br>
    <strong>Lokasi:</strong> {$data['location']}<br><br>

    <strong>Feedback & Benefit untuk Sponsor</strong><br>
    {$data['feedback_benefit']}<br><br>

    <strong>Rencana Anggaran Biaya</strong>
    $budgetPlan <br>

    <strong>Rundown Acara</strong>
    $rundown <br>

    <p>Demikian proposal ini kami sampaikan. Besar harapan kami untuk dapat bekerja sama dengan pihak sponsor demi kesuksesan acara ini. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>

    <p>Hormat kami,<br><br>
    [{$data['name_community']}]</p>
</div>
EOD;

    return $proposal;
}




public function previewProposal(Request $request)
{
    $request->validate([
        'sponsor_id' => 'required|exists:sponsors,id',
        'category' => 'required',
        'event' => 'required',
        'name_community' => 'required|string|max:255',
        'name_event' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'date' => 'required|date_format:Y-m-d',
        'feedback_benefit' => 'required|string',
        'budget_items' => 'required|array',
        'budget_items.*' => 'required|string',
        'budget_descriptions' => 'required|array',
        'budget_descriptions.*' => 'required|string',
        'budget_costs' => 'required|array',
        'budget_costs.*' => 'required|numeric',
        'rundown_times' => 'required|array',
        'rundown_times.*' => 'required|string',
        'rundown_activities' => 'required|array',
        'rundown_activities.*' => 'required|string',
    ]);

    $sponsor = Sponsor::findOrFail($request->sponsor_id);

    $proposal = $this->generateProposal([
        ...$request->all(),
        'sponsor_name' => $sponsor->title,
    ]);

    return view('dashboard.proposal-preview', [
        'proposal' => $proposal,
        'raw_proposal' => $request->proposal ?? json_encode($request->all()),

        // Kirim semua data yang dibutuhkan Blade
        'sponsor_id' => $request->sponsor_id,
        'category' => $request->category,
        'event' => $request->event,
        'name_community' => $request->name_community,
        'name_event' => $request->name_event,
        'location' => $request->location,
        'date_event' => $request->date,
        'feedback_benefit' => $request->feedback_benefit,
        'budget_items' => $request->budget_items ?? [],
        'budget_descriptions' => $request->budget_descriptions ?? [],
        'budget_costs' => $request->budget_costs ?? [],
        'rundown_times' => $request->rundown_times ?? [],
        'rundown_activities' => $request->rundown_activities ?? [],
    ]);
}

public function previewFromDatabase($id)
{
    $proposal = Proposal::with('sponsor')->where('id', $id)->where('user_id', auth()->id())->firstOrFail();

    $raw = json_decode($proposal->proposal_raw, true);

    $htmlProposal = $this->generateProposal([
        'sponsor_name' => $proposal->sponsor->title ?? 'Sponsor',
        'name_community' => $proposal->name_community,
        'name_event' => $proposal->name_event,
        'location' => $proposal->location,
        'date' => $proposal->date_event,
        'feedback_benefit' => $proposal->feedback_benefit,
        'budget_items' => $raw['budget_items'] ?? [],
        'budget_descriptions' => $raw['budget_descriptions'] ?? [],
        'budget_costs' => $raw['budget_costs'] ?? [],
        'rundown_times' => $raw['rundown_times'] ?? [],
        'rundown_activities' => $raw['rundown_activities'] ?? [],
    ]);

    return view('dashboard.preview', [
        'proposal' => $htmlProposal,
        'raw_proposal' => $proposal->proposal_raw,

        'sponsor_id' => $proposal->sponsor_id,
        'category' => $proposal->category,
        'event' => $proposal->event,
        'name_community' => $proposal->name_community,
        'name_event' => $proposal->name_event,
        'location' => $proposal->location,
        'date_event' => $proposal->date_event,
        'feedback_benefit' => $proposal->feedback_benefit,

        'budget_items' => $raw['budget_items'] ?? [],
        'budget_descriptions' => $raw['budget_descriptions'] ?? [],
        'budget_costs' => $raw['budget_costs'] ?? [],
        'rundown_times' => $raw['rundown_times'] ?? [],
        'rundown_activities' => $raw['rundown_activities'] ?? [],
    ]);
}




public function showUserProposals()
{
    // Ambil data proposals milik user yang login
    $userId = auth()->id();

    $proposals = Proposal::where('user_id', $userId)->get();

    // Kirim ke view
    return view('dashboard.index', compact('proposals'));
}

public function edit($id)
{
    $proposal = Proposal::where('id', $id)
                        ->where('user_id', auth()->id())
                        ->firstOrFail();

    $raw = json_decode($proposal->proposal_raw, true);

    $sponsors = Sponsor::all(); // Ambil semua data sponsor

    return view('dashboard.edit-proposal', [
        'proposal' => $proposal,
        'sponsors' => $sponsors, // Kirim data sponsor ke view
        'budget_items' => $raw['budget_items'] ?? [],
        'budget_descriptions' => $raw['budget_descriptions'] ?? [],
        'budget_costs' => $raw['budget_costs'] ?? [],
        'rundown_times' => $raw['rundown_times'] ?? [],
        'rundown_activities' => $raw['rundown_activities'] ?? [],
    ]);
}



public function update(Request $request, $id)
{
    $request->validate([
        'name_event' => 'required|string|max:255',
        'name_community' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'date_event' => 'required|date_format:Y-m-d',
        'feedback_benefit' => 'required|string',
        'budget_items' => 'required|array',
        'budget_descriptions' => 'required|array',
        'budget_costs' => 'required|array',
        'rundown_times' => 'required|array',
        'rundown_activities' => 'required|array',
    ]);

    $proposal = Proposal::where('id', $id)
                        ->where('user_id', auth()->id())
                        ->firstOrFail();

    $raw = json_encode([
        'budget_items' => $request->budget_items,
        'budget_descriptions' => $request->budget_descriptions,
        'budget_costs' => $request->budget_costs,
        'rundown_times' => $request->rundown_times,
        'rundown_activities' => $request->rundown_activities,
    ]);

    $proposal->update([
        'name_event' => $request->name_event,
        'name_community' => $request->name_community,
        'location' => $request->location,
        'date_event' => $request->date_event,
        'feedback_benefit' => $request->feedback_benefit,
        'proposal_raw' => $raw,
    ]);

    return redirect()->route('dashboard')->with('success', 'Proposal berhasil diperbarui.');
}

}
