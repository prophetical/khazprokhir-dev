{{-- Partial: thead untuk setiap tabel kolom khazai --}}
<thead>
    @if(isset($title))
    {{-- Baris 0: Judul Group Pack --}}
    <tr style="background-color:#1e293b;">
        <th colspan="7" 
            style="position:sticky; top:64px; z-index:40; background-color:#1e293b; padding:5px 4px; text-align:center; font-size:8.5px; font-weight:900; text-transform:uppercase; letter-spacing:0.1em; color:#ffffff; border-bottom:1px solid #334155; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
            {{ $title }}
        </th>
    </tr>
    @endif
    
    {{-- Row 1: Group Headers --}}
    <tr style="background-color:#f59e0b; color:#ffffff; font-size:7.5px; font-weight:900; text-transform:uppercase; letter-spacing:0.1em; text-align:center;">
        <th rowspan="2" style="position:sticky; top:{{ isset($title) ? '84px' : '64px' }}; z-index:30; padding:4px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; width:32px; background-color:#f59e0b;">
            No<br>Pack
        </th>
        <th colspan="5" style="position:sticky; top:{{ isset($title) ? '84px' : '64px' }}; z-index:29; padding:4px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; background-color:#f59e0b;">
            Inschiet Vell (Lembar)
        </th>
        <th rowspan="2" style="position:sticky; top:{{ isset($title) ? '84px' : '64px' }}; z-index:30; padding:4px 4px; border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:75px; background-color:#f59e0b;">
            Seri<br>Pengganti
        </th>
    </tr>
    
    {{-- Row 2: Sub Headers --}}
    <tr style="background-color:#f59e0b; color:#ffffff; font-size:7px; font-weight:900; text-transform:uppercase; letter-spacing:0.05em; text-align:center;">
        <th style="position:sticky; top:{{ isset($title) ? '103px' : '83px' }}; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; width:24px; background-color:#f59e0b;">
            Slot
        </th>
        <th style="position:sticky; top:{{ isset($title) ? '103px' : '83px' }}; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:45px; background-color:#f59e0b;">
            Rusak
        </th>
        <th style="position:sticky; top:{{ isset($title) ? '103px' : '83px' }}; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:35px; background-color:#f59e0b;">
            Jumlah
        </th>
        <th style="position:sticky; top:{{ isset($title) ? '103px' : '83px' }}; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:45px; background-color:#f59e0b;">
            Pack<br>PENGGANTI
        </th>
        <th style="position:sticky; top:{{ isset($title) ? '103px' : '83px' }}; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:45px; background-color:#f59e0b;">
            Vell<br>PENGGANTI
        </th>
    </tr>
</thead>