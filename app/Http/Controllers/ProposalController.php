<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\EventDocumentation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Sponsorship;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class ProposalController extends Controller
{
    /**
     * Menampilkan daftar semua proposal (untuk admin)
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua proposal beserta relasi sponsorship dan user
        $proposals = Proposal::with(['sponsorship', 'user'])
            ->latest()
            ->get();
            
        return view('dashboard.proposals.index', compact('proposals'));
    }

    /**
     * Menampilkan daftar proposal yang terlihat oleh perusahaan
     * 
     * @return \Illuminate\View\View
     */
    public function companyDashboard()
    {
        // Ambil proposal yang tidak disembunyikan dari perusahaan
        $proposals = Proposal::where('hidden_from_company', false)
            ->with(['sponsorship', 'user'])
            ->latest()
            ->paginate(10);

        return view('dashboard.company.index', compact('proposals'));
    }





    /**
     * Menampilkan dashboard komunitas dengan proposal dan sponsorship
     * 
     * @return \Illuminate\View\View
     */
    public function communityDashboard()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();
        
        // Ambil data sponsorships dan proposal milik user
        $sponsorships = $user->sponsorships;
        $proposals = Proposal::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('dashboard.community', compact('user', 'sponsorships', 'proposals'));
    }

    /**
     * Menampilkan preview proposal sebelum disubmit
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function preview(Request $request)
    {
        // Clean up old temp files at the start of preview
        $this->cleanupTempFiles();
        // Generate HTML preview dari input
        $proposalHtml = $this->generateHtmlPreview($request->input('company_name'));

        // Ambil data dokumentasi acara jika ada
        $eventDocumentations = [];
        if ($request->has('event_documentations')) {
            // Dapatkan ID proposal dari request
            $proposalId = $request->input('id');
            
            // Pastikan ID proposal tersedia
            if (empty($proposalId)) {
                \Log::warning('No proposal ID provided for event documentations');
            }
            
            foreach ($request->input('event_documentations') as $doc) {
                if (is_array($doc) && !empty($doc['file_path'])) {
                    $filePath = ltrim($doc['file_path'], '/');
                    $fileName = basename($filePath);
                    
                    // Always use the proposal ID in the path if available
                    if (!empty($proposalId) && $proposalId !== 'temp') {
                        $newPath = 'event_documentations/' . $proposalId . '/' . $fileName;
                        
                        // If file is in temp, move it to the permanent location
                        if (str_contains($filePath, 'temp/')) {
                            $tempPath = 'public/' . ltrim($filePath, '/');
                            $newFullPath = 'public/' . $newPath;
                            
                            // Make sure the target directory exists
                            Storage::makeDirectory(dirname($newFullPath));
                            
                            // Move the file
                            if (Storage::exists($tempPath)) {
                                Storage::move($tempPath, $newFullPath);
                                $filePath = $newPath;
                                
                                // Log the move operation
                                \Log::info('Moved file from temp to permanent location', [
                                    'from' => $tempPath,
                                    'to' => $newFullPath,
                                    'public_path' => public_path('storage/' . $newPath)
                                ]);
                            } else {
                                \Log::error('Source file does not exist', [
                                    'temp_path' => $tempPath,
                                    'exists' => Storage::exists($tempPath)
                                ]);
                            }
                        }
                        // If file is not in temp but we have a proposal ID, update the path
                        elseif (!str_contains($filePath, 'event_documentations/' . $proposalId . '/')) {
                            $filePath = $newPath;
                        }
                    }
                    
                    // Generate URL untuk gambar
                    $imageUrl = asset('storage/' . ltrim($filePath, '/'));
                    
                    // Log untuk debugging
                    \Log::info('Generated image URL', [
                        'original_path' => $doc['file_path'],
                        'final_path' => $filePath,
                        'image_url' => $imageUrl,
                        'exists' => file_exists(public_path(parse_url($imageUrl, PHP_URL_PATH)))
                    ]);
                    
                    $eventDocumentations[] = [
                        'file_path' => $filePath,
                        'original_name' => $doc['original_name'] ?? 'documentation.jpg',
                        'mime_type' => $doc['mime_type'] ?? 'image/jpeg',
                        'size' => $doc['size'] ?? 0,
                        'image_url' => $imageUrl
                    ];
                    
                    // Debug log
                    \Log::info('Generated image URL:', [
                        'original_path' => $doc['file_path'],
                        'image_url' => $imageUrl,
                        'exists' => file_exists(public_path(parse_url($imageUrl, PHP_URL_PATH)))
                    ]);
                }
            }
        }

        // Kembalikan view preview dengan data yang diperlukan
        return view('dashboard.proposal.preview', [
            'proposal' => $proposalHtml,
            'raw_proposal' => $request->input('company_name'),
            'sponsorship_id' => $request->input('sponsorship_id'),
            'category' => $request->input('category'),
            'event' => $request->input('event'),
            'name_community' => $request->input('name_community'),
            'name_event' => $request->input('name_event'),
            'location' => $request->input('location'),
            'date_event' => $request->input('date_event'),
            'feedback_benefit' => $request->input('feedback_benefit'),
            'budget_items' => $request->input('budget_items', []),
            'budget_descriptions' => $request->input('budget_descriptions', []),
            'budget_costs' => $request->input('budget_costs', []),
            'rundown_times' => $request->input('rundown_times', []),
            'rundown_activities' => $request->input('rundown_activities', []),
            'eventDocumentations' => $eventDocumentations,
            'id' => $request->input('id'), // Make sure proposal ID is passed to the view
        ]);
    }

    /**
     * Menyimpan proposal yang baru dibuat
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitProposal(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'category' => 'required',
            'event' => 'required',
            'name_community' => 'required|string|max:255',
            'name_event' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'date_event' => 'required|date_format:Y-m-d',
            'feedback_benefit' => 'required|string',
            'sponsorship_id' => 'required|exists:sponsorships,id',
            'proposal' => 'required|string',
            'event_documentations' => 'nullable|array',
            'event_documentations.*' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        // Mulai database transaction
        DB::beginTransaction();

        try {
            // Buat proposal baru
            $proposal = new Proposal();
            $proposal->user_id = auth()->id();
            $proposal->sponsorship_id = $validated['sponsorship_id'];
            $proposal->category = $validated['category'];
            $proposal->event = $validated['event'];
            $proposal->name_community = $validated['name_community'];
            $proposal->name_event = $validated['name_event'];
            $proposal->location = $validated['location'];
            $proposal->date_event = $validated['date_event'];
            $proposal->feedback_benefit = $validated['feedback_benefit'];
            $proposal->proposal_raw = $validated['proposal'];
            $proposal->submit = true;
            $proposal->is_active = true; // Tampilkan di dashboard
            $proposal->save();

            // Handle file uploads
            if ($request->has('event_documentations') && is_array($request->event_documentations)) {
                foreach ($request->event_documentations as $doc) {
                    $docData = [];
                    
                    if (is_array($doc)) {
                        $docData = $doc;
                    } elseif (is_string($doc)) {
                        $decoded = json_decode($doc, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $docData = $decoded;
                        }
                    }
                    
                    if (!empty($docData) && isset($docData['file_path'])) {
                        // Check if file exists in temp location
                        $tempPath = 'public/' . $docData['file_path'];
                        $newPath = 'event_documentations/' . $proposal->id . '/' . basename($docData['file_path']);
                        
                        if (Storage::exists($tempPath)) {
                            // Move file from temp to permanent location
                            Storage::move($tempPath, 'public/' . $newPath);
                            
                            // Save to database
                            EventDocumentation::create([
                                'proposal_id' => $proposal->id,
                                'file_path' => $newPath,
                                'original_name' => $docData['original_name'] ?? basename($docData['file_path']),
                                'mime_type' => $docData['mime_type'] ?? 'image/jpeg',
                                'size' => $docData['size'] ?? 0,
                            ]);
                        }
                    }
                }
                
                // Clean up any remaining temp files
                $this->cleanupTempFiles();
            }

            // Commit transaction
            DB::commit();

            return redirect()
                ->route('dashboard.community')
                ->with('success', 'Proposal has been submitted successfully!');

        } catch (\Exception $e) {
            // Rollback transaction jika terjadi error
            DB::rollBack();
            
            // Hapus file yang sudah terupload jika ada error
            if (isset($proposal) && $proposal->id) {
                Storage::disk('public')->deleteDirectory('event_documentations/' . $proposal->id);
            }
            
            return back()
                ->withInput()
                ->with('error', 'Failed to submit proposal. Please try again.');
        }
    }

    /**
     * Memperbarui status proposal (diterima/ditolak)
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id)
    {
        // Cari proposal atau return 404 jika tidak ditemukan
        $proposal = Proposal::findOrFail($id);
        $feedback = $request->input('feedback', ''); // Ambil feedback jika ada

        // Proses penerimaan proposal
        if ($request->action === 'accept') {
            $proposal->update([
                'is_accept' => true,
                'is_active' => false, // Nonaktifkan dari daftar aktif
                'is_reject' => false,
                'feedback' => $feedback,
                'status' => 'accepted',
            ]);
            $message = 'Proposal has been accepted' . ($feedback ? ' with feedback.' : '.');
            return back()->with('success', $message);
        }

        // Proses penolakan proposal
        if ($request->action === 'reject') {
            $proposal->update([
                'is_accept' => false,
                'is_active' => false, // Nonaktifkan dari daftar aktif
                'is_reject' => true,
                'feedback' => $feedback,
                'status' => 'rejected',
            ]);
            $message = 'Proposal has been rejected' . ($feedback ? ' with feedback.' : '.');
            return back()->with('success', $message);
        }

        // Jika aksi tidak valid
        return back()->with('error', 'Invalid action.');
    }

    /**
     * Menampilkan detail proposal
     * 
     * @param int $id
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show($id)
    {
        // Ambil proposal dengan relasi sponsorship dan user
        $proposal = Proposal::with(['sponsorship', 'user'])
            ->findOrFail($id);
            
        return view('proposal.show', compact('proposal'));
    }

    /**
     * Menghapus proposal
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        // Cari dan hapus proposal
        $proposal = Proposal::findOrFail($id);
        $proposal->delete();

        return redirect()
            ->route('dashboard.community')
            ->with('success', 'Proposal has been deleted successfully!');
    }

    /**
     * Helper: Konversi teks biasa ke HTML
     * 
     * @param string $raw Teks yang akan dikonversi
     * @return string Teks dalam format HTML
     */
    private function generateHtmlPreview($raw)
    {
        // Konversi newline ke <br> dan escape karakter khusus HTML
        return nl2br(e($raw));
    }

    /**
     * Generate HTML untuk preview proposal
     * 
     * @param array $data Data proposal yang akan di-generate
     * @return string HTML yang sudah diformat
     */
    private function generateProposal(array $data): string
    {
        // Style untuk proposal
        $style = '
        <style>
            #proposal-container {
                font-family: Arial, sans-serif;
                background-color: #fff7f0;
                color: #111827;
                padding: 40px;
                position: relative;
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
            /* Image gallery styles */
            .image-gallery {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 1rem;
                margin: 1rem 0;
            }
            .image-container {
                position: relative;
                cursor: pointer;
                overflow: hidden;
                border-radius: 0.5rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                transition: transform 0.3s ease;
            }
            .image-container:hover {
                transform: translateY(-2px);
            }
            .image-container img {
                width: 100%;
                height: 150px;
                object-fit: cover;
                display: block;
            }
            .image-overlay {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.3);
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .image-container:hover .image-overlay {
                opacity: 1;
            }
            .eye-icon {
                background-color: rgba(255, 255, 255, 0.8);
                border-radius: 50%;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .eye-icon svg {
                width: 24px;
                height: 24px;
                color: #374151;
            }
            /* Modal styles */
            .image-modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.8);
                z-index: 1000;
                align-items: center;
                justify-content: center;
                padding: 1rem;
            }
            .modal-content {
                position: relative;
                max-width: 90%;
                max-height: 90vh;
            }
            .modal-content img {
                max-width: 100%;
                max-height: 80vh;
                display: block;
                margin: 0 auto;
                border-radius: 0.5rem;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }
            .close-modal {
                position: absolute;
                top: -40px;
                right: 0;
                color: white;
                font-size: 30px;
                font-weight: bold;
                cursor: pointer;
                background: none;
                border: none;
                padding: 5px 15px;
            }
            .close-modal:hover {
                color: #f97316;
            }
        </style>';

        // Generate tabel Budget Plan
        $budgetPlan = $this->generateBudgetPlanTable($data);
        
        // Generate tabel Rundown Acara
        $rundown = $this->generateRundownTable($data);

        // Generate event documentation section if there are any
        $eventDocumentation = '';
        if (!empty($data['event_documentations'])) {
            $eventDocumentation = '
            <div class="mt-6">
                <h3>Dokumentasi Acara Sebelumnya</h3>
                <div class="image-gallery">';
            
            foreach ($data['event_documentations'] as $doc) {
                if (empty($doc['file_path'])) continue;
                
                $filePath = ltrim($doc['file_path'], '/');
                $imageUrl = asset('storage/' . $filePath);
                
                $eventDocumentation .= <<<DOC
                    <div class="image-container" onclick="openImageModal('$imageUrl')">
                        <img src="$imageUrl" alt="Dokumentasi Acara">
                        <div class="image-overlay">
                            <div class="eye-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                DOC;
            }
            
            // Add modal for image preview
            $eventDocumentation .= '
                </div>
                <!-- Image Preview Modal -->
                <div id="imageModal" class="image-modal">
                    <div class="modal-content">
                        <button class="close-modal" onclick="closeImageModal()">&times;</button>
                        <img id="modalImage" src="" alt="Preview">
                    </div>
                </div>
                <script>
                    function openImageModal(src) {
                        const modal = document.getElementById("imageModal");
                        const modalImg = document.getElementById("modalImage");
                        modal.style.display = "flex";
                        modalImg.src = src;
                        document.body.style.overflow = "hidden";
                    }
                    
                    function closeImageModal() {
                        const modal = document.getElementById("imageModal");
                        modal.style.display = "none";
                        document.body.style.overflow = "auto";
                    }
                    
                    // Close modal when clicking outside the image
                    document.getElementById("imageModal").addEventListener("click", function(e) {
                        if (e.target === this) {
                            closeImageModal();
                        }
                    });
                    
                    // Close with ESC key
                    document.addEventListener("keydown", function(e) {
                        if (e.key === "Escape") {
                            closeImageModal();
                        }
                    });
                </script>
            </div>';
        }
        // Format tanggal ke bahasa Indonesia
        $formattedDate = $this->formatIndonesianDate($data['date']);
        
        // Generate HTML final
        return <<<EOD
        $style
        <div id="proposal-container">
            <h2 style="text-align:center;">PROPOSAL SPONSORSHIP</h2>
            

            <strong>Yth.</strong><br>
            {$data['sponsorship_name']}<br><br>
            
            <p>Sehubungan dengan acara {$data['name_event']} oleh {$data['name_community']} yang diselenggarakan pada tanggal {$formattedDate} di {$data['location']}, kami selaku panitia pelaksana bermaksud mengajukan permohonan sponsorship kepada {$data['sponsorship_name']}. Dukungan dari pihak Anda sangat kami harapkan guna mendukung kelancaran serta kesuksesan acara tersebut.</p><br>

            <strong>Informasi Komunitas dan Kegiatan</strong><br>
            <strong>Komunitas:</strong> {$data['name_community']}<br>
            <strong>Nama Event:</strong> {$data['name_event']}<br>
            <strong>Tanggal:</strong> {$formattedDate}<br>
            <strong>Lokasi:</strong> {$data['location']}<br><br>

            <strong>Feedback & Benefit untuk Sponsor</strong><br>
            {$data['feedback_benefit']}<br><br>

            <strong>Rencana Anggaran Biaya</strong>
            $budgetPlan <br>

            <strong>Rundown Acara</strong>
            $rundown <br>

            $eventDocumentation

            <p>Demikian proposal ini kami sampaikan. Besar harapan kami untuk dapat bekerja sama dengan pihak sponsor demi kesuksesan acara ini. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p><br>

            <p>Hormat kami,<br><br>
            [{$data['name_community']}]</p>
        </div>
        EOD;
    }

    /**
     * Generate tabel Budget Plan
     * 
     * @param array $data Data untuk tabel
     * @return string HTML tabel
     */
    private function generateBudgetPlanTable(array $data): string
    {
        $html = '<table><thead><tr>
            <th>No</th>
            <th>Item</th>
            <th>Deskripsi</th>
            <th style="text-align:right;">Biaya (IDR)</th>
        </tr></thead><tbody>';

        $total = 0;
        
        foreach ($data['budget_items'] as $index => $item) {
            $description = $data['budget_descriptions'][$index] ?? '-';
            $cost = (float)($data['budget_costs'][$index] ?? 0);
            $total += $cost;
            
            $html .= '<tr>';
            $html .= '<td>' . ($index + 1) . '</td>';
            $html .= '<td>' . htmlspecialchars($item) . '</td>';
            $html .= '<td>' . htmlspecialchars($description) . '</td>';
            $html .= '<td class="right">' . number_format($cost, 0, ',', '.') . '</td>';
            $html .= '</tr>';
        }
        
        // Add total row
        $html .= '<tr style="font-weight: bold; border-top: 1px solid #000;">';
        $html .= '<td colspan="3" style="text-align: center;">Total Biaya</td>';
        $html .= '<td class="right">Rp ' . number_format($total, 0, ',', '.') . '</td>';
        $html .= '</tr>';
        
        $html .= '</tbody></table>';
        
        return $html;
    }

    /**
     * Generate tabel Rundown Acara
     * 
     * @param array $data Data untuk tabel
     * @return string HTML tabel
     */
    private function generateRundownTable(array $data): string
    {
        $html = '<table><thead><tr>
            <th style="width: 30%;">Waktu</th>
            <th>Aktivitas</th>
        </tr></thead><tbody>';

        foreach (($data['rundown_times'] ?? []) as $index => $time) {
            $activity = $data['rundown_activities'][$index] ?? '-';
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($time) . '</td>';
            $html .= '<td>' . htmlspecialchars($activity) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        
        return $html;
    }

    /**
     * Format tanggal ke bahasa Indonesia
     * 
     * @param string $date Tanggal dalam format Y-m-d
     * @return string Tanggal yang sudah diformat (contoh: Sabtu, 13 Juli 2025)
     */
    private function formatIndonesianDate(string $date): string
    {
        // Nama hari dalam bahasa Indonesia
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        // Nama bulan dalam bahasa Indonesia
        $months = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', 
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        
        $dateParts = explode('-', $date);
        if (count($dateParts) === 3) {
            $year = $dateParts[0];
            $monthNum = $dateParts[1];
            $day = ltrim($dateParts[2], '0');
            
            // Buat objek Carbon untuk mendapatkan nama hari
            $carbonDate = Carbon::createFromFormat('Y-m-d', $date);
            $dayName = $days[$carbonDate->format('l')];
            $monthName = $months[$monthNum] ?? $monthNum;
            
            return "$dayName, $day $monthName $year";
        }
        
        return $date;
    }




    /**
     * Menampilkan preview proposal sebelum disimpan
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function previewProposal(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'sponsorship_id' => 'required|exists:sponsorships,id',
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
            'event_documentations' => 'nullable|array',
            'event_documentations.*' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);
        
        // Handle file uploads
        $documentations = [];
        if ($request->hasFile('event_documentations')) {
            foreach ($request->file('event_documentations') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('temp/event_documentations', 'public');
                    $documentations[] = [
                        'original_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'mime_type' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                    ];
                }
            }
        } elseif ($request->has('event_documentations') && is_array($request->event_documentations)) {
            // Handle case when coming back from preview with existing files
            foreach ($request->event_documentations as $doc) {
                if (is_array($doc)) {
                    $documentations[] = $doc;
                } elseif (is_string($doc)) {
                    $docData = json_decode($doc, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $documentations[] = $docData;
                    }
                }
            }
        }

        // Cari data sponsorship
        $sponsorship = Sponsorship::findOrFail($request->sponsorship_id);

        // Prepare proposal data
        $proposalData = [
            'sponsorship_name' => $sponsorship->title,
            'category' => $request->category,
            'event' => $request->event,
            'name_community' => $request->name_community,
            'name_event' => $request->name_event,
            'location' => $request->location,
            'date' => $request->date,
            'feedback_benefit' => $request->feedback_benefit,
            'budget_items' => $request->budget_items ?? [],
            'budget_descriptions' => $request->budget_descriptions ?? [],
            'budget_costs' => $request->budget_costs ?? [],
            'rundown_times' => $request->rundown_times ?? [],
            'rundown_activities' => $request->rundown_activities ?? [],
            'event_documentations' => $documentations
        ];

        // Generate HTML proposal
        $proposal = $this->generateProposal($proposalData);

        // Ensure all documentations have full URLs and correct paths
        $documentations = array_map(function($doc) use ($request) {
            // If the path is already a full URL, extract just the filename
            if (str_starts_with($doc['file_path'], 'http')) {
                $fileName = basename(parse_url($doc['file_path'], PHP_URL_PATH));
                $filePath = 'temp/event_documentations/' . $fileName;
            } else {
                // Clean up the file path
                $filePath = ltrim(str_replace('public/', '', $doc['file_path']), '/');
                $fileName = basename($filePath);
                
                // If the file is already in the event_documentations directory, use it directly
                if (strpos($filePath, 'event_documentations/') === 0) {
                    $targetPath = $filePath;
                    $imageUrl = asset('storage/' . $targetPath);
                    
                    return [
                        'file_path' => $targetPath,
                        'original_name' => $doc['original_name'],
                        'mime_type' => $doc['mime_type'],
                        'size' => $doc['size'],
                        'image_url' => $imageUrl,
                        'is_existing' => true
                    ];
                }
            }
            
            // For new files, copy to temp directory
            $tempId = 'temp_' . uniqid();
            $targetDir = 'event_documentations/' . $tempId;
            $targetPath = $targetDir . '/' . $fileName;
            $fullTargetPath = storage_path('app/public/' . $targetPath);
            $sourcePath = storage_path('app/public/' . $filePath);
            
            // Only copy if source exists and target doesn't exist
            if (file_exists($sourcePath) && !file_exists($fullTargetPath)) {
                // Create target directory if it doesn't exist
                if (!file_exists(dirname($fullTargetPath))) {
                    mkdir(dirname($fullTargetPath), 0755, true);
                }
                copy($sourcePath, $fullTargetPath);
            }
            
            // Generate the full public URL
            $imageUrl = asset('storage/' . $targetPath);
            
            return [
                'file_path' => $targetPath,
                'original_name' => $doc['original_name'],
                'mime_type' => $doc['mime_type'],
                'size' => $doc['size'],
                'image_url' => $imageUrl,
                'is_existing' => false
            ];
        }, $documentations);

        // Kembalikan view proposal-preview dengan data yang diperlukan
        return view('dashboard.proposal.proposal-preview', [
            'proposal' => $proposal,
            'raw_proposal' => json_encode($proposalData),
            'sponsorship_id' => $request->sponsorship_id,
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
            'event_documentations' => $documentations,
        ]);
    }

    /**
     * Menampilkan preview proposal yang sudah ada di database
     * 
     * @param int $id ID proposal
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function previewFromDatabase($id)
    {
        // Cari proposal yang bisa diakses oleh user yang login
        $proposal = Proposal::with(['sponsorship', 'eventDocumentations'])
            ->where('id', $id)
            ->firstOrFail();
            
        // Decode proposal raw data
        $raw = json_decode($proposal->proposal_raw, true) ?? [];
        
        // Get event documentations with correct paths and ensure no duplicates
        $processedFiles = [];
        $eventDocumentations = $proposal->eventDocumentations->filter(function($doc) use (&$processedFiles) {
            // Get the file path from the database
            $filePath = $doc->file_path;
            
            // Skip if no file path
            if (empty($filePath)) {
                return false;
            }
            
            // Remove any leading slashes or backslashes
            $filePath = ltrim($filePath, '/\\');
            
            // If the path is already a full URL, extract just the filename
            if (str_starts_with($filePath, 'http')) {
                $filePath = 'event_documentations/' . $proposal->id . '/' . basename(parse_url($filePath, PHP_URL_PATH));
            }
            
            // Ensure the path is in the correct format
            if (!str_starts_with($filePath, 'event_documentations/')) {
                $filePath = 'event_documentations/' . $proposal->id . '/' . basename($filePath);
            }
            
            // Create a unique key for this file
            $fileKey = md5($filePath);
            
            // Skip if we've already processed this file
            if (in_array($fileKey, $processedFiles)) {
                return false;
            }
            
            // Add to processed files
            $processedFiles[] = $fileKey;
            return true;
            
        })->map(function($doc) use ($proposal) {
            $filePath = $doc->file_path;
            $filePath = ltrim($filePath, '/\\');
            
            if (str_starts_with($filePath, 'http')) {
                $filePath = 'event_documentations/' . $proposal->id . '/' . basename(parse_url($filePath, PHP_URL_PATH));
            }
            
            if (!str_starts_with($filePath, 'event_documentations/')) {
                $filePath = 'event_documentations/' . $proposal->id . '/' . basename($filePath);
            }
            
            return [
                'id' => $doc->id,
                'file_path' => $filePath,
                'original_name' => $doc->original_name,
                'mime_type' => $doc->mime_type,
                'size' => $doc->size
            ];
        })->values()->toArray();
        
        // Prepare proposal data
        $proposalData = [
            'sponsorship_name' => $proposal->sponsorship->title,
            'category' => $proposal->category,
            'event' => $proposal->event,
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
            'event_documentations' => $eventDocumentations
        ];
            
        // Generate HTML proposal
        $proposalHtml = $this->generateProposal($proposalData);
        
        // Fix double-prefixed image URLs in the proposal HTML
        $proposalHtml = preg_replace(
            '|src="http[s]?://[^/]+/storage/http[s]?://[^/]+/storage/|', 
            'src="' . url('/storage/'), 
            $proposalHtml
        );

        // Prepare view data
        $viewData = [
            'proposal' => $proposalHtml,
            'raw_proposal' => json_encode($proposalData),
            'proposal_data' => $proposal,
            'from_database' => true,
            'sponsorship_id' => $proposal->sponsorship_id,
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
        ];

        // Only add event documentations if we have any
        if (!empty($eventDocumentations)) {
            $viewData['event_documentations'] = $eventDocumentations;
        }

        // Use the preview.blade.php view for database previews
        return view('dashboard.proposal.preview', $viewData);
    }




    /**
     * Menampilkan daftar proposal milik user yang login
     * 
     * @return \Illuminate\View\View
     */
    public function showUserProposals()
    {
        // Ambil data proposals milik user yang login
        $proposals = Proposal::where('user_id', auth()->id())
            ->with(['sponsorship', 'user'])
            ->latest()
            ->paginate(10);

        return view('dashboard.index', compact('proposals'));
    }

    /**
     * Menampilkan form edit proposal
     * 
     * @param int $id ID proposal
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function edit($id)
    {
        // Cari proposal yang dimiliki oleh user yang login
        $proposal = Proposal::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Cek jika proposal sudah diterima/ditolak
        if ($proposal->is_accept || $proposal->is_reject) {
            return redirect()
                ->route('dashboard.community')
                ->with('error', 'Proposal cannot be edited because it has been accepted or rejected.');
        }

        // Decode data JSON dari proposal_raw
        $raw = json_decode($proposal->proposal_raw, true) ?? [];
        
        // Ambil semua data sponsorship untuk dropdown
        $sponsorships = Sponsorship::all();

        // Get existing event documentations with full URLs and ensure no duplicates
        $processedFiles = [];
        $eventDocumentations = $proposal->eventDocumentations->filter(function($doc) use (&$processedFiles) {
            // Get the file path from the database
            $filePath = $doc->file_path;
            
            // Skip if no file path
            if (empty($filePath)) {
                return false;
            }
            
            // Remove any leading slashes or backslashes
            $filePath = ltrim($filePath, '/\\');
            
            // If the path is already a full URL, extract just the filename
            if (str_starts_with($filePath, 'http')) {
                $filePath = 'event_documentations/' . $doc->proposal_id . '/' . basename(parse_url($filePath, PHP_URL_PATH));
            }
            
            // Ensure the path is in the correct format
            if (!str_starts_with($filePath, 'event_documentations/')) {
                $filePath = 'event_documentations/' . $doc->proposal_id . '/' . basename($filePath);
            }
            
            // Create a unique key for this file
            $fileKey = md5($filePath);
            
            // Skip if we've already processed this file
            if (in_array($fileKey, $processedFiles)) {
                return false;
            }
            
            // Add to processed files
            $processedFiles[] = $fileKey;
            return true;
            
        })->map(function($doc) {
            $filePath = $doc->file_path;
            $filePath = ltrim($filePath, '/\\');
            
            if (str_starts_with($filePath, 'http')) {
                $filePath = 'event_documentations/' . $doc->proposal_id . '/' . basename(parse_url($filePath, PHP_URL_PATH));
            }
            
            if (!str_starts_with($filePath, 'event_documentations/')) {
                $filePath = 'event_documentations/' . $doc->proposal_id . '/' . basename($filePath);
            }
            
            return [
                'id' => $doc->id,
                'file_path' => asset('storage/' . $filePath),
                'original_name' => $doc->original_name,
                'mime_type' => $doc->mime_type,
                'size' => $doc->size
            ];
        })->values()->toArray();

        return view('dashboard.proposal.edit-proposal', [
            'proposal' => $proposal,
            'sponsorships' => $sponsorships,
            'budget_items' => $raw['budget_items'] ?? [],
            'budget_descriptions' => $raw['budget_descriptions'] ?? [],
            'budget_costs' => $raw['budget_costs'] ?? [],
            'rundown_times' => $raw['rundown_times'] ?? [],
            'rundown_activities' => $raw['rundown_activities'] ?? [],
            'event_documentations' => $eventDocumentations,
            'activePage' => 'dashboard',
        ]);
    }

    /**
     * Memperbarui data proposal
     * 
     * @param Request $request
     * @param int $id ID proposal
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(Request $request, $id)
    {
        // Cari proposal yang dimiliki oleh user yang login
        $proposal = Proposal::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Cek jika proposal sudah diterima/ditolak
        if ($proposal->is_accept || $proposal->is_reject) {
            return redirect()
                ->route('dashboard.community')
                ->with('error', 'Proposal cannot be updated because it has been accepted or rejected.');
        }

        // Validasi input
        $validated = $request->validate([
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
            'event_documentations' => 'nullable|array',
            'event_documentations.*' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'existing_documentations' => 'nullable|array',
            'existing_documentations.*' => 'nullable|string',
        ]);

        // Start database transaction for the entire update process
        \DB::beginTransaction();

        try {
            // Handle removed documentations first
            $existingDocIds = $proposal->eventDocumentations()->pluck('id')->toArray();
            $keptDocIds = [];
            
            if ($request->has('existing_documentations') && is_array($request->existing_documentations)) {
                foreach ($request->existing_documentations as $docId) {
                    if (is_numeric($docId) && in_array($docId, $existingDocIds)) {
                        $keptDocIds[] = $docId;
                    }
                }
            }
            
            // Delete documentations that were not kept and not in the kept list
            $docsToDelete = array_diff($existingDocIds, $keptDocIds);
            
            if (!empty($docsToDelete)) {
                $docsToDelete = $proposal->eventDocumentations()->whereIn('id', $docsToDelete)->get();
                
                foreach ($docsToDelete as $doc) {
                    if (Storage::disk('public')->exists($doc->file_path)) {
                        Storage::disk('public')->delete($doc->file_path);
                    }
                    $doc->delete();
                }
            }

            // Process new file uploads
            $uploadedCount = 0;
            if ($request->hasFile('event_documentations')) {
                $files = $request->file('event_documentations');
                
                // Ensure $files is an array
                if (!is_array($files)) {
                    $files = [$files];
                }
                
                // Log the number of files received
                \Log::info('Starting file upload process', [
                    'total_files' => count($files),
                    'proposal_id' => $proposal->id
                ]);
                
                // No file limit check - unlimited uploads allowed
                
                foreach ($files as $index => $file) {
                    try {
                        // Log file info
                        \Log::info("Processing file {$index}", [
                            'name' => $file->getClientOriginalName(),
                            'size' => $file->getSize(),
                            'mime' => $file->getMimeType(),
                            'is_valid' => $file->isValid()
                        ]);
                        
                        if (!$file->isValid()) {
                            throw new \Exception('File is not valid: ' . $file->getErrorMessage());
                        }
                        
                        // Create directory if it doesn't exist
                        $directory = 'event_documentations/' . $proposal->id;
                        if (!Storage::disk('public')->exists($directory)) {
                            Storage::disk('public')->makeDirectory($directory);
                        }
                        
                        // Generate a unique filename
                        $filename = uniqid() . '_' . $file->getClientOriginalName();
                        $path = $file->storeAs($directory, $filename, 'public');
                        
                        // Create database record
                        $proposal->eventDocumentations()->create([
                            'file_path' => $path,
                            'original_name' => $file->getClientOriginalName(),
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize(),
                        ]);
                        
                        $uploadedCount++;
                        \Log::info('File uploaded successfully', [
                            'original_name' => $file->getClientOriginalName(),
                            'saved_path' => $path,
                            'uploaded_count' => $uploadedCount
                        ]);
                        
                    } catch (\Exception $e) {
                        \Log::error('Error uploading file: ' . $e->getMessage(), [
                            'file' => $file ? $file->getClientOriginalName() : 'unknown',
                            'trace' => $e->getTraceAsString()
                        ]);
                        throw $e; // Re-throw to be caught by the outer try-catch
                    }
                }
                
                \Log::info('File upload process completed', [
                    'total_uploaded' => $uploadedCount,
                    'total_attempted' => count($files)
                ]);
            }
            
            // Encode data untuk disimpan ke proposal_raw
            $raw = json_encode([
                'budget_items' => $request->budget_items,
                'budget_descriptions' => $request->budget_descriptions,
                'budget_costs' => $request->budget_costs,
                'rundown_times' => $request->rundown_times,
                'rundown_activities' => $request->rundown_activities,
            ]);

            // Update data proposal
            $proposal->update([
                'name_event' => $request->name_event,
                'name_community' => $request->name_community,
                'location' => $request->location,
                'date_event' => $request->date_event,
                'feedback_benefit' => $request->feedback_benefit,
                'proposal_raw' => $raw,
            ]);
            
            // Commit the transaction
            \DB::commit();
            
            return redirect()
                ->route('dashboard.community')
                ->with('success', 'Proposal has been updated successfully!');
                
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            \DB::rollBack();
            \Log::error('Error updating proposal: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Failed to update proposal: ' . $e->getMessage());
        }

        // Encode data untuk disimpan ke proposal_raw
        $raw = json_encode([
            'budget_items' => $request->budget_items,
            'budget_descriptions' => $request->budget_descriptions,
            'budget_costs' => $request->budget_costs,
            'rundown_times' => $request->rundown_times,
            'rundown_activities' => $request->rundown_activities,
        ]);

        // Update data proposal
        $proposal->update([
            'name_event' => $request->name_event,
            'name_community' => $request->name_community,
            'location' => $request->location,
            'date_event' => $request->date_event,
            'feedback_benefit' => $request->feedback_benefit,
            'proposal_raw' => $raw,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Proposal has been updated successfully!');
    }

    /**
     * Menyembunyikan proposal dari daftar perusahaan
     * 
     * @param int $id ID proposal
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function hideFromCompany($id)
    {
        $proposal = Proposal::findOrFail($id);
        $proposal->hidden_from_company = true;
        $proposal->save();

        return redirect()
            ->back()
            ->with('success', 'Proposal has been hidden from company view!');
    }

    /**
     * Menampilkan kembali proposal yang disembunyikan
     * 
     * @param int $id ID proposal
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function unhideFromCompany($id)
    {
        $proposal = Proposal::findOrFail($id);
        $proposal->hidden_from_company = false;
        $proposal->save();

        return redirect()
            ->back()
            ->with('success', 'Proposal has been restored to company view!');
    }

    /**
     * Menampilkan daftar proposal yang disembunyikan oleh perusahaan
     * 
     * @return \Illuminate\View\View
     */
    public function hidden()
    {
        $user = Auth::user();
            
        // Ambil proposal yang disembunyikan untuk sponsorship milik perusahaan user
        $proposals = Proposal::where('hidden_from_company', true)
            ->whereHas('sponsorship', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['sponsorship', 'user'])
            ->latest()
            ->paginate(10);
                
        return view('dashboard.hidden', compact('proposals'));
    }

    /**
     * Clean up temporary uploaded files and folders
     * 
     * @return void
     */
    protected function cleanupTempFiles()
    {
        try {
            $tempDir = storage_path('app/public/event_documentations');
            
            // Skip if directory doesn't exist
            if (!File::exists($tempDir)) {
                return;
            }
            
            $directories = File::directories($tempDir);
            $now = time();
            $maxAge = 24 * 60 * 60; // 24 hours in seconds
            
            foreach ($directories as $directory) {
                $dirName = basename($directory);
                
                // Only process temp_* directories
                if (str_starts_with($dirName, 'temp_')) {
                    $dirTime = File::lastModified($directory);
                    
                    // If directory is older than maxAge, delete it
                    if (($now - $dirTime) > $maxAge) {
                        File::deleteDirectory($directory);
                        Log::info("Cleaned up old temp directory: " . $dirName);
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error cleaning up temp files: ' . $e->getMessage());
        }
    }

    /**
     * Export proposal as PDF
     * 
     * @param Request $request
     * @return \Barryvdh\DomPDF\PDF
     */
    public function exportPdf(Request $request)
    {
        // Get all the request data
        $data = $request->all();
        
        // Get the sponsorship details
        $sponsorship = Sponsorship::findOrFail($data['sponsorship_id']);
        
        // Process event documentations if they exist in the request
        $eventDocumentations = [];
        if (isset($data['event_documentations']) && is_array($data['event_documentations'])) {
            foreach ($data['event_documentations'] as $doc) {
                if (is_array($doc) && !empty($doc['file_path'])) {
                    $filePath = ltrim($doc['file_path'], '/');
                    $eventDocumentations[] = [
                        'file_path' => $filePath,
                        'original_name' => $doc['original_name'] ?? basename($filePath),
                        'mime_type' => $doc['mime_type'] ?? 'image/jpeg',
                        'size' => $doc['size'] ?? 0,
                        'image_url' => asset('storage/' . $filePath)
                    ];
                }
            }
        }
        
        // Prepare the data for the PDF view
        $proposalData = [
            'sponsorship_name' => $sponsorship->title,
            'name_community' => $data['name_community'],
            'name_event' => $data['name_event'],
            'location' => $data['location'],
            'date' => $data['date_event'],
            'feedback_benefit' => $data['feedback_benefit'],
            'budget_items' => $data['budget_items'] ?? [],
            'budget_descriptions' => $data['budget_descriptions'] ?? [],
            'budget_costs' => $data['budget_costs'] ?? [],
            'rundown_times' => $data['rundown_times'] ?? [],
            'rundown_activities' => $data['rundown_activities'] ?? [],
            'event_documentations' => $eventDocumentations
        ];
        
        // Generate the HTML content using the same method as the preview
        $html = $this->generateProposal($proposalData);
        
        // Generate a filename for the PDF
        $filename = 'Proposal_' . str_replace(' ', '_', $sponsorship->title) . '_' . now()->format('Ymd_His') . '.pdf';
        
        // Load the HTML into the PDF
        $pdf = PDF::loadHTML($html);
        
        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');
        
        // Return the PDF as a download
        return $pdf->download($filename);
    }
}