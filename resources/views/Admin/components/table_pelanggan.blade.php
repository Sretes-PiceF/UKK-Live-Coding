@foreach ($pelanggan as $p)
    <tr>
        <td>{{ $p->id_pelanggan }}</td>
        <td>{{ $p->nama_pelanggan }}</td>
        <td>{{ $p->alamat }}</td>
        <td>{{ $p->no_kwh }}</td>
        <td>
            <a href="{{ route('admin.pelanggan.edit', $p->id_pelanggan) }}" class="btn btn-warning btn-sm">
                <i class="fa fa-edit"></i>
            </a>
        </td>
    </tr>
@endforeach