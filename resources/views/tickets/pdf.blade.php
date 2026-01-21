<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ticket</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .ticket-box { border: 1px solid #ddd; padding: 20px; border-radius: 5px; }
        .header { text-align: center; border-bottom: 2px solid #333; margin-bottom: 20px; padding-bottom: 10px; }
        .info { margin-bottom: 10px; }
        .code { font-size: 20px; font-weight: bold; text-align: center; margin-top: 20px; border: 1px dashed #333; padding: 10px; }
    </style>
</head>
<body>
    <div class="ticket-box">
        <div class="header">
            <h1>{{ $ticket->reservation->event->name }}</h1>
            <p>{{ $ticket->seat->venue->name }}</p>
        </div>

        <div class="info">
            <strong>Date:</strong> {{ $ticket->reservation->event->start_date }}
        </div>
        <div class="info">
            <strong>Holder:</strong> {{ $ticket->reservation->user->name }}
        </div>
        <div class="info">
            <strong>Seat:</strong> 
            @if($ticket->seat->section) Section: {{ $ticket->seat->section }} @endif
            @if($ticket->seat->row) Row: {{ $ticket->seat->row }} @endif
            Number: {{ $ticket->seat->number }}
        </div>
        
        <div class="code">
            CODE: {{ $ticket->ticket_code }}
        </div>
    </div>
</body>
</html>
