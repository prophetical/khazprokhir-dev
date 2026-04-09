{{-- Partial: thead untuk Cutpack Input --}}
<thead>
    @if(isset($title))
    {{-- Baris 0: Judul Group Pack --}}
    <tr style="background-color:#1e293b;">
        <th colspan="11" 
            style="position:sticky; top:64px; z-index:40; background-color:#1e293b; padding:5px 4px; text-align:center; font-size:8.5px; font-weight:900; text-transform:uppercase; letter-spacing:0.1em; color:#ffffff; border-bottom:1px solid #334155; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
            {{ $title }}
        </th>
    </tr>
    @endif
    
    {{-- Row 1: Group Headers --}}
    <tr style="color:#ffffff; font-size:7.5px; font-weight:900; text-align:center; text-transform:uppercase; letter-spacing:0.05em;">
        <th rowspan="2"
            style="position:sticky; top:{{ isset($title) ? '84px' : '64px' }}; z-index:30; padding:4px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; width:32px; background-color:#f59e0b;">
            #
        </th>
        <th rowspan="2"
            style="position:sticky; top:{{ isset($title) ? '84px' : '64px' }}; z-index:30; padding:4px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; width:24px; background-color:#f59e0b;">
            Slot
        </th>

        {{-- Row 1 Groups --}}
        <th colspan="3"
            style="position:sticky; top:{{ isset($title) ? '84px' : '64px' }}; z-index:29; padding:4px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; background-color:#f59e0b;">
            Jumlah Rusak Bilyet
        </th>
        <th colspan="3"
            style="position:sticky; top:{{ isset($title) ? '84px' : '64px' }}; z-index:29; padding:4px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; background-color:#334155;">
            Total Per Pack
        </th>
        <th colspan="3"
            style="position:sticky; top:{{ isset($title) ? '84px' : '64px' }}; z-index:29; padding:4px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; background-color:#4f46e5;">
            Keterangan
        </th>
    </tr>

    {{-- Row 2: Detailed Headers --}}
    <tr style="font-size:7px; font-weight:900; text-align:center; text-transform:uppercase; letter-spacing:0.05em; color:#ffffff;">
        {{-- Inschiet Sub --}}
        <th style="position:sticky; top:{{ isset($title) ? '103px' : '83px' }}; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:38px; background-color:#f59e0b;">
            Seri 1
        </th>
        <th style="position:sticky; top:{{ isset($title) ? '103px' : '83px' }}; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:38px; background-color:#f59e0b;">
            Seri 2
        </th>
        <th style="position:sticky; top:{{ isset($title) ? '103px' : '83px' }}; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:42px; background-color:#f59e0b;">
            Camp
        </th>

        {{-- Total Sub --}}
        <th style="position:sticky; top:{{ isset($title) ? '103px' : '83px' }}; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:40px; background-color:#1e293b; color:#fbbf24;">
            Seri 1
        </th>
        <th style="position:sticky; top:{{ isset($title) ? '103px' : '83px' }}; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:40px; background-color:#1e293b; color:#fbbf24;">
            Seri 2
        </th>
        <th style="position:sticky; top:{{ isset($title) ? '103px' : '83px' }}; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:40px; background-color:#1e293b; color:#a5b4fc;">
            Camp
        </th>

        {{-- Pengganti Sub --}}
        <th style="position:sticky; top:{{ isset($title) ? '103px' : '83px' }}; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:40px; background-color:#3730a3;">
            Pack
        </th>
        <th style="position:sticky; top:{{ isset($title) ? '103px' : '83px' }}; z-index:29; padding:3px 4px; border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:75px; background-color:#1e1b4b; color:#a5b4fc;">
            Seri
        </th>
        <th style="position:sticky; top:{{ isset($title) ? '103px' : '83px' }}; z-index:29; padding:3px 2px; border:1px solid rgba(255,255,255,0.2); border-top:none; min-width:45px; background-color:#3730a3;">
            Bilyet
        </th>
    </tr>
</thead>