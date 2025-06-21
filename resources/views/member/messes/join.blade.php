@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="page-title">Join a Mess</h4>
            <p class="text-muted mb-md-0">Find and request to join available messes.</p>
        </div>
        <div class="col-md-4">
             <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-search"></i></span>
                <input type="text" id="mess-search-input" class="form-control border-start-0" placeholder="Search by mess name...">
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <div class="mb-4">
        <label class="form-label">Your Mess Slots ({{$userMessCount}}/2)</label>
        <div class="progress" style="height: 20px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($userMessCount / 2) * 100 }}%;" aria-valuenow="{{ $userMessCount }}" aria-valuemin="0" aria-valuemax="2"></div>
        </div>
        @if ($userMessCount >= 2)
        <div class="alert alert-warning mt-3">You have reached the maximum number of messes you can join.</div>
        @endif
    </div>

    <div class="row" id="mess-list-container">
        @include('member.messes._join_mess_list', ['availableMesses' => $availableMesses, 'userMessCount' => $userMessCount])
    </div>
    <div id="pagination-container" class="d-flex justify-content-center mt-4">
        {{ $availableMesses->links() }}
    </div>
</div>
@endsection

@push('styles')
<style>
.mess-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.mess-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let searchTimeout;

    function fetchMesses(url) {
        $('#mess-list-container').html('<div class="col-12 text-center p-5"><i class="fas fa-spinner fa-spin fa-3x"></i></div>');

        $.ajax({
            url: url,
            success: function(data) {
                $('#mess-list-container').html(data);
                $('#pagination-container').html($(data).find('.pagination').parent().html());
            },
            error: function() {
                alert('An error occurred while fetching messes.');
            }
        });
    }

    $('#mess-search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val();
        searchTimeout = setTimeout(function() {
            let url = '{{ route("member.messes.join") }}?search=' + query;
            fetchMesses(url);
        }, 300);
    });

    $(document).on('click', '#pagination-container .pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        fetchMesses(url);
    });
});
</script>
@endpush 