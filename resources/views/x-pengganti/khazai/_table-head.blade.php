{{-- Partial: thead Khazai — FLAT (tanpa slot) --}}
<thead>
    @if(isset($title))
    {{-- Baris judul group pack --}}
    <tr style="background-color:#1e293b !important;">
        <th colspan="2"
            style="position:sticky; top:64px; z-index:40; background-color:#1e293b !important;
                padding:5px 4px; text-align:center; font-size:8.5px; font-weight:900;
                text-transform:uppercase; letter-spacing:0.1em; color:#ffffff;
                border-bottom:1px solid #334155; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
            {{ $title }}
        </th>
    </tr>
    @endif

    {{-- Single header row --}}
    <tr style="background-color:#f59e0b !important; color:#ffffff; font-size:7.5px; font-weight:900;
        text-transform:uppercase; letter-spacing:0.1em; text-align:center;">
        <th style="position:sticky; top:83px; z-index:30; padding:5px 2px;
            border:1px solid rgba(255,255,255,0.2); border-top:none; width:32px;
            background-color:#f59e0b !important;">
            No<br>Pack
        </th>
        <th style="position:sticky; top:83px; z-index:29; padding:5px 4px;
            border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:55px;
            background-color:#f59e0b !important;">
            Rusak<br><span style="font-size:6.5px; font-weight:700; opacity:0.85;">(Vell — Auto)</span>
        </th>
        <th style="position:sticky; top:83px; z-index:29; padding:5px 4px;
            border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:75px;
            background-color:#7c3aed !important; display:none;">
            Seri<br>Pengganti
        </th>
    </tr>
</thead>