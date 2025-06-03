@props(['colors'])

<div class="space-y-2">
    @foreach ($colors as $color => $variants)
        <div class="flex flex-wrap items-baseline px-2 py-1 gap-1" style="background-color: @getColorHex($color); color: @getContrastColor($color);">
            <span style="margin-right: 5px">{{ $color }}</span>
            <!-- Badge Warna -->
            {{-- <span class="px-2 py-1 text-xs font-medium rounded capitalize"></span> --}}
            
            <!-- Daftar Ukuran -->
            <div class="flex flex-wrap gap-1 text-xs text-gray-600">
                @php
                    $sizeCounts = $variants->groupBy('size')
                        ->map(fn ($items) => $items->count())
                        ->sortKeys();
                @endphp
                
                @foreach ($sizeCounts as $size => $count)
                    <span class="text-white px-1 py-1 font-semibold">
                        {{ $size }} ({{ $count }})
                    </span>
                @endforeach
            </div>
        </div>
    @endforeach
</div>