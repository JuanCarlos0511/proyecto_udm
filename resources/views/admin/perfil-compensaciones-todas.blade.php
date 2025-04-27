@extends('layouts.admin')

@section('title', 'Todas las Compensaciones')

@section('page-title', 'Todas las Compensaciones')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span><a href="{{ url('admin/perfil') }}">Perfil</a></span>
    <span class="breadcrumb-separator">/</span>
    <span>Todas las Compensaciones</span>
@endsection

@section('styles')
<style>
    .compensations-container {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .compensations-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eaeaea;
    }
    
    .compensations-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }
    
    .compensations-actions {
        display: flex;
        gap: 10px;
    }
    
    .compensations-filter {
        display: flex;
        align-items: center;
        gap: 8px;
        background-color: #f5f6fa;
        border-radius: 8px;
        padding: 8px 15px;
        cursor: pointer;
    }
    
    .compensations-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .compensations-table th {
        text-align: left;
        padding: 12px 15px;
        font-size: 14px;
        font-weight: 500;
        color: #666;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .compensations-table td {
        padding: 12px 15px;
        font-size: 14px;
        color: #333;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .compensation-type {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .type-monthly {
        background-color: #e6f7ee;
        color: #00a389;
    }
    
    .type-quarterly {
        background-color: #e6f7ff;
        color: #0070f3;
    }
    
    .type-weekly {
        background-color: #fff4e5;
        color: #ff9500;
    }
    
    .type-yearly {
        background-color: #f0edff;
        color: #6c5dd3;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        gap: 5px;
        margin-top: 20px;
    }
    
    .pagination-item {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        border-radius: 8px;
        background-color: #f5f6fa;
        color: #333;
        text-decoration: none;
        font-size: 14px;
    }
    
    .pagination-item.active {
        background-color: #6c5dd3;
        color: white;
    }
    
    .pagination-separator {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        font-size: 14px;
        color: #666;
    }
    
    .btn-add {
        background-color: #6c5dd3;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 15px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .btn-add:hover {
        background-color: #5a4cbe;
    }
</style>
@endsection

@section('content')
<div class="compensations-container">
    <div class="compensations-header">
        <h2 class="compensations-title">Todas las Compensaciones</h2>
        <div class="compensations-actions">
            <button class="btn-add">
                <i class="fas fa-plus"></i>
                <span>Agregar Compensación</span>
            </button>
            <div class="compensations-filter">
                <i class="fas fa-filter"></i>
                <span>Filtrar</span>
            </div>
        </div>
    </div>
    
    <table class="compensations-table">
        <thead>
            <tr>
                <th>Monto</th>
                <th>Tipo</th>
                <th>Frecuencia</th>
                <th>Fecha Efectiva</th>
                <th>Fecha de Término</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>862.00 USD</td>
                <td><span class="compensation-type type-monthly">Mensual</span></td>
                <td>Cada mes</td>
                <td>10/05/2015</td>
                <td>-</td>
                <td>Activo</td>
                <td><i class="fas fa-ellipsis-h"></i></td>
            </tr>
            <tr>
                <td>1560.00 USD</td>
                <td><span class="compensation-type type-quarterly">Trimestral</span></td>
                <td>Cada trimestre</td>
                <td>08/06/2022</td>
                <td>-</td>
                <td>Activo</td>
                <td><i class="fas fa-ellipsis-h"></i></td>
            </tr>
            <tr>
                <td>378.00 USD</td>
                <td><span class="compensation-type type-weekly">Semanal</span></td>
                <td>Cada semana</td>
                <td>08/06/2022</td>
                <td>-</td>
                <td>Activo</td>
                <td><i class="fas fa-ellipsis-h"></i></td>
            </tr>
            <tr>
                <td>5000.00 USD</td>
                <td><span class="compensation-type type-yearly">Anual</span></td>
                <td>Cada año</td>
                <td>15/01/2022</td>
                <td>-</td>
                <td>Activo</td>
                <td><i class="fas fa-ellipsis-h"></i></td>
            </tr>
            <tr>
                <td>750.00 USD</td>
                <td><span class="compensation-type type-monthly">Mensual</span></td>
                <td>Cada mes</td>
                <td>01/03/2014</td>
                <td>09/05/2015</td>
                <td>Inactivo</td>
                <td><i class="fas fa-ellipsis-h"></i></td>
            </tr>
            <tr>
                <td>1200.00 USD</td>
                <td><span class="compensation-type type-quarterly">Trimestral</span></td>
                <td>Cada trimestre</td>
                <td>01/01/2020</td>
                <td>07/06/2022</td>
                <td>Inactivo</td>
                <td><i class="fas fa-ellipsis-h"></i></td>
            </tr>
        </tbody>
    </table>
    
    <div class="pagination">
        <a href="#" class="pagination-item"><i class="fas fa-chevron-left"></i></a>
        <a href="#" class="pagination-item active">1</a>
        <a href="#" class="pagination-item">2</a>
        <a href="#" class="pagination-item">3</a>
        <a href="#" class="pagination-item"><i class="fas fa-chevron-right"></i></a>
    </div>
</div>
@endsection
