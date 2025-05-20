<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;
use App\Models\Sponsor;
use App\Models\SponsorUser;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use \Cviebrock\EloquentSluggable\Services\SlugService;


class DashboardSponsorsController extends Controller
{
    

    public function index()
    {

        $user = Auth::user();

    if (!$user->is_company && !$user->is_community) {
        return redirect()->route('choose.account.type');
    }
    
        return view('dashboard.sponsors.index', [
            'sponsors' => Sponsor::where('user_id', auth()->user()->id)->get()
            
        ]);
    }

    public function dashboard()
    {
        $user = Auth::user();
        $sponsors = $user->sponsors; // Mengambil daftar sponsor yang sudah diambil oleh user

        return view('dashboard.index', [
            'user' => $user,
            'sponsors' => $sponsors,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        // Tidak perlu lagi melewati informasi kategori ke view
        return view('dashboard.sponsors.create');
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'title' => 'required|max:255',
        'slug' => 'required|unique:sponsors',
        'image' => 'image|file|max:51200',
        'body' => 'required',
        'category' => 'required|array',
        'category.*' => 'string|max:255',
        'event' => 'required|array',
        'event.*' => 'string|max:255',
    ]);

    // Simpan gambar jika ada
    if ($request->file('image')) {
        $validatedData['image'] = $request->file('image')->store('sponsor-images');
    }

    // Konversi array menjadi string agar bisa disimpan ke database (kolom string)
    $validatedData['category'] = implode(', ', $request->category);
    $validatedData['event'] = implode(', ', $request->event);

    // Tambahan data
    $validatedData['user_id'] = auth()->user()->id;
    $validatedData['excerpt'] = Str::limit(strip_tags($request->body), 100);

    // Simpan sponsor
    Sponsor::create($validatedData);

    return redirect('/dashboard/sponsors')->with('success', 'New sponsor has been added!');
}


    public function show(Sponsor $sponsor)
    {
        return view('dashboard.sponsors.show', [
            'sponsor' => $sponsor
        ]);
    }

    public function edit(Sponsor $sponsor)
    {
        return view('dashboard.sponsors.edit', [
            'sponsor' => $sponsor
        ]);
    }

    public function update(Request $request, Sponsor $sponsor)
    {
        $rules = [
            'title' => 'required|max:255',
            'image' => 'image|file|max:51200',
            'body' => 'required'
        ];

        if ($request->slug != $sponsor->slug) {
            $rules['slug'] = 'required|unique:sponsors';
        }

        $validatedData = $request->validate($rules);

        if ($request->file('image')) {
            // Sesuaikan pengelolaan gambar sesuai kebutuhan Anda
            // Jangan lupa untuk menghapus gambar yang lama jika diperlukan
            // Contoh: Storage::delete($sponsor->image);
            $validatedData['image'] = $request->file('image')->store('sponsor-images');
        }

        $validatedData['user_id'] = auth()->user()->id;
        $validatedData['excerpt'] = Str::limit(strip_tags($request->body), 200);

        $sponsor->update($validatedData);

        return redirect('/dashboard/sponsors')->with('success', 'Class has been updated!');
    }

    public function destroy(Sponsor $sponsor)
    {
        // Sesuaikan penghapusan file gambar jika diperlukan
        // Contoh: if ($sponsor->image) { Storage::delete($sponsor->image); }

        $sponsor->delete();

        return redirect('/dashboard/sponsors')->with('success', 'Class has been deleted!');
    }

    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(Sponsor::class, 'slug', $request->title);
        return response()->json(['slug' => $slug]);
    }

    // public function addsponsor(Sponsor $sponsor)
    // {
    //     return view('dashboard.addsponsor', [
    //         'sponsor' => $sponsor
    //     ]);
    // }

    public function addsponsor()
    {
        $sponsors = Sponsor::all();

        return view('dashboard.addsponsor', [
            'sponsors' => $sponsors
        ]);
    }

    public function takeSponsor(Request $request)
    {
        $validatedData = $request->validate([
            'sponsor_id' => 'required|exists:sponsors,id',
            'category' => 'required',
            'event' => 'required',
        ]);

        $user = auth()->user();

        // Periksa apakah pengguna memiliki kursus yang aktif pada SponsorUser
        $activeSponsor = SponsorUser::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if ($activeSponsor) {
            return back()->with('error', 'Anda masih memiliki kursus yang aktif lainnya.');
        }

        // Simpan data ke pivot table sponsor_user
        $sponsorUser = SponsorUser::create([
            'sponsor_id' => $validatedData['sponsor_id'],
            'user_id' => $user->id,
            'is_active' => true,
            'is_completed' => false,
        ]);

        if ($sponsorUser) {
            return redirect()->route('dashboard')->with('success', 'Sponsor added successfully.');
        } else {
            return back()->with('error', 'Failed to add sponsor.');
        }
    }


    public function storeSponsor(Request $request)
    {
        $validatedData = $request->validate([
            'sponsor_id' => 'required|exists:sponsors,id',
            'category' => 'required',
            'event' => 'required',
        ]);

        $user = auth()->user();

        // Simpan data ke pivot table sponsor_user
        $sponsorUser = SponsorUser::create([
            'sponsor_id' => $validatedData['sponsor_id'],
            'user_id' => $user->id,
            'is_active' => true, // Atur status aktif ke true jika baru ditambahkan
            'is_completed' => false, // Default nilai is_completed ke false
            // ... (menambahkan field lainnya)
        ]);

        if ($sponsorUser) {
            return back()->with('success', 'Sponsor added successfully.');
        } else {
            return back()->with('error', 'Failed to add sponsor.');
        }
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
        'date' => 'required|date_format:d/m/y',
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

        // 'sign' => 'required|string',
    ]);

    $sponsor = Sponsor::findOrFail($request->sponsor_id);

    $proposal = $this->generateProposal([
        'sponsor_name' => $sponsor->title,
        'category' => $request->category,
        'event' => $request->event,
        'name_community' => $request->name_community,
        'name_event' => $request->name_event,
        'location' => $request->location,
        'date' => $request->date,
        'feedback_benefit' => $request->feedback_benefit,
        'budget_items' => $request->budget_items,
'budget_descriptions' => $request->budget_descriptions,
'budget_costs' => $request->budget_costs,

        'rundown_times' => $request->rundown_times,
        'rundown_activities' => $request->rundown_activities,
        'sign' => $request->sign,
    ]);

    return view('dashboard.sponsors.proposal-preview', compact('proposal'));
}

}
