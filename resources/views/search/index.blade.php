@extends('layouts.app')

@section('content')
    <button class="btn btn-primary mb-3" style="float: right;" onclick="window.location.href='{{ route('registration.create') }}'">Register</button>
    <h2>Search Registration Status</h2>

    <div id="success-message" class="alert alert-success d-none"></div>
    <div id="error-message" class="alert alert-danger d-none"></div>

    <form id="search-form">
        <div class="form-group">
            <label for="nid">NID<span class="required-marker">*</span></label>
            <input type="text" name="nid" id="nid" class="form-control" required>
        </div>
        <button type="button" class="btn btn-success mt-2" onclick="searchRegistration()">Search</button>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const searchEndpoint = "{{ env('APP_API_URL') . "/v1/search"}}" ;

        async function searchRegistration() {
            // Clear previous messages
            document.getElementById('success-message').classList.add('d-none');
            document.getElementById('error-message').classList.add('d-none');

            const nid = document.getElementById('nid').value;

            try {
                const response = await axios.post(searchEndpoint, { nid: nid });

                if (response.status === 200) {
                    document.getElementById('success-message').textContent = response.data.SUCCESS_MESSAGE;
                    document.getElementById('success-message').classList.remove('d-none');
                    document.getElementById('nid').classList.remove('d-none');
                }
            } catch (error) {
                if (error.response) {
                    document.getElementById('error-message').textContent = error.response.data.message || 'Failed to fetch registration status. Please check the NID and try again.';
                    document.getElementById('error-message').classList.remove('d-none');
                } else {
                    alert('An unexpected error occurred. Please try again later.');
                }
            }
        }
    </script>
@endsection
