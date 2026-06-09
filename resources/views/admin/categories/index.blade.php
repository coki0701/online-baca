@extends('admin.layouts.app')

@section('title', 'Kategori')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <div>
            <h2 class="fw-bold mb-1">
                📂 Data Kategori
            </h2>

            <p class="text-muted mb-0">
                Kelola kategori buku perpustakaan digital.
            </p>
        </div>

        <a href="{{ route('admin.categories.create') }}"
           class="btn btn-primary rounded-pill px-4">

            <i class="fa fa-plus me-1"></i>
            Tambah Kategori

        </a>

    </div>

    {{-- ALERT --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm rounded-4">
            {{ session('success') }}
        </div>

    @endif

    @if(session('error'))

    <div class="alert alert-danger border-0 shadow-sm rounded-4">

        {{ session('error') }}

    </div>

    @endif

    {{-- CARD --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            {{-- SEARCH --}}
            <form action="{{ route('admin.categories.index') }}"
                  method="GET"
                  class="row g-3 mb-4">

                <div class="col-lg-10">

                    <input type="text"
                           name="search"
                           class="form-control rounded-4 py-3"
                           placeholder="🔍 Cari kategori..."
                           value="{{ request('search') }}">

                </div>

                <div class="col-lg-2 d-flex gap-2">

                    <button class="btn btn-primary rounded-4 w-100">
                        Cari
                    </button>

                    <a href="{{ route('admin.categories.index') }}"
                       class="btn btn-secondary rounded-4 w-100">
                        Reset
                    </a>

                </div>

            </form>

            {{-- TABLE --}}
            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th width="80">No</th>
                            <th>Nama Kategori</th>
                            <th width="180" class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($categories as $category)

                        <tr>

                            <td>
                                {{ method_exists($categories, 'firstItem') ? $categories->firstItem() + $loop->index : $loop->iteration }}
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-3">

                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                         style="width:45px;height:45px;">

                                        <i class="fa fa-layer-group"></i>

                                    </div>

                                    <div>
                                        <h6 class="mb-0 fw-bold">
                                            {{ $category->name }}
                                        </h6>
                                    </div>

                                </div>
                            </td>

                            <td class="text-center">

                                <div class="d-flex justify-content-center gap-2">

                                    <a href="{{ route('admin.categories.edit', $category->id) }}"
                                       class="btn btn-warning btn-sm rounded-pill">

                                        <i class="fa fa-edit"></i>

                                    </a>

                                    <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                          method="POST"
                                          class="delete-form">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm rounded-pill">

                                            <i class="fa fa-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="3"
                                class="text-center py-5">

                                <div style="font-size:55px;">
                                    📂
                                </div>

                                <h5 class="fw-bold mt-3">
                                    Kategori Tidak Ditemukan
                                </h5>

                                <p class="text-muted mb-0">
                                    Coba gunakan kata kunci lain.
                                </p>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- PAGINATION --}}
            @if(method_exists($categories, 'links'))

                <div class="mt-4">
                    {{ $categories->links() }}
                </div>

            @endif

        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>

document.querySelectorAll('.delete-form').forEach(form => {

    form.addEventListener('submit', function(e){

        e.preventDefault();

        Swal.fire({
            title: 'Yakin hapus kategori?',
            text: 'Data yang dihapus tidak dapat dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {

            if(result.isConfirmed){
                form.submit();
            }

        });

    });

});

</script>

    </div>

</div>

@endsection