@extends('Layout.Management.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/views/Management/NumberSeries/NumberSariesCreate.css') }}">
@endpush

@section('title', 'Edit Number Series')

@php
    $locked = $series->last_no !== null;
@endphp

@section('content')
    <div class="tax-group-page">

        <h1>Edit Number Series</h1>

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

        <section class="card form-card">
            <div class="section-head">
                <h2>Series Details</h2>
                <p>Last issued number: <strong>{{ $series->last_no ?? '— none yet —' }}</strong></p>
            </div>

            @if ($locked)
                <div class="banner">
                    <i class="bi bi-lock-fill"></i>
                    This series has already issued numbers, so Code, Prefix, Digit Count, and Starting No. are locked to
                    keep every issued number consistent.
                </div>
            @endif

            <form action="{{ route('number-series.update', $series->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="field">
                    <label for="code">Page <span class="required">*</span></label>
                    <select name="code" id="code" class="select-control" {{ $locked ? 'disabled' : 'required' }}>
                        @foreach ($availablePurposes as $value => $label)
                            <option value="{{ $value }}"
                                {{ old('code', $series->code) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @if ($locked)
                        <input type="hidden" name="code" value="{{ $series->code }}">
                    @endif
                </div>

                <div class="field">
                    <label for="name">Name <span class="required">*</span></label>
                    <input type="text" name="name" id="name" class="text-control"
                        value="{{ old('name', $series->name) }}" required>
                </div>

                <div class="field">
                    <label for="prefix">Prefix <span class="required">*</span></label>
                    <input type="text" name="prefix" id="prefix" class="text-control"
                        value="{{ old('prefix', $series->prefix) }}" maxlength="10"
                        {{ $locked ? 'disabled' : 'required' }}>
                    @if ($locked)
                        <input type="hidden" name="prefix" value="{{ $series->prefix }}">
                    @endif
                </div>

                <div class="field">
                    <label for="padding">Minimum Digits <span class="required">*</span></label>
                    <select name="padding" id="padding" class="select-control" {{ $locked ? 'disabled' : 'required' }}>
                        @foreach ([3, 4, 5] as $digits)
                            <option value="{{ $digits }}"
                                {{ (int) old('padding', $series->padding) === $digits ? 'selected' : '' }}>
                                {{ $digits }} digits (e.g. {{ str_pad('1', $digits, '0', STR_PAD_LEFT) }})</option>
                        @endforeach
                    </select>
                    @if ($locked)
                        <input type="hidden" name="padding" value="{{ $series->padding }}">
                    @endif
                </div>

                <div class="field">
                    <label for="start_no">Starting No. <span class="required">*</span></label>
                    <input type="number" name="start_no" id="start_no" class="text-control"
                        value="{{ old('start_no', $series->start_no) }}" min="1"
                        {{ $locked ? 'disabled' : 'required' }}>
                    @if ($locked)
                        <input type="hidden" name="start_no" value="{{ $series->start_no }}">
                    @endif
                </div>

                <div class="field">
                    <label for="end_no">Ending No. <span class="required">*</span></label>
                    <input type="number" name="end_no" id="end_no" class="text-control"
                        value="{{ old('end_no', $series->end_no) }}" min="1" required>
                    <p class="hint">Current range: {{ $series->start_formatted }} &rarr; {{ $series->end_formatted }}
                    </p>
                </div>

                <div class="field">
                    <label>
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', $series->is_active) ? 'checked' : '' }} style="width:auto;">
                        Active
                    </label>
                </div>

                <div class="form-footer">
                    <a href="{{ route('number-series.index') }}" class="btn">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </section>

    </div>
@endsection
