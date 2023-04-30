<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pengingat Tagihan</title>
  </head>
  <body>
    <p>Halo {{ $data['name'] }},</p>
    <p>Ini adalah pengingat untuk pembayaran tagihan Anda. Jangan lupa untuk segera melakukan pembayaran sebelum jatuh tempo ya.</p>
    <table>
      <thead>
        <tr>
          <th>No. Tagihan</th>
          <th>Jumlah Tagihan</th>
          <th>Tanggal Jatuh Tempo</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>{{ $data['no_tagihan'] }}</td>
          <td>{{ $jumlah_tagihan }}</td>
          <td>{{ $tanggal_jatuh_tempo }}</td>
        </tr>
      </tbody>
    </table>
    <p>Terima kasih.</p>
  </body>
</html>
