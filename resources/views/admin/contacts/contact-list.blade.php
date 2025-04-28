@extends('layouts.admin')

@section('title', 'Contactos')

@section('breadcrumb')
    <span>Contactos</span>
@endsection

@section('page-title', 'Contactos')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/contacts.css') }}">
@endsection

@section('page-actions')
    <a href="{{ url('admin/contacts/create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Añadir Contacto
    </a>
@endsection

@section('content')
    <div class="filters-bar">
        <div class="filter-group">
            <label for="lastActivityDate">Última actividad</label>
            <select id="lastActivityDate" class="filter-select">
                <option value="all">Todas las fechas</option>
                <option value="today">Hoy</option>
                <option value="week">Esta semana</option>
                <option value="month">Este mes</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="createdDate">Fecha de creación</label>
            <select id="createdDate" class="filter-select">
                <option value="all">Todas las fechas</option>
                <option value="today">Hoy</option>
                <option value="week">Esta semana</option>
                <option value="month">Este mes</option>
            </select>
        </div>
        
        <button class="btn-filter">
            <span>Filtros Avanzados</span>
            <i class="fas fa-filter"></i>
        </button>
        
        <button class="btn-filter-clear">
            <i class="fas fa-redo"></i>
        </button>
    </div>
    
    <div class="table-container">
        <table class="admin-table contacts-table">
            <thead>
                <tr>
                    <th class="checkbox-column">
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th>Contacto</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Fecha de Registro</th>
                    <th class="actions-column"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="checkbox-column">
                        <input type="checkbox" class="row-checkbox">
                    </td>
                    <td>
                        <div class="contact-info">
                            <div class="contact-avatar" style="background-color: #8A2BE2;">ZZ</div>
                            <span class="contact-name">Zuraiz Zafar</span>
                        </div>
                    </td>
                    <td>zuraizklempk470@gmail.com</td>
                    <td>(684) 555-0102</td>
                    <td>Marzo 13, 2014</td>
                    <td class="actions-column">
                        <button class="action-btn" title="Más acciones">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td class="checkbox-column">
                        <input type="checkbox" class="row-checkbox">
                    </td>
                    <td>
                        <div class="contact-info">
                            <div class="contact-avatar" style="background-color: #4169E1;">SU</div>
                            <span class="contact-name">Sami Ullah</span>
                        </div>
                    </td>
                    <td>mannhachi05@gmail.com</td>
                    <td>(316) 555-0116</td>
                    <td>Noviembre 16, 2014</td>
                    <td class="actions-column">
                        <button class="action-btn" title="Más acciones">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td class="checkbox-column">
                        <input type="checkbox" class="row-checkbox">
                    </td>
                    <td>
                        <div class="contact-info">
                            <div class="contact-avatar" style="background-color: #DC143C;">CW</div>
                            <span class="contact-name">Cameron Williamson</span>
                        </div>
                    </td>
                    <td>cjctm12@gmail.com</td>
                    <td>(225) 555-0118</td>
                    <td>Febrero 9, 2015</td>
                    <td class="actions-column">
                        <button class="action-btn" title="Más acciones">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td class="checkbox-column">
                        <input type="checkbox" class="row-checkbox">
                    </td>
                    <td>
                        <div class="contact-info">
                            <div class="contact-avatar" style="background-color: #6B8E23;">KM</div>
                            <span class="contact-name">Kathryn Murphy</span>
                        </div>
                    </td>
                    <td>nvt.last.nude@gmail.com</td>
                    <td>(405) 555-0128</td>
                    <td>Febrero 11, 2014</td>
                    <td class="actions-column">
                        <button class="action-btn" title="Más acciones">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td class="checkbox-column">
                        <input type="checkbox" class="row-checkbox">
                    </td>
                    <td>
                        <div class="contact-info">
                            <div class="contact-avatar" style="background-color: #2E8B57;">RE</div>
                            <span class="contact-name">Ralph Edwards</span>
                        </div>
                    </td>
                    <td>danghoang87@hotmail.com</td>
                    <td>(702) 555-0122</td>
                    <td>Noviembre 7, 2017</td>
                    <td class="actions-column">
                        <button class="action-btn" title="Más acciones">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td class="checkbox-column">
                        <input type="checkbox" class="row-checkbox">
                    </td>
                    <td>
                        <div class="contact-info">
                            <div class="contact-avatar" style="background-color: #FFD700;">JJ</div>
                            <span class="contact-name">Jacob Jones</span>
                        </div>
                    </td>
                    <td>vuhaithuongnute@gmail.com</td>
                    <td>(603) 555-0123</td>
                    <td>Octubre 24, 2018</td>
                    <td class="actions-column">
                        <button class="action-btn" title="Más acciones">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="pagination-controls">
        <div class="pagination-info">
            <span>Mostrando</span>
            <select id="pageSize" class="page-size-select">
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span>de 500</span>
        </div>
        
        <div class="pagination">
            <button class="pagination-btn" title="Anterior">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="pagination-btn active">1</button>
            <button class="pagination-btn">2</button>
            <button class="pagination-btn">3</button>
            <button class="pagination-btn">4</button>
            <span class="pagination-ellipsis">...</span>
            <button class="pagination-btn">20</button>
            <button class="pagination-btn" title="Siguiente">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin/contacts.js') }}"></script>
@endsection
