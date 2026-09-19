@extends('admin.booking.search')

@section('search_results')
    <div class="page-wrapper search-page-wrapper">
        <div class="search-result">
            <h3 class="search-result-title">検索結果</h3>
            @if (count($bookings ?? []) > 0)
                <table class="shopsearchlist_table">
                    <tbody>
                        <tr>
                            <td nowrap="" id="customer_name">
                                顧客名
                            </td>
                            <td nowrap="" id="customer_contact">
                                顧客連絡先
                            </td>
                            <td nowrap="" id="hotel_name">
                                宿泊先ホテル
                            </td>
                            <td nowrap="" id="checkin_time">
                                チェックイン日時
                            </td>
                            <td nowrap="" id="checkout_time">
                                チェックアウト日時
                            </td>
                        </tr>
                        @foreach ($bookings as $booking)
                            <tr style="background-color:#BDF1FF">
                                <td>
                                    {{ $booking->customer_name }}
                                </td>
                                <td>
                                    {{ $booking->customer_contact }}
                                </td>
                                <td>
                                    {{ $booking->hotel->hotel_name ?? '-' }}
                                </td>
                                <td nowrap="">
                                    {{ $booking->checkin_time ? (string) $booking->checkin_time : '-' }}
                                </td>
                                <td nowrap="">
                                    {{ $booking->checkout_time ? (string) $booking->checkout_time : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>検索結果がありません</p>
            @endif
        </div>
    </div>
@endsection
