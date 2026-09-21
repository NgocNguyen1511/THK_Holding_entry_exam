@extends('admin.booking.search')

@section('search_results')
    <div class="page-wrapper search-page-wrapper">
        <div class="search-result">
            <h3 class="search-result-title">検索結果</h3>
            @if (!empty($bookings))
                <div style="overflow-x: auto; max-width: 100%;">
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
                                <td nowrap="" id="created_at">
                                    予約日時
                                </td>
                                <td nowrap="" id="updated_at">
                                    情報更新日時
                                </td>
                            </tr>
                            @foreach ($bookings as $booking)
                                <tr style="background-color:#BDF1FF">
                                    <td style="white-space: nowrap;">
                                        {{ $booking['customer_name'] }}
                                    </td>
                                    <td style="white-space: nowrap;">
                                        {{ $booking['customer_contact'] }}
                                    </td>
                                    <td style="white-space: nowrap;">
                                        {{ $booking['hotel']['hotel_name'] ?? '-' }}
                                    </td>
                                    <td nowrap="">
                                        {{ $booking['checkin_time'] ? (string) $booking['checkin_time'] : '-' }}
                                    </td>
                                    <td nowrap="">
                                        {{ $booking['checkout_time'] ? (string) $booking['checkout_time'] : '-' }}
                                    </td>
                                    <td nowrap="">
                                        {{ $booking['created_at'] ? (string) $booking['created_at'] : '-' }}
                                    </td>
                                    <td nowrap="">
                                        {{ $booking['updated_at'] ? (string) $booking['updated_at'] : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>検索結果がありません</p>
            @endif
        </div>
    </div>
@endsection
