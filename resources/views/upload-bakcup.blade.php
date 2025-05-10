@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 50vh;">
        <div class="card shadow mb-4 w-100" style="max-width: 500px;">
            <div class="card-body">
                <h5 class="card-title mb-3 text-center">Upload CSV File</h5>
                <form id="upload-form" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="csv_file" class="form-label"></label>
                        <input type="file" name="csv_file" id="csv_file" class="form-control" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Card for upload history -->
    <div class="card shadow">
        <div class="card-body">
            <h5 class="card-title mb-3">Upload History</h5>

            <ul id="upload-list" class="list-group">
                @foreach($uploads as $upload)
                    <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $upload->id }}">
                        <span class="text-truncate me-3" style="max-width: 60%;">{{ $upload->file_path }}</span>
                        <span class="badge bg-{{ $upload->status === 'completed' ? 'success' : ($upload->status === 'failed' ? 'danger' : 'warning') }} rounded-pill">
                            {{ ucfirst($upload->status) }}
                        </span>
                        <small class="text-muted">{{ $upload->created_at->diffForHumans() }}</small>
                        @if($upload->status === 'failed')
                            <div class="mt-2 text-danger">
                                <strong>Failure Reason:</strong> {{ $upload->failure_reason }}
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('upload-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            fetch('{{ route('uploads.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Upload failed');
                return response.text();
            })
            .then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'File uploaded successfully!',
                    timer: 2000,
                    showConfirmButton: false
                });
                form.reset(); 
            })
            .catch(error => {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Upload failed',
                    text: error.message
                });
            });
        });
    </script>


@endsection
