# kafyx-3/permission-gate

Role-based access control for Laravel Blade. No config files, no `Gate::define`, no migrations — just install and use.

---

## Install

```bash
composer require kafyx-3/permission-gate
```

Auto-discovered. Nothing else to do.

---

## Usage

### `@can` with inline roles

Pass an array as the second argument and the package handles it directly.

```blade
@can('edit-alat', ['admin', 'petugas'])
    <a href="{{ route('alat.edit', $item->id) }}" class="btn btn-warning">Edit</a>
@endcan

@can('hapus-alat', ['admin'])
    <form action="{{ route('alat.destroy', $item->id) }}" method="POST">
        @csrf @method('DELETE')
        <button class="btn btn-danger">Delete</button>
    </form>
@endcan
```

Without the array, `@can` falls back to Laravel's Gate and Policy as normal.

```blade
@can('edit-alat')       {{-- uses Gate::define if you have one --}}
@can('update', $post)   {{-- policy check, still works --}}
```

### `@elsecan` and `@cannot`

```blade
@can('setujui-peminjaman', ['admin', 'petugas'])
    <span class="badge bg-success">Can Approve</span>
@elsecan('buat-peminjaman', ['peminjam'])
    <span class="badge bg-info">Can Borrow</span>
@endcan

@cannot('hapus-alat', ['admin'])
    <span class="text-muted">No delete access</span>
@endcannot
```

### `@hasRole`

For when you just want to check the role without attaching an ability name.

```blade
@hasRole('admin', 'petugas')
    <span class="badge bg-success">Staff</span>
@endhasRole

@hasRole(['admin', 'petugas'])
    <span class="badge bg-success">Staff</span>
@endhasRole

@hasRole('admin')
    Admin
@elsehasRole('petugas')
    Petugas
@elsehasRole('peminjam')
    Peminjam
@endhasRole
```

---

## CRUD Table Example

The `<th>` and `<td>` for the action column must use the same condition, otherwise the column appears empty for some roles.

```blade
<div class="d-flex justify-content-between mb-3">
    <h4>Daftar Alat</h4>
    @can('tambah-alat', ['admin', 'petugas'])
        <a href="{{ route('alat.create') }}" class="btn btn-primary">+ Tambah Alat</a>
    @endcan
</div>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Nama Alat</th>
            <th>Stok</th>
            <th>Kondisi</th>
            @hasRole('admin', 'petugas')
                <th>Aksi</th>
            @endhasRole
        </tr>
    </thead>
    <tbody>
        @forelse ($alat as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->stok }}</td>
            <td>{{ $item->kondisi }}</td>
            @hasRole('admin', 'petugas')
            <td class="d-flex gap-1">
                @can('edit-alat', ['admin', 'petugas'])
                    <a href="{{ route('alat.edit', $item->id) }}"
                       class="btn btn-sm btn-warning">Edit</a>
                @endcan
                @can('hapus-alat', ['admin'])
                    <form action="{{ route('alat.destroy', $item->id) }}"
                          method="POST"
                          onsubmit="return confirm('Hapus?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                @endcan
            </td>
            @endhasRole
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center text-muted">No data.</td>
        </tr>
        @endforelse
    </tbody>
</table>
```

---

## Requirements

- PHP 8.1+
- Laravel 10 or 11

## License

MIT © [kafyx-3](https://github.com/kafyx-3)
