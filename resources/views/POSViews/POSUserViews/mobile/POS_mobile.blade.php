@extends('ManagementSystemViews.UserViews.Layouts.app')
@include('ManagementSystemViews.UserViews.Layouts.header_mobile')
@include('ManagementSystemViews.UserViews.Layouts.footer')

@section('title', 'POS system')

@section('content')
<div class="phone">

    <!-- CARD: TOTAL ORDER -->
    <div class="card teal">
        <div class="card-icon">📄</div>
        <div>
            <p>Total Order</p>
            <h2>$48,988,078</h2>
        </div>
        <span class="badge up">+22%</span>
    </div>

    <!-- CARD: TOTAL RETURN -->
    <div class="card blue">
        <div class="card-icon">🔁</div>
        <div>
            <p>Total Order Return</p>
            <h2>$16,478,145</h2>
        </div>
        <span class="badge down">-22%</span>
    </div>

    <!-- DATE FILTER -->
    <input type="date" class="date-input">

    <!-- CHART (STATIC UI) -->
    <div class="chart">
        <div class="bar sat"></div>
        <div class="bar sun"></div>
        <div class="bar mon"></div>
        <div class="bar tue"></div>
        <div class="bar wed"></div>
        <div class="bar thu"></div>
        <div class="bar fri"></div>
    </div>

    <div class="chart-labels">
        <span>Sat</span>
        <span>Sun</span>
        <span>Mon</span>
        <span>Tue</span>
        <span>Wed</span>
        <span>Thu</span>
        <span>Fri</span>
    </div>

    <!-- BUDGET -->
    <div class="budget">
        <div>
            <p>Your Monthly Budget</p>
            <strong>USD 4,658.0 for 2 days</strong>
        </div>
        <div class="progress-circle">77%</div>
    </div>
</div>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: sans-serif;
}

.phone {
    width: 100%;
    min-height: 100vh;
    background: #fff;
    padding: 20px;
}

/* Cards */
.card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #fff;
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 12px;
}

.card-icon {
    background: #ffffff30;
    padding: 10px;
    border-radius: 10px;
    font-size: 18px;
    margin-right: 10px;
}

.card h2 {
    font-size: 18px;
}

.card p {
    font-size: 12px;
}

/* Colors */
.teal { background: #14b8a6; }
.blue { background: #06b6d4; }

/* Badge */
.badge {
    font-size: 12px;
    background: #fff;
    padding: 4px 8px;
    border-radius: 10px;
    font-weight: bold;
}
.up { color: #16a34a; }
.down { color: #dc2626; }

/* Date */
.date-input {
    width: 100%;
    border: 2px solid #14b8a6;
    border-radius: 10px;
    padding: 10px;
    margin: 16px 0;
}

/* Chart */
.chart {
    display: flex;
    justify-content: space-between;
    height: 140px;
    align-items: flex-end;
}

.bar {
    width: 12%;
    background: #5eead4;
    border-radius: 6px;
}

.sat { height: 80%; }
.sun { height: 55%; }
.mon { height: 35%; }
.tue { height: 25%; }
.wed { height: 65%; }
.thu { height: 85%; }
.fri { height: 55%; }

.chart-labels {
    display: flex;
    justify-content: space-between;
    font-size: 11px;
    color: #666;
    margin: 8px 0 20px;
}

/* Budget */
.budget {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.progress-circle {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    border: 4px solid #14b8a6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #14b8a6;
    font-size: 12px;
    font-weight: bold;
}
.sidebar,
.sidebar-wrap {
    display: none;
}
</style>
@endsection