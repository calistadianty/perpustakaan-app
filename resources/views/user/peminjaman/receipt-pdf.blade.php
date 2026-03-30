<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Receipt #{{ str_pad($peminjaman->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        /* Thermal Receipt Style */
        body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 20px;
            color: #000;
            background-color: #fff;
            font-size: 14px;
            line-height: 1.4;
        }
        
        .receipt-container {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            border-bottom: 2px dashed #000;
            padding-bottom: 30px;
        }

        /* Header */
        .text-center {
            text-align: center;
        }
        .store-name {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 5px 0;
            letter-spacing: 1px;
        }
        .store-subtitle {
            font-size: 12px;
            margin: 0 0 15px 0;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 15px 0;
        }
        .divider-thick {
            border-top: 2px solid #000;
            margin: 15px 0;
        }

        /* Meta Info */
        .meta-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        .meta-left {
            display: table-cell;
            text-align: left;
            width: 40%;
        }
        .meta-right {
            display: table-cell;
            text-align: right;
            width: 60%;
        }

        /* Items */
        .item-details {
            margin: 20px 0;
        }
        .item-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
            display: block;
        }
        .item-meta {
            font-size: 12px;
            display: block;
            margin-bottom: 3px;
        }

        /* Status */
        .status-box {
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #000;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 18px;
            letter-spacing: 2px;
        }

        /* Footer */
        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 30px;
        }
        .barcode {
            font-family: 'Courier New', Courier, monospace;
            font-size: 20px;
            letter-spacing: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="text-center">
            <h1 class="store-name">RUMAH BACA</h1>
            <p class="store-subtitle">BUKTI PEMINJAMAN BUKU</p>
        </div>

        <div class="divider"></div>

        <!-- Meta -->
        <div class="meta-row">
            <div class="meta-left">NO. RECEIPT</div>
            <div class="meta-right">#{{ str_pad($peminjaman->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="meta-row">
            <div class="meta-left">TANGGAL CETAK</div>
            <div class="meta-right">{{ now()->format('d/m/Y H:i') }}</div>
        </div>
        <div class="meta-row">
            <div class="meta-left">PEMINJAM</div>
            <div class="meta-right">{{ strtoupper($peminjaman->user->name) }}</div>
        </div>

        <div class="divider-thick"></div>

        <!-- Item Info -->
        <div class="item-details">
            <span class="item-title">{{ strtoupper($peminjaman->book->judul) }}</span>
            <span class="item-meta">PENULIS : {{ strtoupper($peminjaman->book->penulis) }}</span>
            
            <div class="divider"></div>
            
            <div class="meta-row">
                <div class="meta-left">TGL PINJAM</div>
                <div class="meta-right">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y') }}</div>
            </div>
            <div class="meta-row">
                <div class="meta-left">BATAS KEMBALI</div>
                <div class="meta-right">{{ \Carbon\Carbon::parse($peminjaman->tanggal_batas_kembali)->format('d/m/Y') }}</div>
            </div>
            <div class="meta-row">
                <div class="meta-left">TGL KEMBALI</div>
                <div class="meta-right">
                    @if($peminjaman->tanggal_kembali)
                        {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </div>
            </div>
        </div>

        <div class="divider-thick"></div>

        <!-- Status Box -->
        <div class="status-box">
            STATUS: {{ $peminjaman->status }}
        </div>

        <div class="divider"></div>

        <!-- Footer -->
        <div class="footer">
            <p>TERIMA KASIH ATAS KUNJUNGAN ANDA</p>
            <p>Harap simpan struk ini sebagai bukti transaksi yang sah.</p>
            
            <div class="barcode">|||| | || ||| || ||</div>
            <p style="font-size: 10px;">{{ md5($peminjaman->id . $peminjaman->created_at) }}</p>
        </div>
    </div>
</body>
</html>
