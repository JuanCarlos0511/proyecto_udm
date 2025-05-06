@extends('layouts.app')

@section('title', 'Historial de Pagos')

@section('container-class', '')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/components/history.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/disbursement-history.css') }}">
@endsection

@section('content')
    <div class="disbursement-container">
        <div class="disbursement-header">
            <div class="title-section">
                <h1>Payroll Disbursement</h1>
                <p class="subtitle">Unveiling the Tapestry of Payroll Disbursement History</p>
            </div>
            
            <div class="action-buttons">
                <button class="account-btn">My Virtual Account</button>
                <button class="create-btn"><i class="fas fa-plus"></i> Create Disbursement</button>
            </div>
        </div>
        
        <div class="notification-banner">
            <i class="fas fa-arrow-right"></i>
            <span>Step into the future with our enhanced payroll disbursement experience! You can easily switch back to the old version.</span>
            <a href="#" class="here-link">HERE</a>
            <button class="close-btn"><i class="fas fa-times"></i></button>
        </div>
        
        <div class="summary-cards">
            <div class="summary-card draft">
                <div class="card-icon"><i class="fas fa-file-alt"></i></div>
                <div class="card-content">
                    <h3>Drafts</h3>
                    <p class="amount">$28,334</p>
                </div>
            </div>
            
            <div class="summary-card completed">
                <div class="card-icon"><i class="fas fa-check-circle"></i></div>
                <div class="card-content">
                    <h3>Completed</h3>
                    <p class="amount">$528,000</p>
                </div>
            </div>
            
            <div class="summary-card awaiting">
                <div class="card-icon"><i class="fas fa-clock"></i></div>
                <div class="card-content">
                    <h3>Awaiting</h3>
                    <p class="amount">$28,000</p>
                </div>
            </div>
            
            <div class="summary-card overdue">
                <div class="card-icon"><i class="fas fa-exclamation-circle"></i></div>
                <div class="card-content">
                    <h3>Overdue</h3>
                    <p class="amount">$8,120</p>
                </div>
            </div>
        </div>
        
        <div class="filter-tabs">
            <div class="tab-buttons">
                <button class="tab-btn active" data-filter="all">All</button>
                <button class="tab-btn" data-filter="completed">Completed</button>
                <button class="tab-btn" data-filter="awaiting">Awaiting</button>
                <button class="tab-btn" data-filter="overdue">Overdue</button>
            </div>
            
            <div class="filter-controls">
                <div class="period-filter">
                    <span>All period</span>
                    <i class="fas fa-calendar"></i>
                </div>
                
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
            </div>
        </div>
        
        <div class="disbursement-table">
            <table>
                <thead>
                    <tr>
                        <th>Disbursement ID <i class="fas fa-sort"></i></th>
                        <th>Payroll period <i class="fas fa-sort"></i></th>
                        <th>Type disbursement <i class="fas fa-sort"></i></th>
                        <th>Transfer schedule <i class="fas fa-sort"></i></th>
                        <th>Status <i class="fas fa-sort"></i></th>
                        <th>Total disbursement <i class="fas fa-sort"></i></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="disbursementHistory">
                    <!-- This will be populated dynamically by JavaScript -->
                </tbody>
            </table>
        </div>
        
        <div class="pagination">
            <span>Page 1 of 2</span>
            <div class="pagination-controls">
                <button class="prev-btn"><i class="fas fa-chevron-left"></i> Previous</button>
                <button class="next-btn">Next <i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Add Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    
    <!-- Add CSRF token meta tag for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="{{ asset('js/disbursementController.js') }}"></script>
@endsection
