@extends('layouts.app')

@section('title', 'Admin Panel')
@section('content')
    <div class="container py-4">
        <h4 class="fw-bold mb-3">Add Address</h4>

        <form action="{{ route('customer.address.store') }}" method="POST">
            @csrf

            <input type="text" name="name" class="form-control mb-2" placeholder="Receiver Name">

            <input type="text" name="phone" class="form-control mb-2" placeholder="Phone Number">

            <!-- PROVINCE -->
            <select name="province_id" id="province" class="form-control mb-2">
                <option value="">Select Province</option>
                @foreach ($provinces as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>

            <!-- CITY -->
            <select name="city_id" id="city" class="form-control mb-2">
                <option value="">Select City</option>
            </select>

            <!-- DISTRICT -->
            <select name="district_id" id="district" class="form-control mb-2">
                <option value="">Select District</option>
            </select>

            <textarea name="address" class="form-control mb-2" placeholder="Full Address"></textarea>

            <div class="form-check mb-3">
                <input type="hidden" name="is_default" value="0">
                <input type="checkbox" name="is_default" value="1">
                <label class="form-check-label">Set as primary</label>
            </div>

            <button class="btn btn-success w-100">Save Address</button>
        </form>
    </div>

    <script>
        // 🔥 PROVINCE → CITY
        document.getElementById('province').addEventListener('change', function() {
            let provinceId = this.value;

            fetch(`/home/cities/${provinceId}`)
                .then(res => res.json())
                .then(data => {
                    let city = document.getElementById('city');
                    city.innerHTML = '<option value="">Select City</option>';

                    data.forEach(item => {
                        city.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                    });

                    // reset district
                    document.getElementById('district').innerHTML = '<option value="">Select District</option>';
                });
        });

        // 🔥 CITY → DISTRICT
        document.getElementById('city').addEventListener('change', function() {
            let cityId = this.value;

            fetch(`/home/districts/${cityId}`)
                .then(res => res.json())
                .then(data => {
                    let district = document.getElementById('district');
                    district.innerHTML = '<option value="">Select District</option>';

                    data.forEach(item => {
                        district.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                    });
                });
        });
    </script>
@endsection
