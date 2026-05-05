<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Certificate</title>

<style>
@page {
    size: A4 landscape;
    margin: 0;
}

html, body {
    margin: 0;
    height: 100%;
    font-family: DejaVu Sans, sans-serif;
}

/* PAGE */
.page {
    width: 100%;
    height: 100%;
    position: relative;
    overflow: hidden;
}

/* LEFT DESIGN */
.bg {
    position: absolute;
    left: 0;
    top: 0;
    width: 40%;
    height: 100%;
    background: linear-gradient(135deg, #ff6fa5, #b46bff);
    border-top-right-radius: 200px;
    border-bottom-right-radius: 200px;
}

.bg::after {
    content: "";
    position: absolute;
    left: 40px;
    top: 50px;
    width: 100%;
    height: 85%;
    background: linear-gradient(135deg, #ff3f8e, #9b59ff);
    border-top-right-radius: 200px;
    border-bottom-right-radius: 200px;
    opacity: 0.7;
}

/* CONTENT */
.content {
    position: relative;
    z-index: 2;
    padding: 80px 100px;
    padding-bottom: 140px;
    text-align: center;
}

/* TITLE */
.title {
    font-size: 46px;
    font-weight: bold;
    color: #d64b8a;
}

/* SUBTITLE */
.subtitle {
    margin-top: 15px;
    font-size: 14px;
    color: #555;
}

/* NAME */
.name {
    margin: 30px auto;
    width: 60%;
    border-bottom: 2px solid #ccc;
    font-size: 24px;
    padding-bottom: 10px;
}

/* COURSE */
.course {
    margin-top: 20px;
    font-size: 15px;
    color: #555;
}

/* RESULT INFO */
.info {
    margin-top: 15px;
    font-size: 13px;
}

/* FOOTER */
.footer {
    margin-top: 60px;
    display: flex;
    justify-content: flex-start;
    gap: 80px;
    padding-left: 120px;
}

/* SIGN BLOCK */
.sign {
    text-align: left;
}

/* LINE */
.line {
    border-top: 1px solid #999;
    width: 180px;
    margin-bottom: 5px;
}

/* TEXT */
.small {
    font-size: 12px;
    color: #666;
}

/* ✅ MORE GAP ADDED */
.date {
    margin-bottom: 25px; /* increased from 15px → 25px */
}

</style>
</head>

<body>

<div class="page">

    <!-- LEFT DESIGN -->
    <div class="bg"></div>

    <!-- CONTENT -->
    <div class="content">

        <div class="title">CERTIFICATE OF COMPLETION</div>

        <div class="subtitle">
            THIS CERTIFICATE IS PRESENTED TO
        </div>

        <div class="name">
            {{ $user->name }}
        </div>

        <div class="course">
            FOR THE SUCCESSFUL COMPLETION OF<br>
            <b>{{ $quiz->title }}</b>
        </div>

        <div class="info">
            Score: {{ $result->score }}/{{ $result->total }} |
            {{ $result->percentage }}% |
            Grade: {{ $result->grade() }} |
            Rank: #{{ $result->rank }}
        </div>

        @if($result->rank == 1)
            <div style="margin-top:5px; color:gold;">
                🏆 Top Performer
            </div>
        @endif

        <!-- FOOTER -->
        <div class="footer">

            <div class="sign">
                <div class="small date">
                    {{ now()->format('d-m-Y') }}
                </div>

                <div class="line"></div>

                <div class="small">SIGNATURE</div>
            </div>

        </div>

    </div>

</div>

</body>
</html>