{{--
Partial: thead untuk tabel rikyet
FIX #2: Semua warna pakai inline style agar tidak bergantung Tailwind compile.
FIX #4: thead sticky — top disesuaikan dengan tinggi header app (64px navbar).
Variabel $title wajib disertakan.
--}}
<thead>
    {{-- Baris judul kolom --}}
    <tr style="background-color:#0f766e !important;">
        <th colspan="8"
            style="position:sticky; top:64px; z-index:30; background-color:#0f766e !important; padding:5px 4px; text-align:center; font-size:8.5px; font-weight:900; text-transform:uppercase; letter-spacing:0.1em; color:#ffffff; border-bottom:1px solid #0d9488;">
            {{ $title }}
        </th>
    </tr>
    {{-- Row 1: Group Headers --}}
    <tr
        style="background-color:#0d9488 !important; color:#ffffff; font-size:7.5px; font-weight:900; text-align:center; text-transform:uppercase; letter-spacing:0.05em;">
        <th rowspan="2"
            style="position:sticky; top:83px; z-index:30; padding:4px 2px; border:1px solid rgba(255,255,255,0.25); border-top:none; width:22px; white-space:nowrap; color:#ffffff; background-color:#0d9488 !important;">
            No<br>Pack
        </th>
        <th colspan="3" 
            style="position:sticky; top:83px; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.25); border-top:none; color:#ffffff; background-color:#0d9488 !important;">
            Jumlah Rusak (Brood)
        </th>
        <th colspan="3"
            style="position:sticky; top:83px; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.25); border-top:none; background-color:#0f766e !important; color:#ffffff;">
            Total Jumlah
        </th>
        <th rowspan="2"
            style="position:sticky; top:83px; z-index:30; padding:4px 2px; border:1px solid rgba(255,255,255,0.25); border-top:none; min-width:52px; color:#ffffff; font-size:7px; background-color:#0d9488 !important;">
            Seri<br>Pengganti
        </th>
    </tr>
    {{-- Row 2: Sub Headers --}}
    <tr
        style="background-color:#0d9488 !important; color:#ffffff; font-size:7px; font-weight:900; text-align:center; text-transform:uppercase;">
        <th style="position:sticky; top:101px; z-index:29; padding:3px 1px; border:1px solid rgba(255,255,255,0.25); border-top:none; min-width:34px; color:#ffffff; background-color:#0d9488 !important;">
            Seri 1<br><span style="font-size:6px; font-weight:500; opacity:0.85;">(A1–U1)</span>
        </th>
        <th style="position:sticky; top:101px; z-index:29; padding:3px 1px; border:1px solid rgba(255,255,255,0.25); border-top:none; min-width:34px; color:#ffffff; background-color:#0d9488 !important;">
            Seri 2<br><span style="font-size:6px; font-weight:500; opacity:0.85;">(A2–U2)</span>
        </th>
        <th style="position:sticky; top:101px; z-index:29; padding:3px 1px; border:1px solid rgba(255,255,255,0.25); border-top:none; min-width:34px; color:#ffffff; background-color:#0d9488 !important;">
            Campuran<br><span style="font-size:6px; font-weight:500; opacity:0.85;">(V1,W1..)</span>
        </th>
        <th
            style="position:sticky; top:101px; z-index:29; padding:3px 1px; border:1px solid rgba(255,255,255,0.25); border-top:none; min-width:26px; background-color:#0f766e !important; color:#ffffff;">
            S1</th>
        <th
            style="position:sticky; top:101px; z-index:29; padding:3px 1px; border:1px solid rgba(255,255,255,0.25); border-top:none; min-width:26px; background-color:#0f766e !important; color:#ffffff;">
            S2</th>
        <th
            style="position:sticky; top:101px; z-index:29; padding:3px 1px; border:1px solid rgba(255,255,255,0.25); border-top:none; min-width:26px; background-color:#0f766e !important; color:#ffffff;">
            Campuran</th>
    </tr>
</thead>