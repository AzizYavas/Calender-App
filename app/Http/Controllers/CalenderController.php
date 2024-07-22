<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use PDF;

class CalenderController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function updateOrCreateEvent(Request $request)
    {

        if ($request->ajax()) {

            try {
                $data = $request->all();

                $allDay = $data['allDay'] ? 1 : 0;

                $updateData = [
                    'calender_id' => $data['uniqueId'],
                    'event_all_day' => $allDay,
                ];

                if (!empty($data['title'])) {
                    $updateData['event_title'] = $data['title'];
                }

                if (!empty($data['location'])) {
                    $updateData['event_location'] = $data['location'];
                }

                if (!empty($data['eventStart'])) {
                    $updateData['event_start'] = $data['eventStart'];
                }

                if (!empty($data['eventEnd'])) {
                    $updateData['event_end'] = $data['eventEnd'];
                }

                $event = Event::updateOrCreate(
                    ['calender_id' => $data['uniqueId']],
                    $updateData
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Event created successfully!',
                    'data' => $event
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage()
                ], 500);
            }
        }
    }

    public function deleteEvent(Request $request)
    {
        try {
            $uniqueId = $request->input('uniqueId');

            $event = Event::where('calender_id', $uniqueId)->first();

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found.'
                ], 404);
            }

            $event->delete();

            return response()->json([
                'success' => true,
                'message' => 'Event deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }


    public function generatePDF()
    {

        $allEventData = Event::all();

        $pdf = PDF::loadView('pdf', ['events' => $allEventData]);
        return $pdf->download('Etkinlik-Raporu.pdf');
    }
}
