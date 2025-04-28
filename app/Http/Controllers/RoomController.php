<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class RoomController extends Controller
{
    public $rooms = [];

    public function __construct()
    {
        if (!Session::has('rooms')) {
            $this->initializeRooms();
        }
    }

    private function initializeRooms()
    {
        $rooms = [];
        // Floors 1-9
        for ($floor = 1; $floor <= 9; $floor++) {
            for ($i = 1; $i <= 10; $i++) {
                $roomNo = $floor * 100 + $i;
                $rooms[$roomNo] = ['floor' => $floor, 'booked' => false];
            }
        }
        // Floor 10
        for ($i = 1; $i <= 7; $i++) {
            $roomNo = 1000 + $i;
            $rooms[$roomNo] = ['floor' => 10, 'booked' => false];
        }

        Session::put('rooms', $rooms);
    }

    public function index()
    {
        $rooms = Session::get('rooms');
        return view('rooms', compact('rooms'));
    }

    public function bookRooms(Request $request)
    {
        $count = $request->input('count');

        if ($count < 1 || $count > 5) {
            return redirect('/')->with('error', 'You can only book between 1 to 5 rooms.');
        }

        $rooms = Session::get('rooms');

        // First try to book rooms on same floor
        $bookedRooms = $this->findRoomsOnSameFloor($rooms, $count);

        // If not possible, find best across floors
        if (empty($bookedRooms)) {
            $bookedRooms = $this->findRoomsAcrossFloors($rooms, $count);
        }

        // Update session
        foreach ($bookedRooms as $roomNo) {
            $rooms[$roomNo]['booked'] = true;
        }

        Session::put('rooms', $rooms);

        return redirect('/');
    }

    private function findRoomsOnSameFloor($rooms, $count)
    {
        $floors = [];
        foreach ($rooms as $roomNo => $room) {
            if (!$room['booked']) {
                $floors[$room['floor']][] = $roomNo;
            }
        }

        foreach ($floors as $floorRooms) {
            if (count($floorRooms) >= $count) {
                return array_slice($floorRooms, 0, $count);
            }
        }

        return [];
    }

    private function findRoomsAcrossFloors($rooms, $count)
    {
        $available = array_keys(array_filter($rooms, fn($r) => !$r['booked']));
        sort($available);

        return array_slice($available, 0, $count);
    }

    public function generateRandomOccupancy()
    {
        $rooms = Session::get('rooms');

        $keys = array_keys($rooms);
        shuffle($keys);

        $randomKeys = array_slice($keys, 0, rand(10, 30)); // Random 10-30 rooms booked

        foreach ($randomKeys as $roomNo) {
            $rooms[$roomNo]['booked'] = true;
        }

        Session::put('rooms', $rooms);

        return redirect('/');
    }

    public function resetBookings()
    {
        Session::forget('rooms');
        return redirect('/');
    }
}
