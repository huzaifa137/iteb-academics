@if (empty($groups))
    <span class="empty"></span>
@else
    @foreach ($groups as $group)
        @if ($group['time'])
            <div class="time">{{ $group['time'][0] }}<br>{{ $group['time'][1] }}</div>
        @endif
        <div class="group">
            @foreach ($group['lines'] as $line)
                <div>{{ $line }}</div>
            @endforeach
        </div>
    @endforeach
@endif
