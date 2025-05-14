<!DOCTYPE html>
<html>
<head>
    <title>Attendance</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome for the fingerprint icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            color: white;
            margin: 5px;
        }
        
        .btn-success {
            background-color: #28a745;
        }
        
        .btn-danger {
            background-color: #dc3545;
        }
        
        .btn-primary {
            background-color: #007bff;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .qrcode-container {
            margin-top: 20px;
            text-align: center;
        }
        
        .qrcode-container img {
            max-width: 200px;
        }
    </style>
</head>
<body>

<h2>Attendance</h2>

<input type="hidden" id="employee_id" value="{{ $employee_id }}">
<button id="attendance-button" class="btn" onclick="toggleAttendance()">Loading...</button>

<div id="qrcode-container" class="qrcode-container" style="{{ $isChecked ? 'display: none;' : 'display: block;' }}">
    <h3>Scan to Check In</h3>
    <div id="qrcode">
        @if($qrCode)
            <img src="{{ $qrCode }}" alt="QR Code" style="max-width: 200px;">
        @endif
    </div>
    <button class="btn btn-primary" onclick="window.print()">Print QR Code</button>
</div>

<script>
    let isCheckedIn = {{ $isChecked ? 'true' : 'false' }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function updateButton() {
        const button = document.getElementById('attendance-button');
        
        if (isCheckedIn) {
            button.innerHTML = '<i class="fas fa-fingerprint"></i> Check Out';
            button.className = 'btn btn-danger';
        } else {
            button.innerHTML = '<i class="fas fa-fingerprint"></i> Check In';
            button.className = 'btn btn-success';
        }
    }

    function toggleAttendance() {
        const employeeId = document.getElementById('employee_id').value;

        fetch("{{ route('attendances.toggle.submit') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
                employee_id: employeeId,
                is_checked: !isCheckedIn
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                isCheckedIn = !isCheckedIn;
                updateButton();
                // Hide QR code if checked in
                if (isCheckedIn) {
                    document.getElementById('qrcode-container').style.display = 'none';
                } else {
                    document.getElementById('qrcode-container').style.display = 'block';
                }
            } else {
                alert(data.error);
            }
        })
        .catch(error => console.error("Error:", error));
    }

    // Set initial button label
    updateButton();
</script>

</body>
</html>