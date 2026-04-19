{{-- Partial: thead Cutpack — FLAT (tanpa slot) --}}
<thead>
    @if(isset($title))
    <tr style="background-color:#1e293b !important;">
        <th colspan="7"
            style="position:sticky; top:64px; z-index:40; background-color:#1e293b !important;
                padding:5px 4px; text-align:center; font-size:8.5px; font-weight:900;
                text-transform:uppercase; letter-spacing:0.1em; color:#ffffff;
                border-bottom:1px solid #334155; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
            {{ $title }}
        </th>
    </tr>
    @endif

    {{-- Row 1: Group Headers --}}
    <tr style="color:#ffffff; font-size:7.5px; font-weight:900; text-align:center;
        text-transform:uppercase; letter-spacing:0.05em;">
        <th rowspan="2"
            style="position:sticky; top:83px; z-index:30; padding:4px 2px;
            border:1px solid rgba(255,255,255,0.2); border-top:none; width:32px;
            background-color:#f59e0b !important;">
            #
        </th>
        <th colspan="3"
            style="position:sticky; top:83px; z-index:29; padding:4px 2px;
            border:1px solid rgba(255,255,255,0.2); border-top:none;
            background-color:#f59e0b !important;">
            Rusak Bilyet <span style="font-size:6px; opacity:0.8;">(Auto)</span>
        </th>
        <th colspan="3"
            style="position:sticky; top:83px; z-index:29; padding:4px 2px;
            border:1px solid rgba(255,255,255,0.2); border-top:none;
            background-color:#4f46e5 !important;">
            Keterangan
        </th>
    </tr>

    {{-- Row 2: Sub Headers --}}
    <tr style="font-size:7px; font-weight:900; text-align:center; text-transform:uppercase;
        letter-spacing:0.05em; color:#ffffff;">
        <th style="position:sticky; top:101px; z-index:29; padding:3px 2px;
            border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:38px;
            background-color:#f59e0b !important;">Seri 1</th>
        <th style="position:sticky; top:101px; z-index:29; padding:3px 2px;
            border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:38px;
            background-color:#f59e0b !important;">Seri 2</th>
        <th style="position:sticky; top:101px; z-index:29; padding:3px 2px;
            border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:42px;
            background-color:#f59e0b !important;">Camp</th>
        {{-- Keterangan sub --}}
        <th style="position:sticky; top:101px; z-index:29; padding:3px 2px;
            border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:45px;
            background-color:#3730a3 !important;">Pack</th>
        <th style="position:sticky; top:101px; z-index:29; padding:3px 4px;
            border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:75px;
            background-color:#1e1b4b !important; color:#a5b4fc;">Seri</th>
        <th style="position:sticky; top:101px; z-index:29; padding:3px 2px;
            border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:50px;
            background-color:#3730a3 !important;">Bilyet</th>
    </tr>
</thead>