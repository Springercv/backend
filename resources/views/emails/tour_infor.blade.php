<p>Dear {{ $account }},</p>

<div style="background: rgba(119,244,234,0.07)">
    <h1>{{ $data->name }}</h1>
    <p><b>Ngày bắt đầu: </b><span>{{ $data->start_date }}</span> </p>
    <p><b>Ngày kết thúc: </b><span>{{ $data->end_date }}</span></p>
    <p><b>Phương tiện: </b><span>{{ $data->vehicle }}</span></p>
    <p><b>Loại khách sạn: </b><span>{{ $data->hotel_type }}</span></p>
    <p><b>Tổng thời gian diễn ra: </b><span>{{ $data->period_date }} ngày</span></p>
    <h3>Mô tả:</h3>
    <p>{{ $data->description }}</p>
    <h1>Lộ trình cụ thể:</h1>
    <p>{!! $data->note !!}</p>
</div>

