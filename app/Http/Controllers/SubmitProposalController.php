<?php

namespace App\Http\Controllers;

use App\Models\Sponsorship;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SubmitProposalController extends Controller
{
    /**
     * Menampilkan form pengajuan proposal
     * 
     * @param int|null $sponsorship ID sponsorship yang dipilih (opsional)
     * @return \Illuminate\View\View
     */
    public function create($sponsorship = null)
    {
        // Ambil semua data sponsorship
        $sponsorships = Sponsorship::all();
        $selectedSponsorship = null;

        // Jika ada parameter sponsorship, cari sponsorship tersebut
        if ($sponsorship) {
            $selectedSponsorship = Sponsorship::findOrFail($sponsorship);
        }

        // Tampilkan view dengan data yang diperlukan
        return view('dashboard.submitproposal', [
            'sponsorships' => $sponsorships,
            'selectedSponsorship' => $selectedSponsorship
        ]);
    }

    /**
     * Menyimpan data proposal baru
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Debug the incoming request data
        \Log::info('Incoming request data:', $request->all());

        try {
            // Validate the request data
            $validatedData = $request->validate([
                'sponsorship_id' => 'required|exists:sponsorships,id',
                'category' => 'required|string|max:255',
                'event' => 'required|string|max:255',
                'name_community' => 'required|string|max:255',
                'name_event' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'date_event' => 'required|date_format:Y-m-d',
                'feedback_benefit' => 'required|string',
                'budget_items' => 'required|array',
                'budget_descriptions' => 'required|array',
                'budget_costs' => 'required|array',
                'rundown_times' => 'required|array',
                'rundown_activities' => 'required|array',
                'event_documentations' => 'nullable|array',
            ]);

            // Get the authenticated user
            $user = auth()->user();
            
            // Prepare the raw proposal data
            $proposalData = [
                'budget_items' => $request->budget_items ?? [],
                'budget_descriptions' => $request->budget_descriptions ?? [],
                'budget_costs' => $request->budget_costs ?? [],
                'rundown_times' => $request->rundown_times ?? [],
                'rundown_activities' => $request->rundown_activities ?? []
            ];

            // Start database transaction
            DB::beginTransaction();

            // Create the proposal
            $proposal = new Proposal([
                'sponsorship_id' => $validatedData['sponsorship_id'],
                'user_id' => $user->id,
                'category' => $validatedData['category'],
                'event' => $validatedData['event'],
                'name_community' => $validatedData['name_community'],
                'name_event' => $validatedData['name_event'],
                'location' => $validatedData['location'],
                'date_event' => $validatedData['date_event'],
                'feedback_benefit' => $validatedData['feedback_benefit'],
                'proposal_raw' => json_encode($proposalData),
                'is_active' => true,
                'is_completed' => false,
                'is_reject' => false,
            ]);

            // Save the proposal first to get an ID
            $proposal->save();

            // Handle event documentations if any
            $eventDocs = [];
            
            // Try to get event_documentations from the proposal JSON if not in request directly
            if ($request->has('proposal') && is_string($request->proposal)) {
                $proposalData = json_decode($request->proposal, true);
                if (isset($proposalData['event_documentations']) && is_array($proposalData['event_documentations'])) {
                    $eventDocs = $proposalData['event_documentations'];
                }
            } elseif ($request->has('event_documentations') && is_array($request->event_documentations)) {
                $eventDocs = $request->event_documentations;
            }
            
            if (!empty($eventDocs)) {
                $proposalDir = 'event_documentations/' . $proposal->id;
                
                // Create directory if it doesn't exist
                if (!Storage::disk('public')->exists($proposalDir)) {
                    Storage::disk('public')->makeDirectory($proposalDir);
                }

                foreach ($eventDocs as $doc) {
                    if (empty($doc['file_path'])) {
                        continue;
                    }

                    // Get the relative path from the storage URL if it's a full URL
                    $filePath = $doc['file_path'];
                    if (strpos($filePath, 'storage/') !== false) {
                        $filePath = 'public/' . substr($filePath, strpos($filePath, 'storage/') + 8);
                    } elseif (strpos($filePath, 'http') === 0) {
                        // Skip if it's a full URL that doesn't point to our storage
                        continue;
                    }
                    
                    // Remove 'public/' prefix for Storage operations
                    $relativePath = str_replace('public/', '', $filePath);
                    
                    // Skip if temp file doesn't exist
                    if (!Storage::disk('public')->exists($relativePath)) {
                        \Log::warning("File not found in public disk: " . $relativePath);
                        continue;
                    }

                    $fileName = basename($relativePath);
                    $newPath = $proposalDir . '/' . $fileName;

                    try {
                        // Move the file from temp to permanent location
                        if (Storage::disk('public')->exists($relativePath)) {
                            // Ensure the target directory exists
                            $targetDir = dirname($newPath);
                            if (!Storage::disk('public')->exists($targetDir)) {
                                Storage::disk('public')->makeDirectory($targetDir, 0755, true);
                            }
                            
                            // Move the file
                            Storage::disk('public')->move($relativePath, $newPath);
                            
                            // Get file info
                            $fileInfo = [
                                'file_path' => $newPath,
                                'original_name' => $doc['original_name'] ?? $fileName,
                                'mime_type' => $doc['mime_type'] ?? Storage::disk('public')->mimeType($newPath) ?? 'application/octet-stream',
                                'size' => $doc['size'] ?? Storage::disk('public')->size($newPath) ?? 0,
                            ];
                            
                            // Create the event documentation record
                            $eventDoc = $proposal->eventDocumentations()->create($fileInfo);
                            \Log::info("Successfully saved event documentation: ", $fileInfo);
                            \Log::info("Event documentation record created with ID: " . $eventDoc->id);
                        } else {
                            \Log::warning("Source file does not exist: " . $relativePath);
                        }
                    } catch (\Exception $e) {
                        \Log::error("Error saving event documentation: " . $e->getMessage());
                        \Log::error($e->getTraceAsString());
                        continue;
                    }
                }

                // Clean up any remaining temp files
                $this->cleanupTempFiles();
            } else {
                \Log::warning('No event documentations found in request or proposal data');
                \Log::info('Request data: ', $request->all());
                if ($request->has('proposal') && is_string($request->proposal)) {
                    \Log::info('Proposal data: ', json_decode($request->proposal, true));
                }
            }

            // Commit the transaction
            DB::commit();

            // Redirect to dashboard with success message
            return redirect()
                ->route('dashboard.community')
                ->with('success', 'Proposal has been submitted successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            \Log::error('Validation error: ' . $e->getMessage());
            return back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            
            // Log the error
            \Log::error('Error submitting proposal: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            // Delete any uploaded files if they exist
            if (isset($proposal) && $proposal->exists) {
                if ($proposal->eventDocumentations()->exists()) {
                    foreach ($proposal->eventDocumentations as $doc) {
                        if (Storage::exists('public/' . $doc->file_path)) {
                            Storage::delete('public/' . $doc->file_path);
                        }
                    }
                }
                // Delete the proposal if it was created
                $proposal->delete();
            }
            
            // Return back with error message
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan proposal. Error: ' . $e->getMessage());
        }
    }

    /**
     * Method untuk mengambil/memproses data proposal
     * 
     * @param Request $request
     * @return void
     * @todo Implementasikan logika pengambilan proposal
     */
    public function take(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'sponsorship_id' => 'required|exists:sponsorships,id',
            'category' => 'required|string|max:255',
            'event' => 'required|string|max:255',
        ]);

        // Dapatkan user yang sedang login
        $user = auth()->user();
        
        // Method ini belum diimplementasikan sepenuhnya
        // TODO: Tambahkan logika pengambilan proposal
    }

    /**
     * Redirect ke halaman preview proposal
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function showPreview()
    {
        // Saat ini hanya redirect ke dashboard
        // TODO: Implementasikan logika preview proposal jika diperlukan
        return redirect()->route('dashboard');
    }

    /**
     * Clean up temporary uploaded files
     * 
     * @return void
     */
    private function cleanupTempFiles()
    {
        try {
            $files = Storage::disk('public')->allFiles('temp/event_documentations');
            foreach ($files as $file) {
                // Delete files older than 24 hours
                if (Storage::disk('public')->lastModified($file) < now()->subDay()->getTimestamp()) {
                    Storage::disk('public')->delete($file);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error cleaning up temp files: ' . $e->getMessage());
        }
    }
}
