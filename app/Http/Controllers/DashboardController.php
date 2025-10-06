<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $events = [];
        
        try {
            // Coba ambil event mendatang dulu
            $upcomingResponse = Http::get('https://wikimedia.or.id/wp-json/tribe/events/v1/events', [
                'per_page' => 3,
                'start_date' => now()->format('Y-m-d'),
                'end_date' => now()->addYear()->format('Y-m-d'),
                'status' => 'publish',
                'order' => 'ASC' // Event terdekat dulu
            ]);

            if ($upcomingResponse->successful()) {
                $upcomingEvents = $upcomingResponse->json()['events'] ?? [];
                
                // Jika event mendatang kurang dari 6, ambil event yang sudah lewat
                if (count($upcomingEvents) < 3) {
                    $pastResponse = Http::get('https://wikimedia.or.id/wp-json/tribe/events/v1/events', [
                        'per_page' => (3 - count($upcomingEvents)),
                        'start_date' => '2000-01-01',
                        'end_date' => now()->subDay()->format('Y-m-d'),
                        'status' => 'publish',
                        'order' => 'DESC' // Event terbaru yang sudah lewat
                    ]);

                    if ($pastResponse->successful()) {
                        $pastEvents = $pastResponse->json()['events'] ?? [];
                        // Gabungkan: event mendatang + event yang sudah lewat
                        $events = array_merge($upcomingEvents, $pastEvents);
                    } else {
                        $events = $upcomingEvents;
                    }
                } else {
                    $events = $upcomingEvents;
                }
            } else {
                // Jika gagal ambil upcoming, ambil 6 event terbaru saja
                $allResponse = Http::get('https://wikimedia.or.id/wp-json/tribe/events/v1/events', [
                    'per_page' => 6,
                    'start_date' => '2000-01-01',
                    'end_date' => now()->addYear()->format('Y-m-d'),
                    'status' => 'publish',
                    'order' => 'DESC'
                ]);

                if ($allResponse->successful()) {
                    $events = $allResponse->json()['events'] ?? [];
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to fetch events: ' . $e->getMessage());
        }

        return Inertia::render('Dashboard', [
            'events' => $events
        ]);
    }
}