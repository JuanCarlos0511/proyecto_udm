<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class BillController extends Controller
{
    /**
     * Display a listing of the bills.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $bills = Bill::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.bills.index', compact('bills'));
    }

    /**
     * Show the form for creating a new bill.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $patients = User::where('role', 'paciente')->where('status', 'active')->get();
        return view('admin.bills.create', compact('patients'));
    }

    /**
     * Store a newly created bill in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Registrar los datos recibidos para depuración
            \Log::info('Datos recibidos en BillController@store:', [
                'request_all' => $request->all(),
                'is_ajax' => $request->ajax(),
                'content_type' => $request->header('Content-Type'),
                'user_id' => $request->user_id,
                'cuenta_con_seguro' => $request->cuenta_con_seguro
            ]);
            
            // Validar los datos
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'rfc' => 'required|string|max:13',
                'codigo_postal' => 'required|string|max:10',
                'cuenta_con_seguro' => 'sometimes|boolean',
                'regimen_fiscal' => 'required|string|max:255',
                'cfdi' => 'required|string|max:255',
                'status' => 'sometimes|in:pendiente,realizada',
            ]);
    
            // Si la validación falla
            if ($validator->fails()) {
                \Log::warning('Validación fallida en BillController@store:', [
                    'errors' => $validator->errors()->toArray()
                ]);
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ], 422);
                } else {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
            }
    
            // Preparar los datos para la factura
            $billData = [
                'user_id' => $request->user_id,
                'rfc' => $request->rfc,
                'codigo_postal' => $request->codigo_postal,
                'regimen_fiscal' => $request->regimen_fiscal,
                'cfdi' => $request->cfdi,
                'status' => $request->status ?? 'pendiente',
                'cuenta_con_seguro' => $request->has('cuenta_con_seguro') ? (bool)$request->cuenta_con_seguro : false,
            ];
            
            \Log::info('Datos preparados para crear factura:', $billData);
    
            // Crear la factura
            $bill = Bill::create($billData);
            \Log::info('Factura creada con ID: ' . $bill->id);
    
            // Cargar la relación de usuario para la respuesta
            $bill->load('user');
    
            // Devolver respuesta según el tipo de solicitud
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Factura creada exitosamente.',
                    'bill' => $bill
                ]);
            } else {
                return redirect()->route('admin.bills.index')
                    ->with('success', 'Factura creada exitosamente.');
            }
        } catch (\Exception $e) {
            // Registrar el error
            \Log::error('Error en BillController@store: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Devolver respuesta de error
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar la factura: ' . $e->getMessage()
                ], 500);
            } else {
                return redirect()->back()
                    ->with('error', 'Error al guardar la factura: ' . $e->getMessage())
                    ->withInput();
            }
        }
    }

    /**
     * Display the specified bill.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $bill = Bill::with('user')->findOrFail($id);
        return view('admin.bills.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified bill.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $bill = Bill::findOrFail($id);
        $patients = User::where('role', 'paciente')->where('status', 'active')->get();
        return view('admin.bills.edit', compact('bill', 'patients'));
    }

    /**
     * Update the specified bill in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $bill = Bill::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'rfc' => 'required|string|max:13',
            'codigo_postal' => 'required|string|max:10',
            'cuenta_con_seguro' => 'required|boolean',
            'regimen_fiscal' => 'required|string|max:255',
            'cfdi' => 'required|string|max:255',
            'status' => 'required|in:pendiente,realizada',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $bill->update([
            'user_id' => $request->user_id,
            'rfc' => $request->rfc,
            'codigo_postal' => $request->codigo_postal,
            'cuenta_con_seguro' => $request->cuenta_con_seguro,
            'regimen_fiscal' => $request->regimen_fiscal,
            'cfdi' => $request->cfdi,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.bills.index')
            ->with('success', 'Factura actualizada exitosamente.');
    }

    /**
     * Remove the specified bill from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $bill = Bill::findOrFail($id);
        $bill->delete();

        return redirect()->route('admin.bills.index')
            ->with('success', 'Factura eliminada exitosamente.');
    }

    /**
     * Display the bill history.
     *
     * @return \Illuminate\View\View
     */
    public function history()
    {
        $bills = Bill::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.bills.history', compact('bills'));
    }

    /**
     * Generate a new bill.
     *
     * @return \Illuminate\View\View
     */
    public function generate()
    {
        $patients = User::where('role', 'paciente')->where('status', 'active')->get();
        return view('admin.bills.generate', compact('patients'));
    }

    /**
     * Get bills data for AJAX requests.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBillsData()
    {
        // Obtener todas las facturas con la información del usuario
        $bills = Bill::with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($bill) {
                return [
                    'id' => $bill->id,
                    'patient_name' => $bill->user->name,
                    'patient_email' => $bill->user->email,
                    'rfc' => $bill->rfc,
                    'codigo_postal' => $bill->codigo_postal,
                    'cuenta_con_seguro' => $bill->cuenta_con_seguro,
                    'regimen_fiscal' => $bill->regimen_fiscal,
                    'cfdi' => $bill->cfdi,
                    'status' => $bill->status,
                    'created_at' => Carbon::parse($bill->created_at)->format('d M, Y'),
                ];
            });
        
        // Calcular estadísticas
        $currentMonth = Carbon::now()->startOfMonth();
        $monthlyBills = Bill::where('created_at', '>=', $currentMonth)->get();
        
        $statistics = [
            'monthly_income' => number_format($monthlyBills->count() * 350, 2), // Asumiendo un promedio de $350 por factura
            'total_bills' => Bill::count(),
        ];
        
        return response()->json([
            'bills' => $bills,
            'statistics' => $statistics
        ]);
    }
}
