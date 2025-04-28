<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hotel Room Reservation</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f2f5f7;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .actions {
            text-align: center;
            margin-bottom: 30px;
        }
        .actions form {
            display: inline-block;
            margin: 0 10px;
        }
        .actions button {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            margin-top: 10px;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .actions button:hover {
            background-color: #0056b3;
        }
        .floor {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 30px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }
        .floor h2 {
            margin-top: 0;
            color: #444;
        }
        .rooms {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        .room {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
            border-radius: 8px;
            border: 2px solid #ccc;
            transition: transform 0.2s, background-color 0.2s;
        }
        .room.available {
            background-color: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        .room.booked {
            background-color: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }
        .room:hover {
            transform: scale(1.1);
        }
        .message {
            text-align: center;
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <h1>🏨 Hotel Room Reservation System</h1>

    @if(session('error'))
        <div class="message">
            {{ session('error') }}
        </div>
    @endif

    <div class="actions">
        <form action="/book" method="POST">
            @csrf
            <label for="count">Number of Rooms (1-5): </label>
            <input type="number" name="count" id="count" min="1" max="5" required style="padding: 5px; border-radius: 5px;">
            <button type="submit">Book Rooms</button>
        </form>

        <form action="/random" method="POST">
            @csrf
            <button type="submit">🎲 Random Occupancy</button>
        </form>

        <form action="/reset" method="POST">
            @csrf
            <button type="submit">🔄 Reset Booking</button>
        </form>
    </div>

    @foreach(range(10,1) as $floor)
        <div class="floor">
            <h2>Floor {{ $floor }}</h2>
            <div class="rooms">
                @foreach($rooms as $roomNo => $room)
                    @if($room['floor'] == $floor)
                        <div class="room {{ $room['booked'] ? 'booked' : 'available' }}">
                            {{ $roomNo }}
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach

</body>
</html>
