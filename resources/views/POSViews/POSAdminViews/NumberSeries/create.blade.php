@extends('Layout.Management.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/NumberSeries/NumberSariesCreate.css') }}">
@endpush

@section('title', 'Add Number Series')

@section('content')
    <div class="tax-group-page">

        <h1>Add Number Series</h1>

        @if ($errors->any())
            <div class="error-banner">
                <i class="bi bi-exclamation-circle"></i>
                <div>
                    Please fix the following:
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if (empty($availablePurposes))
            <section class="card empty-card">
                Every page already has a number series set up — nothing left to add.
                <div>
                    <a href="{{ route('number-series.index') }}" class="btn">Back</a>
                </div>
            </section>
        @else
            <section class="card form-card">
                <div class="section-head">
                    <h2>Series Details</h2>
                    <p>Any feature (Orders now, more later) calls this series by its Code to get the next number.</p>
                </div>

                <form action="{{ route('number-series.store') }}" method="POST">
                    @csrf

                    <div class="field">
                        <label for="code">Page <span class="required">*</span></label>
                        <select name="code" id="code" class="select-control" required>
                            <option value="" {{ old('code') ? '' : 'selected' }} disabled hidden>Select a page
                            </option>
                            @foreach ($availablePurposes as $value => $label)
                                <option value="{{ $value }}" {{ old('code') === $value ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                        <p class="hint">Each page can only have one number series — already-configured pages won't show up
                            here. This can't be changed once a number has been issued.</p>
                    </div>

                    <div class="field">
                        <label for="name">Name <span class="required">*</span></label>
                        <input type="text" name="name" id="name" class="text-control"
                            placeholder="e.g. POS Order Number" value="{{ old('name') }}" required>
                    </div>

                    <div class="field">
                        <label for="prefix">Prefix <span class="required">*</span></label>
                        <input type="text" name="prefix" id="prefix" class="text-control" placeholder="e.g. POSLA"
                            value="{{ old('prefix') }}" maxlength="10" required>
                        <p class="hint">Text placed in front of every number this series generates.</p>
                    </div>

                    <div class="field">
                        <label for="padding">Minimum Digits <span class="required">*</span></label>
                        <select name="padding" id="padding" class="select-control" required>
                            <option value="" {{ old('padding') ? '' : 'selected' }} disabled hidden>Select digit
                                count</option>
                            @foreach ([3, 4, 5] as $digits)
                                <option value="{{ $digits }}"
                                    {{ (string) old('padding') === (string) $digits ? 'selected' : '' }}>
                                    {{ $digits }} digits (e.g. {{ str_pad('1', $digits, '0', STR_PAD_LEFT) }})
                                </option>
                            @endforeach
                        </select>
                        <p class="hint">Numbers are zero-padded to at least this many digits, then grow naturally past it
                            (e.g. with 3 digits: 001, 002, ... 999, 1000, 1001...).</p>
                    </div>

                    <div class="field">
                        <label for="start_no">Starting No. <span class="required">*</span></label>
                        <input type="number" name="start_no" id="start_no" class="text-control" placeholder="1"
                            value="{{ old('start_no', 1) }}" min="1" required>
                    </div>

                    <div class="field">
                        <label for="end_no">Ending No. <span class="required">*</span></label>
                        <input type="number" name="end_no" id="end_no" class="text-control" placeholder="1000000"
                            value="{{ old('end_no') }}" min="1" required>
                        <p class="hint">Once numbers reach this value, this series stops issuing new ones until you extend
                            it here.</p>
                    </div>

                    <div class="field">
                        <label>
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', true) ? 'checked' : '' }} style="width:auto;">
                            Active
                        </label>
                        <p class="hint">Only active series can be used to issue new numbers.</p>
                    </div>

                    <div class="banner">
                        <i class="bi bi-info-circle"></i>
                        Prefix, digit count, and Starting No. can no longer be changed once this series has issued its first
                        number.
                    </div>

                    <div class="form-footer">
                        <a href="{{ route('number-series.index') }}" class="btn">Cancel</a>
                        <button type="submit" class="btn btn-primary">Add Number Series</button>
                    </div>
                </form>
            </section>
        @endif

    </div>
@endsection
