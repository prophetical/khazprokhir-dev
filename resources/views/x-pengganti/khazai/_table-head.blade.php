{{-- Partial: thead untuk setiap tabel kolom khazai --}}
<thead>
    @if(isset($title))
    {{-- Baris 0: Judul Group Pack --}}
    <tr style="background-color:#1e293b !important;">
        <th colspan="7" 
            style="position:sticky; top:64px; z-index:40; background-color:#1e293b !important; padding:5px 4px; text-align:center; font-size:8.5px; font-weight:900; text-transform:uppercase; letter-spacing:0.1em; color:#ffffff; border-bottom:1px solid #334155; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
            {{ $title }}
        </th>
    </tr>
    @endif
    
    {{-- Row 1: Group Headers --}}
    <tr style="background-color:#f59e0b !important; color:#ffffff; font-size:7.5px; font-weight:900; text-transform:uppercase; letter-spacing:0.1em; text-align:center;">
        <th rowspan="2" style="position:sticky; top:83px; z-index:30; padding:4px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; width:32px; background-color:#f59e0b !important;">
            No<br>Pack
        </th>
        <th colspan="5" style="position:sticky; top:83px; z-index:29; padding:4px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; background-color:#f59e0b !important;">
            Inschiet Vell (Lembar)
        </th>
        <th rowspan="2" style="position:sticky; top:83px; z-index:30; padding:4px 4px; border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:75px; background-color:#f59e0b !important;">
            Seri<br>Pengganti
        </th>
    </tr>
    
    {{-- Row 2: Sub Headers --}}
    <tr style="background-color:#f59e0b !important; color:#ffffff; font-size:7px; font-weight:900; text-transform:uppercase; letter-spacing:0.05em; text-align:center;">
        <th style="position:sticky; top:101px; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; width:24px; background-color:#f59e0b !important;">
            Slot
        </th>
        <th style="position:sticky; top:101px; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:45px; background-color:#f59e0b !important;">
            Rusak
        </th>
        <th style="position:sticky; top:101px; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:35px; background-color:#f59e0b !important;">
            Jumlah
        </th>
        <th style="position:sticky; top:101px; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:45px; background-color:#f59e0b !important;">
            Pack<br>PENGGANTI
        </th>
        <th style="position:sticky; top:101px; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:45px; background-color:#f59e0b !important;">
            Vell<br>PENGGANTI
        </th>
    </tr>
</thead>