@extends('layouts.app')

@section('content')
    <button class="btn btn-primary mb-3" style="float: right;"
            onclick="window.location.href='{{ route('search.index') }}'">Search
    </button>
    <h2>Register for COVID Vaccine</h2>

    <div id="success-message" class="alert alert-success d-none"></div>
    <div id="form-errors" class="alert alert-danger d-none">
        <ul id="error-list"></ul>
    </div>

    <form id="registration-form">
        <div class="form-group">
            <label for="nid">NID<span class="required-marker">*</span></label>
            <input type="text" name="nid" id="nid" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="name">Name<span class="required-marker">*</span></label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth<span class="required-marker">*</span></label>
            <input type="date" name="dob" id="dob" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email<span class="required-marker">*</span></label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="vaccine_center_id">Vaccine Center<span class="required-marker">*</span></label>
            <select name="vaccine_center_id" class="form-control" required id="vaccine-center-select">
            </select>
            <div id="loading-message" class="mt-2">Loading vaccine centers...</div>
            <div id="error-message" class="alert alert-danger mt-2 d-none"></div>
        </div>
        <button type="button" class="btn btn-success mt-2" onclick="submitForm()">Register</button>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const apiEndpoint = "{{ env('APP_API_URL') . "/v1/registrations"}}";
        const vaccineCentersEndpoint = "{{ env('APP_API_URL') . "/v1/dropdown/vaccine-centers"}}";
        const selectElement = document.getElementById('vaccine-center-select');
        const errorMessage = document.getElementById('error-message');
        const loadingMessage = document.getElementById('loading-message');
        let offset = 0;
        const limit = -1;
        let fetching = false;

        // Load vaccine centers on page load
        document.addEventListener('DOMContentLoaded', () => {
            loadVaccineCenters();
        });

        async function loadVaccineCenters() {
            try {
                fetching = true;
                const response = await axios.get(`${vaccineCentersEndpoint}?limit=${limit}&offset=${offset}`);
                const centers = response.data.items;
                const pagination = response.data.metadata.pagination;

                centers.forEach(center => {
                    const option = document.createElement('option');
                    option.value = center.id;
                    option.textContent = center.name;
                    selectElement.appendChild(option);
                });

                // Update offset based on the pagination metadata
                offset = pagination.nextOffset;

                // Check if there are more pages to load
                if (pagination.currentPage < pagination.pageCount) {
                    fetching = false;
                } else {
                    loadingMessage.classList.add('d-none');
                }
            } catch (error) {
                errorMessage.textContent = 'Failed to load vaccine centers. Please try again later.';
                errorMessage.classList.remove('d-none');
                loadingMessage.classList.add('d-none');
            }
        }

        async function submitForm() {
            // Hide previous messages
            document.getElementById('success-message').classList.add('d-none');
            document.getElementById('form-errors').classList.add('d-none');
            document.getElementById('error-list').innerHTML = '';

            // Gather form data
            const formData = {
                nid: document.getElementById('nid').value,
                name: document.getElementById('name').value,
                dob: document.getElementById('dob').value,
                email: document.getElementById('email').value,
                vaccine_center_id: document.getElementById('vaccine-center-select').value
            };

            try {
                const response = await axios.post(apiEndpoint, formData);
                if (response.status === 200) {
                    document.getElementById('success-message').textContent = response.data.SUCCESS_MESSAGE;
                    document.getElementById('success-message').classList.remove('d-none');
                    document.getElementById('registration-form').reset();
                }
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    const errorList = document.getElementById('error-list');
                    for (const [field, messages] of Object.entries(error.response.data.errors)) {
                        messages.forEach(message => {
                            const li = document.createElement('li');
                            li.textContent = message;
                            errorList.appendChild(li);
                        });
                    }
                    document.getElementById('form-errors').classList.remove('d-none');
                } else {
                    alert('An unexpected error occurred. Please try again later.');
                }
            }
        }
    </script>
@endsection
