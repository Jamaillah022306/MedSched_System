<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Slip — MedSched</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f7ff;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .slip-wrapper { width: 100%; max-width: 600px; }

        .print-bar {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 16px;
        }

        .btn-print {
            background: #1565C0; color: white; border: none;
            padding: 9px 22px; border-radius: 8px; font-size: 14px;
            font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px;
        }
        .btn-print:hover { background: #0d47a1; }

        .btn-back {
            background: white; color: #1a3a5c; border: 2px solid #1a3a5c;
            padding: 9px 22px; border-radius: 8px; font-size: 14px;
            font-weight: 700; cursor: pointer; display: flex; align-items: center;
            gap: 6px; text-decoration: none;
        }
        .btn-back:hover { background: #1a3a5c; color: white; }

        .slip-card {
            background: white; border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.12); overflow: hidden;
        }

        /* ── Header ── */
        .slip-header {
            background: linear-gradient(135deg, #2196F3, #1565C0);
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            color: white;
            padding: 28px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .slip-brand { display: flex; align-items: center; gap: 10px; }

        .slip-brand-text {
            font-size: 24px; font-weight: 800;
            letter-spacing: -0.5px; color: white;
        }
        .slip-brand-text span { color: #87ceeb; }

        .slip-title-right { text-align: right; }
        .slip-title-right .slip-title {
            font-size: 18px; font-weight: 800;
            letter-spacing: 1px; text-transform: uppercase; color: white;
        }
        .slip-title-right .slip-id {
            font-size: 12px; color: rgba(255,255,255,0.8); margin-top: 4px;
        }

        /* ── Status banner ── */
        .status-banner {
            padding: 12px 32px; font-size: 13px; font-weight: 700;
            display: flex; align-items: center; gap: 8px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .status-confirmed { background: #e8f5e9; color: #2e7d32; }
        .status-done      { background: #e3f2fd; color: #1565C0; }

        /* ── Body ── */
        .slip-body { padding: 28px 32px; }

        .slip-section-title {
            font-size: 11px; font-weight: 800; color: #5a7a9c;
            text-transform: uppercase; letter-spacing: 1px;
            margin-bottom: 14px; padding-bottom: 6px;
            border-bottom: 2px solid #e8f4ff;
        }

        .slip-row { display: flex; margin-bottom: 12px; gap: 12px; }
        .slip-label { font-size: 12px; color: #000; font-weight: 600; min-width: 150px; }
        .slip-value { font-size: 14px; font-weight: 700; color: #1a3a5c; }

        .section-gap { margin-top: 24px; margin-bottom: 14px; }

        .confirmed-box {
            background: #f0f7ff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            border-left: 4px solid #2196F3;
            border-radius: 6px; padding: 12px 16px;
            margin-top: 20px; font-size: 13px; color: #1a3a5c;
        }
        .confirmed-box strong { color: #2196F3; }

        .slip-footer {
            background: #f5faff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            padding: 16px 32px; border-top: 1px solid #e8f4ff;
            text-align: center; font-size: 12px; color: #5a7a9c; line-height: 1.6;
        }

        .dashed-divider {
            border: none; border-top: 2px dashed #dbe8f5; margin: 20px 0;
        }

        /* ── Print styles ── */
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            body { background: white !important; padding: 0 !important; }

            .print-bar { display: none !important; }

            .slip-card { box-shadow: none !important; border-radius: 0 !important; }

            .slip-header {
                background: linear-gradient(135deg, #2196F3, #1565C0) !important;
                color: white !important;
            }

            .slip-brand-text,
            .slip-title-right .slip-title { color: white !important; }

            .slip-title-right .slip-id { color: rgba(255,255,255,0.8) !important; }

            .status-confirmed { background: #e8f5e9 !important; color: #2e7d32 !important; }
            .status-done      { background: #e3f2fd !important; color: #1565C0 !important; }
            .confirmed-box    { background: #f0f7ff !important; }
            .slip-footer      { background: #f5faff !important; }
        }
    </style>
</head>
<body>

<div class="slip-wrapper">

    {{-- Print / Back buttons --}}
    <div class="print-bar">
        <a href="{{ route('patient.appointments') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <button class="btn-print" onclick="window.print()">
            <i class="bi bi-printer-fill"></i> Print Slip
        </button>
    </div>

    <div class="slip-card">

        {{-- Header --}}
        <div class="slip-header">
            <div class="slip-brand">
                <img src="{{ asset('image/image_2026-03-19_111753926-removebg-preview.png') }}"
                    style="height: 42px; width: 42px; object-fit: contain;">
                <div class="slip-brand-text"><span>Med</span>Sched</div>
            </div>
            <div class="slip-title-right">
                <div class="slip-title">Appointment Slip</div>
                <div class="slip-id">Ref # APT-{{ str_pad($appointment->id, 5, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        {{-- Status Banner --}}
        @php $status = strtolower($appointment->status); @endphp
        @if ($status === 'confirmed')
            <div class="status-banner status-confirmed">
                <i class="bi bi-check-circle-fill"></i> CONFIRMED APPOINTMENT
            </div>
        @elseif ($status === 'done')
            <div class="status-banner status-done">
                <i class="bi bi-patch-check-fill"></i> COMPLETED VISIT
            </div>
        @endif

        {{-- Body --}}
        <div class="slip-body">

            {{-- Patient Information --}}
            <div class="slip-section-title">Patient Information</div>
            <div class="slip-row">
                <div class="slip-label">Full Name</div>
                <div class="slip-value">{{ $appointment->patient->user->fullname ?? '—' }}</div>
            </div>
            <div class="slip-row">
                <div class="slip-label">Contact</div>
                <div class="slip-value">{{ $appointment->patient->phone ?? '—' }}</div>
            </div>
            <div class="slip-row">
                <div class="slip-label">Date of Birth</div>
                <div class="slip-value">
                    {{ $appointment->patient->date_of_birth
                        ? \Carbon\Carbon::parse($appointment->patient->date_of_birth)->format('M d, Y')
                        : '—' }}
                </div>
            </div>
            <div class="slip-row">
                <div class="slip-label">Gender</div>
                <div class="slip-value">{{ ucfirst($appointment->patient->gender ?? '—') }}</div>
            </div>

            <hr class="dashed-divider">

            {{-- Appointment Information --}}
            <div class="slip-section-title section-gap">Appointment Information</div>
            <div class="slip-row">
                <div class="slip-label">Doctor</div>
                <div class="slip-value">Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</div>
            </div>
            <div class="slip-row">
                <div class="slip-label">Specialization</div>
                <div class="slip-value">{{ $appointment->doctor->specialization }}</div>
            </div>
            <div class="slip-row">
                <div class="slip-label">Date</div>
                <div class="slip-value">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</div>
            </div>
            <div class="slip-row">
                <div class="slip-label">Time</div>
                <div class="slip-value">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</div>
            </div>
            <div class="slip-row">
                <div class="slip-label">Type</div>
                <div class="slip-value">{{ $appointment->type }}</div>
            </div>
            <div class="slip-row">
                <div class="slip-label">Reason for Visit</div>
                <div class="slip-value">{{ $appointment->reason ?? '—' }}</div>
            </div>

            {{-- Confirmed by --}}
            @if ($appointment->actedBy)
            <div class="confirmed-box">
                <i class="bi bi-person-check-fill"></i>
                @if ($status === 'confirmed')
                    Confirmed by: <strong>{{ $appointment->actedBy->fullname }}</strong>
                @elseif ($status === 'done')
                    Marked done by: <strong>{{ $appointment->actedBy->fullname }}</strong>
                @endif
                <span style="color:#5a7a9c; font-weight:400;">({{ ucfirst($appointment->actedBy->role) }})</span>
                · {{ \Carbon\Carbon::parse($appointment->updated_at)->format('M d, Y h:i A') }}
            </div>
            @endif

        </div>

        {{-- Footer --}}
        <div class="slip-footer">
            <strong>MedSched Medical Scheduling System</strong><br>
            Please present this slip upon arrival. This serves as your official appointment confirmation.<br>
            Generated on {{ now()->format('F d, Y \a\t h:i A') }}
        </div>

    </div>
</div>

</body>
</html>