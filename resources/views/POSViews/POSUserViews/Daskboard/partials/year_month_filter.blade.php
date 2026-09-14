<form method="GET" action="{{ route('user.index') }}" class="year-filter-form filter-form-inline">
    @foreach (request()->except(['year', 'month', 'page']) as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
    <select name="month" class="year-filter month-filter" onchange="this.form.submit()">
        <option value="all" {{ $selectedMonth === null ? 'selected' : '' }}>All months</option>
        @foreach ($monthOptions as $opt)
            <option value="{{ $opt['value'] }}" {{ $selectedMonth === $opt['value'] ? 'selected' : '' }}>
                {{ $opt['label'] }}
            </option>
        @endforeach
    </select>
    <select name="year" class="year-filter" onchange="this.form.submit()">
        @foreach ($availableYears as $yr)
            <option value="{{ $yr }}" {{ (int) $selectedYear === (int) $yr ? 'selected' : '' }}>
                {{ $yr }}
            </option>
        @endforeach
    </select>
</form>
