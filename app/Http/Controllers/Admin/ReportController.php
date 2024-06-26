<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\DB;
use App\Models\{
    RoomType,
    BedType,
    Room,
    Amenity,
    Complement,
    RoomTypeAmenity,
    RoomTypeComplement,
    Booking,
    PaymentMode,
    PaymentLog,
};

class ReportController extends Controller
{
    

    public function index(Request $request)
    {
        $selectedMonth = Carbon::now()->startOfMonth();

        if ($request->has('selected_month')) {
            $selectedMonth = Carbon::createFromFormat('Y-m', $request->selected_month)->startOfMonth();
        }

        $startDate = $selectedMonth->copy()->startOfMonth();
        $endDate = $selectedMonth->copy()->endOfMonth();

        $bookings = Booking::whereBetween('check_in', [$startDate, $endDate])
                            ->selectRaw('DATE(check_in) as date, COUNT(*) as bookingCount, SUM(paid_amount) as totalCollection')
                            ->groupBy('date')
                            ->get();

        $months = collect([
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ]);

        if ($request->has('pdf')) {
            $filename = 'booking_summary-' . $selectedMonth->format('F') . '.pdf';

            $pdf = PDF::loadView('admin.reports.monthly_pdf', compact('bookings', 'selectedMonth', 'months'));
            return $pdf->download($filename);
        }

        return view('admin.reports.index', compact('bookings', 'selectedMonth', 'months'));
    }


    public function yearly(Request $request)
    {
        $selectedYear = Carbon::now();
        
        if ($request->has('selected_year')) {
            $selectedYear = Carbon::createFromDate($request->selected_year, 1, 1);
        }
        
        $startDate = $selectedYear->copy()->startOfYear();
        $endDate = $selectedYear->copy()->endOfYear();
        
        $bookings = Booking::whereBetween('check_in', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($booking) {
                return Carbon::parse($booking->check_in)->format('F');
            })
            ->map(function ($bookingGroup) {
                return [
                    'bookingCount' => $bookingGroup->count(),
                    'totalCollection' => $bookingGroup->sum('paid_amount'),
                ];
            });

        if ($request->has('pdf')) {
            $filename = 'yearly_summary-' . $selectedYear->format('Y') . '.pdf';

            $pdf = PDF::loadView('admin.reports.yearly_pdf', compact('bookings', 'selectedYear'));
            return $pdf->download($filename);
        }
    
        return view('admin.reports.yearly', compact('bookings', 'selectedYear'));
    }

    public function custom(Request $request)
    {
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        
        $bookings = Booking::whereBetween('check_in', [$startDate, $endDate])
                            ->selectRaw('DATE(check_in) as date, COUNT(*) as bookingCount, SUM(paid_amount) as totalCollection')
                            ->groupBy('date')
                            ->get();  
    
        if ($request->has('pdf')) {
            $currentDateTime = now()->format('dMy');
            $filename = 'booking_summary_' . $currentDateTime . '.pdf';
    
            $pdf = PDF::loadView('admin.reports.custom_pdf', compact('bookings', 'startDate', 'endDate'));
            return $pdf->download($filename);
        }
    
        return view('admin.reports.custom', compact('bookings', 'startDate', 'endDate'));
    }
    
    public function collection(Request $request)
    {
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
    
        $bookings = Booking::whereDate('created_at', '>=', $startDate)
                           ->whereDate('created_at', '<=', $endDate)
                           ->get();
    
        $totalBookingFare = $bookings->sum('booking_fare');
        $totalTaxCharge = $bookings->sum('tax_charge');
        $totalExtraCharge = $bookings->sum('extra_charge');
        $totalPaidAmount = $bookings->sum('paid_amount');
        $totalRoundedValues = $bookings->sum('total_amount');
    
        if ($request->has('pdf')) {
            $filename = 'collection_report_' . $startDate . '_' . $endDate . '.pdf';
    
            $pdf = PDF::loadView('admin.reports.collection_pdf', compact('bookings', 'startDate', 'endDate', 'totalBookingFare', 'totalTaxCharge', 'totalExtraCharge', 'totalPaidAmount', 'totalRoundedValues'));
            return $pdf->download($filename);
        }
    
        return view('admin.reports.collection', compact('bookings', 'startDate', 'endDate', 'totalBookingFare', 'totalTaxCharge', 'totalExtraCharge', 'totalPaidAmount', 'totalRoundedValues'));
    }
    
    public function bookings(Request $request)
    {
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        
        $bookings = Booking::whereBetween('check_in', [$startDate, $endDate])
        ->join('booked_rooms', 'bookings.id', '=', 'booked_rooms.booking_id')
        ->join('rooms', 'booked_rooms.room_id', '=', 'rooms.id')
        ->selectRaw('rooms.room_number as room_number, COUNT(*) as bookingCount')
        ->groupBy('room_number')
        ->get();
     
    
        if ($request->has('pdf')) {
            $currentDateTime = now()->format('dMy');
            $filename = 'booking_summary_' . $currentDateTime . '.pdf';
    
            $pdf = PDF::loadView('admin.reports.booking_pdf', compact('bookings', 'startDate', 'endDate'));
            return $pdf->download($filename);
        }
    
        return view('admin.reports.bookings', compact('bookings', 'startDate', 'endDate'));
    }

    public function daily(Request $request)
    {
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        
       
        
        $bookings = Booking::select(DB::raw('bookings.id as booking_id, receipt_number,payment_modes.id as payment_mode_id,
                SUM(CASE WHEN payment_logs.payment_mode_id = "1" THEN payment_logs.amount ELSE 0 END) AS cash_collection,
                SUM(CASE WHEN payment_logs.payment_mode_id = "2" THEN payment_logs.amount ELSE 0 END) AS card_collection,
                SUM(CASE WHEN payment_logs.payment_mode_id = "3" THEN payment_logs.amount ELSE 0 END) AS upi_collection,
                SUM(payment_logs.amount) AS total_collection')
            )
            ->join('booked_rooms', 'bookings.id', '=', 'booked_rooms.booking_id')
            ->join('payment_logs', 'bookings.id', '=', 'payment_logs.booking_id')
            ->whereDate('payment_logs.created_at', '>=', $startDate)
            ->whereDate('payment_logs.created_at', '<=', $endDate)
            ->join('payment_modes', 'payment_logs.payment_mode_id', '=', 'payment_modes.id')
            ->groupBy('booking_id','.payment_modes.id','receipt_number') 
            ->get();
            // $totalcash = $bookings->sum('cash');
            // $totalcards = $bookings->sum('cards');
            // $totalupi = $bookings->sum('upi');
            // $totalAmount = $bookings->sum('totalAmount');

         
        

    
        return view('admin.reports.daily', compact('bookings', 'startDate', 'endDate'));
    }
    
    
    


    
}
