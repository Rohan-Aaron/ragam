@extends('layouts.admin')

@section('title', 'Gallery')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('Admin.gallery.create') }}" class="btn btn-primary">+ Add Image</a>
    </div>

    <div class="card">
        <div class="card-datatable text-nowrap">
            <div class="d-flex justify-content-between align-items-center col flex-wrap m-2 gap-2">
                <form method="GET" action="{{ route('Admin.gallery.index') }}"
                    class="d-flex justify-content-center align-items-center">
                    <div class="dt-search mt-0 mt-md-6 d-flex justify-content-center align-items-center">
                        <label for="dt-search-0">Search</label>
                        <input type="search" name="search" class="form-control ms-2" id="dt-search-0"
                            placeholder="Search Image" value="{{ request('search') }}">
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead>
                        <tr>
                            <th>SL NO</th>
                            <th>Title</th>
                            <th>Thumbnail</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($galleries as $key => $gallery)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $gallery->title }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="image" width="50">
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('Admin.gallery.edit', $gallery->id) }}" class="btn btn-primary" title="Edit">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger"
                                            onclick="confirmDelete('{{ route('Admin.gallery.delete', $gallery->id) }}')">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No images found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function confirmDelete(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(url, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(() => {
                        Swal.fire('Deleted!', 'The image has been deleted.', 'success').then(() => {
                            window.location.reload();
                        });
                    })
                    .catch(() => {
                        Swal.fire('Error!', 'There was an issue deleting the image.', 'error');
                    });
                }
            });
        }
    </script>
@endsection
